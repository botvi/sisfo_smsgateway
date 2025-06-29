<?php

namespace App\Http\Controllers\ketua_ekstrakurikuler;

use App\Models\MonitoringKegiatanExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SmsApi;
use RealRashid\SweetAlert\Facades\Alert;

class MonitoringEkstraController extends Controller
{
    public function index()
    {
        $monitoringEkstra = MonitoringKegiatanExtra::with('ketuaExtra')->orderBy('created_at', 'desc')->get();
        return view('pageketuaekstra.monitoring_ekstra.index', compact('monitoringEkstra'));
    }

    public function formkirimpesan()
    {
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')->get();
        $monitoringEkstra = MonitoringKegiatanExtra::all();
        return view('pageketuaekstra.monitoring_ekstra.form_kirim_pesan', compact('siswa', 'monitoringEkstra'));
    }

    public function kirimpesan(Request $request)
    {
        // Validasi input
        $request->validate([
            'kegiatan' => 'required'
        ]);

        // Simpan ke database terlebih dahulu
        $monitoringEkstra = MonitoringKegiatanExtra::create([
            'ketua_extra_id' => Auth::user()->id,
            'kegiatan' => $request->kegiatan,
        ]);

        // Ambil semua data siswa dengan orang tua
        $semuaSiswa = Siswa::with('orangTuaWali')->get();
        
        // Filter siswa yang memiliki nomor HP orang tua
        $siswaDenganHP = $semuaSiswa->filter(function($siswa) {
            return $siswa->orangTuaWali && !empty($siswa->orangTuaWali->no_hp_ortu);
        });

        if ($siswaDenganHP->isEmpty()) {
            Alert::error('Error', 'Tidak ada siswa dengan nomor HP orang tua yang tersedia');
            return redirect()->back();
        }

        // Buat link detail kegiatan dengan ID yang sudah tersimpan
        $linkDetail = route('monitoring-ekstra.detail', $monitoringEkstra->id);

        $message = "Kepada Yth. Bpk/Ibu Orang Tua,\n\n";
        $message .= "Kami informasikan bahwa ada kegiatan ekstrakurikuler baru yang akan dilaksanakan.\n";
        $message .= "Untuk detail lengkap kegiatan, silakan klik link berikut:\n";
        $message .= "{$linkDetail}\n\n";
        $message .= "Terima kasih.\nKetua Ekstrakurikuler.";

        // Ambil konfigurasi API dari database
        $smsApi = SmsApi::first();
        if (!$smsApi) {
            Alert::error('Error', 'Konfigurasi SMS API tidak ditemukan');
            return redirect()->back();
        }

        // Setup API key dan headers
        $apiKey = $smsApi->api_key;
        $headers = array(
            "Authorization: Basic " . base64_encode("apikey:" . $apiKey),
            "Content-Type: application/json"
        );

        $successCount = 0;
        $failedCount = 0;
        $failedNumbers = [];

        // Kirim SMS satu per satu
        foreach ($siswaDenganHP as $siswa) {
            $mobile = $siswa->orangTuaWali->no_hp_ortu;
            
            // Siapkan data untuk satu nomor
            $data = json_encode([
                [
                    'mobile' => $mobile,
                    'text' => $message
                ]
            ]);

            // Kirim request ke API SMS Text
            $ch = curl_init("https://api.smstext.app/push");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Log untuk debugging
            Log::info('SMS API Request - Single Send', [
                'mobile' => $mobile,
                'message' => $message,
                'http_code' => $httpCode,
                'curl_error' => $curlError
            ]);

            // Cek apakah ada error curl
            if ($curlError) {
                Log::error('Curl Error for ' . $mobile . ': ' . $curlError);
                $failedCount++;
                $failedNumbers[] = $mobile;
            } else {
                $results = json_decode($response, true);
                
                // Log response
                Log::info('SMS API Response - Single Send', [
                    'mobile' => $mobile,
                    'response' => $results,
                    'raw_response' => $response
                ]);

                // Cek response API untuk menentukan status
                if (is_array($results) && !empty($results) && is_string($results[0])) {
                    $successCount++;
                    Log::info('SMS berhasil dikirim ke: ' . $mobile);
                } else {
                    $failedCount++;
                    $failedNumbers[] = $mobile;
                    Log::error('SMS gagal dikirim ke: ' . $mobile . ' - Response: ' . $response);
                }
            }

            // Delay kecil antara request untuk menghindari rate limiting
            usleep(100000); // 0.1 detik delay
        }

        // Tampilkan hasil
        if ($successCount > 0) {
            $alertMessage = "Pesan berhasil dikirim ke {$successCount} orang tua";
            if ($failedCount > 0) {
                $alertMessage .= ", gagal dikirim ke {$failedCount} orang tua";
            }
            Alert::success('Berhasil', $alertMessage);
        } else {
            Alert::error('Gagal', 'Semua pesan gagal dikirim');
        }
        
        return redirect()->route('monitoring-ekstra.index');
    }

    public function detail($id)
    {
        $monitoringEkstra = MonitoringKegiatanExtra::with('ketuaExtra')->findOrFail($id);
        return view('pageketuaekstra.monitoring_ekstra.detail', compact('monitoringEkstra'));
    }

}

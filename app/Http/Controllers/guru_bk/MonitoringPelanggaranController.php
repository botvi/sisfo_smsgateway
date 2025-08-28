<?php

namespace App\Http\Controllers\guru_bk;

use App\Models\MonitoringPelanggaran;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SmsApi;
use RealRashid\SweetAlert\Facades\Alert;

class MonitoringPelanggaranController extends Controller
{
    public function index()
    {
        $monitoringPelanggaran = MonitoringPelanggaran::with('siswa', 'pelanggaran')->get();
        return view('pagegurubk.monitoring_pelanggaran.index', compact('monitoringPelanggaran'));
    }

    public function formkirimpesan()
    {
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')->get();
        $pelanggaran = Pelanggaran::all();
        return view('pagegurubk.monitoring_pelanggaran.form_kirim_pesan', compact('siswa', 'pelanggaran'));
    }

    public function kirimpesan(Request $request)
    {
        // Validasi input
        $request->validate([
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'siswa_id' => 'required|exists:siswas,id'
        ]);

        // Ambil data siswa untuk mendapatkan nomor telepon orang tua
        $siswa = Siswa::with('orangTuaWali')->find($request->siswa_id);
        
        if (!$siswa || !$siswa->orangTuaWali) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa atau orang tua tidak ditemukan'
            ]);
        }

        // Cek apakah nomor HP orang tua tersedia
        if (empty($siswa->orangTuaWali->no_hp_ortu)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP orang tua tidak tersedia'
            ]);
        }

        // Ambil data pelanggaran untuk membuat pesan otomatis
        $pelanggaran = Pelanggaran::find($request->pelanggaran_id);
        if (!$pelanggaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggaran tidak ditemukan'
            ]);
        }

        // Buat pesan otomatis berdasarkan data pelanggaran
        $message = "Kepada Yth. Bapak/Ibu Orang Tua dari {$siswa->nama_siswa}\n\n";
        $message .= "Dengan hormat, kami memberitahukan bahwa putra/putri Anda telah melakukan pelanggaran:\n";
        $message .= "- Jenis Pelanggaran: {$pelanggaran->nama_pelanggaran}\n";
        $message .= "- Tingkat Pelanggaran: {$pelanggaran->tingkat_pelanggaran}\n";
        $message .= "- Poin Pelanggaran: {$pelanggaran->poin_pelanggaran}\n\n";
        $message .= "Mohon perhatian dan bimbingan untuk putra/putri Anda.\n\n";
        $message .= "Terima kasih.\n";
        $message .= "Guru BK";

        // Jika ada pesan yang diinput manual, gunakan itu
        if (!empty($request->pesan)) {
            $message = $request->pesan;
            // Ganti placeholder [NAMA_SISWA] dengan nama siswa yang sebenarnya
            $message = str_replace('[NAMA_SISWA]', $siswa->nama_siswa, $message);
        }

        // Ambil konfigurasi API dari database
        $smsApi = SmsApi::first();
        if (!$smsApi) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi SMS API tidak ditemukan'
            ]);
        }

        // Data untuk dikirim sesuai format API
        $telepon = $siswa->orangTuaWali->no_hp_ortu;

        // Setup API key dan headers
        $apiKey = $smsApi->api_key;
        $headers = array(
            "Authorization: Basic " . base64_encode("apikey:" . $apiKey),
            "Content-Type: application/json"
        );

        // Format data sesuai endpoint API
        $data = json_encode([
            [
                'mobile' => $telepon,
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
        Log::info('SMS API Request', [
            'telepon' => $telepon,
            'message' => $message,
            'http_code' => $httpCode,
            'curl_error' => $curlError
        ]);

        // Cek apakah ada error curl
        if ($curlError) {
            Log::error('Curl Error: ' . $curlError);
            $status = 'Gagal';
            $alertMessage = 'Gagal terhubung ke server SMS';
            $alertType = 'error';
        } else {
            $results = json_decode($response, true);
            
            // Log response
            Log::info('SMS API Response', [
                'response' => $results,
                'raw_response' => $response
            ]);

            // Cek response API untuk menentukan status
            $status = 'Gagal';
            $alertMessage = 'Pesan gagal dikirim';
            $alertType = 'error';

            // Jika response berisi array dengan ID (contoh: ["f3724eda-6230-4c60-b875-744fb01778ae"])
            if (is_array($results) && !empty($results) && is_string($results[0])) {
                $status = 'Terkirim';
                $alertMessage = 'Pesan berhasil dikirim ke orang tua';
                $alertType = 'success';
            }
        }

        // Simpan ke database
        $monitoringPelanggaran = MonitoringPelanggaran::create([
            'guru_bk_id' => Auth::user()->id,
            'pelanggaran_id' => $request->pelanggaran_id,
            'siswa_id' => $request->siswa_id,
        ]);

        return response()->json([
            'success' => $alertType === 'success',
            'message' => $alertMessage,
            'status' => $status,
            'siswa' => $siswa->nama_siswa
        ]);
    }

    public function kirimpesanMultiple(Request $request)
    {
        // Validasi input
        $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswas,id',
            'pelanggaran_id' => 'required|exists:pelanggarans,id'
        ]);

        $siswaIds = $request->siswa_ids;
        $pelanggaranId = $request->pelanggaran_id;
        $pesan = $request->pesan;
        $results = [];

        foreach ($siswaIds as $siswaId) {
            try {
                // Ambil data siswa
                $siswa = Siswa::with('orangTuaWali')->find($siswaId);
                
                if (!$siswa || !$siswa->orangTuaWali) {
                    $results[] = [
                        'siswa_id' => $siswaId,
                        'success' => false,
                        'message' => 'Data siswa atau orang tua tidak ditemukan'
                    ];
                    continue;
                }

                // Cek nomor HP orang tua
                if (empty($siswa->orangTuaWali->no_hp_ortu)) {
                    $results[] = [
                        'siswa_id' => $siswaId,
                        'siswa_nama' => $siswa->nama_siswa,
                        'success' => false,
                        'message' => 'Nomor HP orang tua tidak tersedia'
                    ];
                    continue;
                }

                // Ambil data pelanggaran
                $pelanggaran = Pelanggaran::find($pelanggaranId);
                if (!$pelanggaran) {
                    $results[] = [
                        'siswa_id' => $siswaId,
                        'siswa_nama' => $siswa->nama_siswa,
                        'success' => false,
                        'message' => 'Data pelanggaran tidak ditemukan'
                    ];
                    continue;
                }

                // Buat pesan
                if (!empty($pesan)) {
                    $message = str_replace('[NAMA_SISWA]', $siswa->nama_siswa, $pesan);
                } else {
                    $message = "Kepada Yth. Bapak/Ibu Orang Tua dari {$siswa->nama_siswa}\n\n";
                    $message .= "Dengan hormat, kami memberitahukan bahwa putra/putri Anda telah melakukan pelanggaran:\n";
                    $message .= "- Jenis Pelanggaran: {$pelanggaran->nama_pelanggaran}\n";
                    $message .= "- Tingkat Pelanggaran: {$pelanggaran->tingkat_pelanggaran}\n";
                    $message .= "- Poin Pelanggaran: {$pelanggaran->poin_pelanggaran}\n\n";
                    $message .= "Mohon perhatian dan bimbingan untuk putra/putri Anda.\n\n";
                    $message .= "Terima kasih.\n";
                    $message .= "Guru BK";
                }

                // Kirim SMS
                $smsApi = SmsApi::first();
                if (!$smsApi) {
                    $results[] = [
                        'siswa_id' => $siswaId,
                        'siswa_nama' => $siswa->nama_siswa,
                        'success' => false,
                        'message' => 'Konfigurasi SMS API tidak ditemukan'
                    ];
                    continue;
                }

                $telepon = $siswa->orangTuaWali->no_hp_ortu;
                $apiKey = $smsApi->api_key;
                $headers = array(
                    "Authorization: Basic " . base64_encode("apikey:" . $apiKey),
                    "Content-Type: application/json"
                );

                $data = json_encode([
                    [
                        'mobile' => $telepon,
                        'text' => $message
                    ]
                ]);

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
                Log::info('SMS API Request Multiple', [
                    'telepon' => $telepon,
                    'message' => $message,
                    'http_code' => $httpCode,
                    'curl_error' => $curlError
                ]);

                $status = 'Gagal';
                $success = false;
                $messageResult = 'Pesan gagal dikirim';

                if ($curlError) {
                    Log::error('Curl Error Multiple: ' . $curlError);
                    $messageResult = 'Gagal terhubung ke server SMS';
                } else {
                    $apiResults = json_decode($response, true);
                    
                    Log::info('SMS API Response Multiple', [
                        'response' => $apiResults,
                        'raw_response' => $response
                    ]);

                    if (is_array($apiResults) && !empty($apiResults) && is_string($apiResults[0])) {
                        $status = 'Terkirim';
                        $success = true;
                        $messageResult = 'Pesan berhasil dikirim ke orang tua';
                    }
                }

                // Simpan ke database
                $monitoringPelanggaran = MonitoringPelanggaran::create([
                    'guru_bk_id' => Auth::user()->id,
                    'pelanggaran_id' => $pelanggaranId,
                    'siswa_id' => $siswaId,
                ]);

                $results[] = [
                    'siswa_id' => $siswaId,
                    'siswa_nama' => $siswa->nama_siswa,
                    'success' => $success,
                    'message' => $messageResult,
                    'status' => $status
                ];

            } catch (\Exception $e) {
                Log::error('Error in multiple SMS sending: ' . $e->getMessage());
                $results[] = [
                    'siswa_id' => $siswaId,
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    public function getOrangTua($siswa_id)
    {
        $siswa = Siswa::with('orangTuaWali')->find($siswa_id);
        
        if (!$siswa || !$siswa->orangTuaWali) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa atau orang tua tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_ortu' => $siswa->orangTuaWali->nama_ortu,
                'no_hp_ortu' => $siswa->orangTuaWali->no_hp_ortu
            ]
        ]);
    }

}

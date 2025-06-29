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
            Alert::error('Error', 'Data siswa atau orang tua tidak ditemukan');
            return redirect()->back();
        }

        // Cek apakah nomor HP orang tua tersedia
        if (empty($siswa->orangTuaWali->no_hp_ortu)) {
            Alert::error('Error', 'Nomor HP orang tua tidak tersedia');
            return redirect()->back();
        }

        // Ambil data pelanggaran untuk membuat pesan otomatis
        $pelanggaran = Pelanggaran::find($request->pelanggaran_id);
        if (!$pelanggaran) {
            Alert::error('Error', 'Data pelanggaran tidak ditemukan');
            return redirect()->back();
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
        }

        // Ambil konfigurasi API dari database
        $smsApi = SmsApi::first();
        if (!$smsApi) {
            Alert::error('Error', 'Konfigurasi SMS API tidak ditemukan');
            return redirect()->back();
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

        if ($alertType === 'success') {
            Alert::success('Berhasil', $alertMessage);
        } else {
            Alert::error('Gagal', $alertMessage);
        }
        
        return redirect()->route('monitoring-pelanggaran.index');
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

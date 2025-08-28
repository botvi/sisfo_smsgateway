<?php

namespace App\Http\Controllers\wali_kelas;

use App\Models\MonitoringAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SmsApi;
use RealRashid\SweetAlert\Facades\Alert;

class MonitoringAbsensiController extends Controller
{
    public function index()
    {
        $waliKelas = Auth::user();
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')
            ->whereHas('masterKelas', function ($query) use ($waliKelas) {
                $query->where('wali_kelas_id', $waliKelas->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $monitoringAbsensi = MonitoringAbsensi::where('wali_kelas_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pagewalikelas.monitoring_absensi.index', compact('monitoringAbsensi', 'siswa'));
    }

    public function formkirimpesan()
    {
        $waliKelas = Auth::user();
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')
            ->whereHas('masterKelas', function ($query) use ($waliKelas) {
                $query->where('wali_kelas_id', $waliKelas->id);
            })
            ->get();
        return view('pagewalikelas.monitoring_absensi.form_kirim_pesan', compact('siswa'));
    }

    public function kirimpesan(Request $request)
    {
        // Validasi input
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'pesan' => 'required|string|max:400'
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
    
        $message = "Kepada Yth. Bpk/Ibu Orang Tua dari {$siswa->nama_siswa},\n\n";
        $message .= "Kami informasikan bahwa:\n";
        $message .= "{$request->pesan}\n\n";
        $message .= "Terima kasih.\nWali Kelas.";
        
    
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
        $monitoringAbsensi = MonitoringAbsensi::create([
            'wali_kelas_id' => Auth::user()->id,
            'siswa_id' => $request->siswa_id,
            'pesan' => $request->pesan,
            'status' => $status,
            'tanggal_pengiriman' => now(),
        ]);
    
        return response()->json([
            'success' => $alertType === 'success',
            'message' => $alertMessage,
            'status' => $status,
            'siswa' => $siswa->nama_siswa
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

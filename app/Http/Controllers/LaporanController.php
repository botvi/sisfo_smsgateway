<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\MasterKelas;
use App\Models\OrangTuaWali;
use App\Models\MonitoringAbsensi;
use App\Models\MonitoringPelanggaran;
use App\Models\Pelanggaran;
use App\Models\MonitoringKegiatanExtra;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LaporanController extends Controller
{
    public function index()
    {
        $kelas = MasterKelas::with('waliKelas')->get();
        return view('laporan.index', compact('kelas'));
    }

    public function daftarSiswaDanOrtu(Request $request)
    {
        $kelas = MasterKelas::with('waliKelas')->get();
        $selectedKelas = $request->get('kelas_id');
        
        $query = Siswa::with(['masterKelas.waliKelas', 'orangTuaWali']);
        
        if ($selectedKelas) {
            $query->where('master_kelas_id', $selectedKelas);
        }
        
        $siswa = $query->orderBy('nama_siswa')->get();
        
        return view('laporan.laporan_daftarsiswadanortu', compact('siswa', 'kelas', 'selectedKelas'));
    }

    public function printDaftarSiswaDanOrtu(Request $request)
    {
        $selectedKelas = $request->get('kelas_id');
        $kelasInfo = null;
        
        $query = Siswa::with(['masterKelas.waliKelas', 'orangTuaWali']);
        
        if ($selectedKelas) {
            $query->where('master_kelas_id', $selectedKelas);
            $kelasInfo = MasterKelas::with('waliKelas')->find($selectedKelas);
        }
        
        $siswa = $query->orderBy('nama_siswa')->get();
        
        return view('laporan.print.print_daftarsiswadanortu', compact('siswa', 'kelasInfo'));
    }

    public function monitoringAbsensi(Request $request)
    {
        $kelas = MasterKelas::with('waliKelas')->get();
        $selectedKelas = $request->get('kelas_id');
        $selectedStatus = $request->get('status');
        $tanggalAwal = $request->get('tanggal_awal');
        $tanggalAkhir = $request->get('tanggal_akhir');
        
        $query = MonitoringAbsensi::with(['waliKelas', 'siswa.masterKelas', 'siswa.orangTuaWali']);
        
        // Filter berdasarkan kelas
        if ($selectedKelas) {
            $query->whereHas('siswa', function($q) use ($selectedKelas) {
                $q->where('master_kelas_id', $selectedKelas);
            });
        }
        
        // Filter berdasarkan status
        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalAwal) {
            $query->whereDate('tanggal_pengiriman', '>=', $tanggalAwal);
        }
        
        if ($tanggalAkhir) {
            $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
        }
        
        $absensi = $query->orderBy('tanggal_pengiriman', 'desc')->get();
        
        return view('laporan.laporan_monitoring_absensi', compact('absensi', 'kelas', 'selectedKelas', 'selectedStatus', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function printMonitoringAbsensi(Request $request)
    {
        $selectedKelas = $request->get('kelas_id');
        $selectedStatus = $request->get('status');
        $tanggalAwal = $request->get('tanggal_awal');
        $tanggalAkhir = $request->get('tanggal_akhir');
        $kelasInfo = null;
        
        $query = MonitoringAbsensi::with(['waliKelas', 'siswa.masterKelas', 'siswa.orangTuaWali']);
        
        // Filter berdasarkan kelas
        if ($selectedKelas) {
            $query->whereHas('siswa', function($q) use ($selectedKelas) {
                $q->where('master_kelas_id', $selectedKelas);
            });
            $kelasInfo = MasterKelas::with('waliKelas')->find($selectedKelas);
        }
        
        // Filter berdasarkan status
        if ($selectedStatus) {
            $query->where('status', $selectedStatus);
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalAwal) {
            $query->whereDate('tanggal_pengiriman', '>=', $tanggalAwal);
        }
        
        if ($tanggalAkhir) {
            $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
        }
        
        $absensi = $query->orderBy('tanggal_pengiriman', 'desc')->get();
        
        return view('laporan.print.print_monitoring_absensi', compact('absensi', 'kelasInfo', 'selectedStatus', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function monitoringPelanggaran(Request $request)
    {
        $kelas = MasterKelas::with('waliKelas')->get();
        $pelanggaran = Pelanggaran::all();
        $selectedKelas = $request->get('kelas_id');
        $selectedPelanggaran = $request->get('pelanggaran_id');
        
        $query = MonitoringPelanggaran::with(['guruBk', 'pelanggaran', 'siswa.masterKelas', 'siswa.orangTuaWali']);
        
        // Filter berdasarkan kelas
        if ($selectedKelas) {
            $query->whereHas('siswa', function($q) use ($selectedKelas) {
                $q->where('master_kelas_id', $selectedKelas);
            });
        }
        
        // Filter berdasarkan jenis pelanggaran
        if ($selectedPelanggaran) {
            $query->where('pelanggaran_id', $selectedPelanggaran);
        }
        
        $monitoringPelanggaran = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.laporan_monitoring_pelanggaran', compact('monitoringPelanggaran', 'kelas', 'pelanggaran', 'selectedKelas', 'selectedPelanggaran'));
    }

    public function printMonitoringPelanggaran(Request $request)
    {
        $selectedKelas = $request->get('kelas_id');
        $selectedPelanggaran = $request->get('pelanggaran_id');
        $kelasInfo = null;
        $pelanggaranInfo = null;
        
        $query = MonitoringPelanggaran::with(['guruBk', 'pelanggaran', 'siswa.masterKelas', 'siswa.orangTuaWali']);
        
        // Filter berdasarkan kelas
        if ($selectedKelas) {
            $query->whereHas('siswa', function($q) use ($selectedKelas) {
                $q->where('master_kelas_id', $selectedKelas);
            });
            $kelasInfo = MasterKelas::with('waliKelas')->find($selectedKelas);
        }
        
        // Filter berdasarkan jenis pelanggaran
        if ($selectedPelanggaran) {
            $query->where('pelanggaran_id', $selectedPelanggaran);
            $pelanggaranInfo = Pelanggaran::find($selectedPelanggaran);
        }
        
        $monitoringPelanggaran = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.print.print_monitoring_pelanggaran', compact('monitoringPelanggaran', 'kelasInfo', 'pelanggaranInfo'));
    }

    public function monitoringKegiatanEkstra(Request $request)
    {
        $ketuaExtra = User::where('role', 'ketua_ekstrakurikuler')->get();
        $selectedKetuaExtra = $request->get('ketua_extra_id');
        $tanggalAwal = $request->get('tanggal_awal');
        $tanggalAkhir = $request->get('tanggal_akhir');
        
        $query = MonitoringKegiatanExtra::with('ketuaExtra');
        
        // Filter berdasarkan ketua ekstrakurikuler
        if ($selectedKetuaExtra) {
            $query->where('ketua_extra_id', $selectedKetuaExtra);
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalAwal) {
            $query->whereDate('created_at', '>=', $tanggalAwal);
        }
        
        if ($tanggalAkhir) {
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }
        
        $kegiatan = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.laporan_monitoring_kegiatan_ekstra', compact('kegiatan', 'ketuaExtra', 'selectedKetuaExtra', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function printMonitoringKegiatanEkstra(Request $request)
    {
        $selectedKetuaExtra = $request->get('ketua_extra_id');
        $tanggalAwal = $request->get('tanggal_awal');
        $tanggalAkhir = $request->get('tanggal_akhir');
        $ketuaExtraInfo = null;
        
        $query = MonitoringKegiatanExtra::with('ketuaExtra');
        
        // Filter berdasarkan ketua ekstrakurikuler
        if ($selectedKetuaExtra) {
            $query->where('ketua_extra_id', $selectedKetuaExtra);
            $ketuaExtraInfo = User::find($selectedKetuaExtra);
        }
        
        // Filter berdasarkan tanggal
        if ($tanggalAwal) {
            $query->whereDate('created_at', '>=', $tanggalAwal);
        }
        
        if ($tanggalAkhir) {
            $query->whereDate('created_at', '<=', $tanggalAkhir);
        }
        
        $kegiatan = $query->orderBy('created_at', 'desc')->get();
        
        return view('laporan.print.print_monitoring_kegiatan_ekstra', compact('kegiatan', 'ketuaExtraInfo', 'tanggalAwal', 'tanggalAkhir'));
    }
}

<?php

namespace App\Http\Controllers\wali_kelas;

use App\Models\OrangTuaWali;
use App\Models\Siswa;
use App\Models\MasterKelas;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrangTuaWalidanSiswaController extends Controller
{
    public function index()
    {
        $waliKelas = Auth::user();
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')
            ->whereHas('masterKelas', function ($query) use ($waliKelas) {
                $query->where('wali_kelas_id', $waliKelas->id);
            })
            ->get();

        return view('pagewalikelas.orang_tua_wali_dan_siswa.index', compact('siswa'));
    }

    public function create()
    {
        $masterKelas = MasterKelas::where('wali_kelas_id', Auth::user()->id)->get();
        return view('pagewalikelas.orang_tua_wali_dan_siswa.create', compact('masterKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Validasi data orang tua/wali
            'nama_ortu' => 'required|string|max:255',
            'no_hp_ortu' => 'required|numeric|digits_between:10,15',
            'tanggal_lahir_ortu' => 'required|date',
            'alamat_ortu' => 'required|string',
            
            // Validasi data siswa
            'nama_siswa' => 'required|string|max:255',
            'tanggal_lahir_siswa' => 'required|date',
            'alamat_siswa' => 'required|string',
            'nisn_siswa' => 'required|numeric|unique:siswas,nisn_siswa',
            'master_kelas_id' => 'required|exists:master_kelas,id'
        ]);

        try {
            // Simpan data orang tua/wali
            $orangTuaWali = OrangTuaWali::create([
                'nama_ortu' => $request->nama_ortu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'tanggal_lahir_ortu' => $request->tanggal_lahir_ortu,
                'alamat_ortu' => $request->alamat_ortu
            ]);

            // Simpan data siswa
            $siswa = Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'tanggal_lahir_siswa' => $request->tanggal_lahir_siswa,
                'alamat_siswa' => $request->alamat_siswa,
                'nisn_siswa' => $request->nisn_siswa,
                'master_kelas_id' => $request->master_kelas_id,
                'orang_tua_wali_id' => $orangTuaWali->id
            ]);

            Alert::success('Berhasil', 'Data orang tua/wali dan siswa berhasil ditambahkan');
            return redirect()->route('orang-tua-wali-dan-siswa.index');
            
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $siswa = Siswa::with('orangTuaWali', 'masterKelas')->findOrFail($id);
        $masterKelas = MasterKelas::where('wali_kelas_id', Auth::user()->id)->get();
        return view('pagewalikelas.orang_tua_wali_dan_siswa.edit', compact('siswa', 'masterKelas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        $request->validate([
            // Validasi data orang tua/wali
            'nama_ortu' => 'required|string|max:255',
            'no_hp_ortu' => 'required|numeric|digits_between:10,15',
            'tanggal_lahir_ortu' => 'required|date',
            'alamat_ortu' => 'required|string',
            
            // Validasi data siswa
            'nama_siswa' => 'required|string|max:255',
            'tanggal_lahir_siswa' => 'required|date',
            'alamat_siswa' => 'required|string',
            'nisn_siswa' => 'required|numeric|unique:siswas,nisn_siswa,' . $id,
            'master_kelas_id' => 'required|exists:master_kelas,id'
        ]);

        try {
            // Update data orang tua/wali
            $siswa->orangTuaWali->update([
                'nama_ortu' => $request->nama_ortu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'tanggal_lahir_ortu' => $request->tanggal_lahir_ortu,
                'alamat_ortu' => $request->alamat_ortu
            ]);

            // Update data siswa
            $siswa->update([
                'nama_siswa' => $request->nama_siswa,
                'tanggal_lahir_siswa' => $request->tanggal_lahir_siswa,
                'alamat_siswa' => $request->alamat_siswa,
                'nisn_siswa' => $request->nisn_siswa,
                'master_kelas_id' => $request->master_kelas_id
            ]);

            Alert::success('Berhasil', 'Data orang tua/wali dan siswa berhasil diperbarui');
            return redirect()->route('orang-tua-wali-dan-siswa.index');
            
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $orangTuaWali = $siswa->orangTuaWali;
            
            // Hapus siswa terlebih dahulu
            $siswa->delete();
            
            // Hapus orang tua/wali
            $orangTuaWali->delete();
            
            Alert::success('Berhasil', 'Data orang tua/wali dan siswa berhasil dihapus');
            return redirect()->route('orang-tua-wali-dan-siswa.index');
            
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}

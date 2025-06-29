<?php

namespace App\Http\Controllers\admin;

use App\Models\MasterKelas;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class MasterKelasController extends Controller
{
    public function index()
    {
        $kelas = MasterKelas::with('waliKelas')->get();
        return view('pageadmin.master_kelas.index', compact('kelas'));
    }

    public function create()
    {
        $waliKelas = User::where('role', 'wali_kelas')->get();
        return view('pageadmin.master_kelas.create', compact('waliKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        MasterKelas::create($request->all());
        Alert::success('Sukses', 'Data kelas berhasil ditambahkan');
        return redirect()->route('master-kelas.index');
    }

    public function edit($id)
    {
        $kelas = MasterKelas::findOrFail($id);
        $waliKelas = User::where('role', 'wali_kelas')->get();
        return view('pageadmin.master_kelas.edit', compact('kelas', 'waliKelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        $kelas = MasterKelas::findOrFail($id);
        $kelas->update($request->all());
        
        Alert::success('Sukses', 'Data kelas berhasil diperbarui');
        return redirect()->route('master-kelas.index');
    }

    public function destroy($id)
    {
        $kelas = MasterKelas::findOrFail($id);
        $kelas->delete();
        
        Alert::success('Sukses', 'Data kelas berhasil dihapus');
        return redirect()->route('master-kelas.index');
    }
}

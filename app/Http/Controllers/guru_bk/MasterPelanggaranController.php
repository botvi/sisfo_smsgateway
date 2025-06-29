<?php

namespace App\Http\Controllers\guru_bk;

use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class MasterPelanggaranController extends Controller
{
    public function index()
    {
        $pelanggaran = Pelanggaran::all();
        return view('pagegurubk.master_pelanggaran.index', compact('pelanggaran'));
    }

    public function create()
    {
        return view('pagegurubk.master_pelanggaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'tingkat_pelanggaran' => 'required|string|max:255',
            'poin_pelanggaran' => 'required|integer',
        ]);

        Pelanggaran::create($request->all());
        Alert::success('Sukses', 'Data pelanggaran berhasil ditambahkan');
        return redirect()->route('master-pelanggaran.index');
    }

    public function edit($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        return view('pagegurubk.master_pelanggaran.edit', compact('pelanggaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'tingkat_pelanggaran' => 'required|string|max:255',
            'poin_pelanggaran' => 'required|integer',
        ]);

        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->update($request->all());
        
        Alert::success('Sukses', 'Data pelanggaran berhasil diperbarui');
        return redirect()->route('master-pelanggaran.index');
    }

    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();
        
        Alert::success('Sukses', 'Data pelanggaran berhasil dihapus');
        return redirect()->route('master-pelanggaran.index');
    }
}

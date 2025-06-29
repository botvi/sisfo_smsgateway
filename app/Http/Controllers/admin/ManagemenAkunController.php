<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManagemenAkunController extends Controller
{
  public function index()
  {
    $users = User::where('role', '!=', 'admin')->get();
    return view('pageadmin.managemen_akun.index', compact('users'));
  }

  public function create()
  {
    return view('pageadmin.managemen_akun.create');
  }

  public function store(Request $request)
  {
    $request->validate([
      'nama' => 'required',
      'username' => 'required|unique:users',
      'password' => 'required|min:6|confirmed',
      'role' => 'required',
    ]);

    // Buat user baru
    User::create([
      'nama' => $request->nama,
      'username' => $request->username,
      'password' => Hash::make($request->password),
      'role' => $request->role
    ]);

    Alert::success('Berhasil', 'Data User berhasil ditambahkan');
    return redirect()->route('managemen-akun.index');
  }

  public function show($id)
  {
    $user = User::findOrFail($id);
    return view('pageadmin.managemen_akun.show', compact('user'));
  }

  public function edit($id)
  {
    $user = User::findOrFail($id);
    return view('pageadmin.managemen_akun.edit', compact('user'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'nama' => 'required',
      'username' => 'required|unique:users,username,' . $id,
      'role' => 'required',
    ]);

    $user = User::findOrFail($id);
    
    // Update data user
    $user->update([
      'nama' => $request->nama,
      'username' => $request->username,
      'role' => $request->role,
    ]);

    // Update password jika diisi
    if ($request->filled('password')) {
      $request->validate([
        'password' => 'min:6|confirmed',
      ]);
      
      $user->update([
        'password' => Hash::make($request->password)
      ]);
    }

    Alert::success('Berhasil', 'Data User berhasil diperbarui');
    return redirect()->route('managemen-akun.index');
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    Alert::success('Berhasil', 'Data User berhasil dihapus');
    return redirect()->route('managemen-akun.index');
  }
}


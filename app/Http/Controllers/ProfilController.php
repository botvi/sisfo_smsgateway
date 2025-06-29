<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $data = User::where('id', auth()->user()->id)->first();
        return view('profil.index', compact('data'));
    }

    public function update(Request $request)
    {
        $admin = User::find(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $admin->id,
            'nama' => 'required', 
            'password' => 'nullable|min:8'
        ]);

        if($validator->fails()) {
            Alert::toast('Terjadi kesalahan! ' . $validator->errors()->first(), 'error')->position('top-end');
            return redirect()->back();
        }

    

        $data = $request->except(['password']);
        
        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);
        Alert::toast('Profil berhasil diubah!', 'success')->position('top-end');
        return redirect()->back();
    }
}

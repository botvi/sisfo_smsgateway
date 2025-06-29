<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SmsApi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SmsApiController extends Controller
{
    public function index()
    {
        $smsApi = SmsApi::first();
        return view('pageadmin.sms_api.index', compact('smsApi'));
    }

    public function storeorupdate(Request $request)
    {
        $smsApi = SmsApi::first();

        if ($smsApi) {
            // Update jika data sudah ada
            $smsApi->update($request->all());
            Alert::success('Success', 'Sms Api berhasil diubah');
        } else {
            // Create jika data belum ada
            SmsApi::create($request->all());
            Alert::success('Success', 'Sms Api berhasil ditambahkan');
        }

        return redirect()->route('sms-api.index');
    }
}
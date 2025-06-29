<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswas';
    protected $fillable = ['nama_siswa', 'tanggal_lahir_siswa', 'alamat_siswa', 'nisn_siswa', 'master_kelas_id', 'orang_tua_wali_id'];

    public function masterKelas()
    {
        return $this->belongsTo(MasterKelas::class, 'master_kelas_id');
    }

    public function orangTuaWali()
    {
        return $this->belongsTo(OrangTuaWali::class, 'orang_tua_wali_id');
    }
    
}

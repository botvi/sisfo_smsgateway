<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringAbsensi extends Model
{
    use HasFactory;
    protected $fillable = ['wali_kelas_id', 'siswa_id', 'pesan', 'status', 'tanggal_pengiriman'];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function masterKelas()
    {
        return $this->hasManyThrough(MasterKelas::class, Siswa::class, 'id', 'id', 'siswa_id', 'master_kelas_id');
    }
}

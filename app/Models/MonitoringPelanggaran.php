<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPelanggaran extends Model
{
    use HasFactory;
    protected $fillable = ['guru_bk_id', 'pelanggaran_id', 'siswa_id'];

    public function guruBk()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class, 'pelanggaran_id');
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

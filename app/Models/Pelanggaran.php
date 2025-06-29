<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;
    protected $fillable = ['nama_pelanggaran', 'tingkat_pelanggaran', 'poin_pelanggaran'];

    public function monitoringPelanggaran()
    {
        return $this->hasMany(MonitoringPelanggaran::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}

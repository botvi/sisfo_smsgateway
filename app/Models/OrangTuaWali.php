<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTuaWali extends Model
{
    use HasFactory;
    protected $table = 'orang_tua_walis';
    protected $fillable = ['nama_ortu', 'no_hp_ortu', 'tanggal_lahir_ortu', 'alamat_ortu'];

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'orang_tua_wali_id');
    }
}

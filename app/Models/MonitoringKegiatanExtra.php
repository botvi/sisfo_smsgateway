<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringKegiatanExtra extends Model
{
    use HasFactory;
    protected $fillable = ['ketua_extra_id', 'kegiatan'];

    public function ketuaExtra()
    {
        return $this->belongsTo(User::class, 'ketua_extra_id');
    }
}

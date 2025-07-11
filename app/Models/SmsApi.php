<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsApi extends Model
{
    use HasFactory;
    protected $table = 'sms_apis';
    protected $fillable = ['api_key'];
}

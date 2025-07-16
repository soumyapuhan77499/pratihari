<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappOtp extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_otps';

    protected $fillable = [
        'mobile',
        'otp',
        'is_verified',
    ];
    
}

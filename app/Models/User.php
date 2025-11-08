<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'pratihari_id',
        'name',
        'mobile_number',
        'otp',
        'otp_expires_at',
        'email',
        'order_id',
        'expiry',
        'hash',
        'client_id',
        'client_secret',
        'otp_length',
        'channel',
        'userphoto',
    ];

    protected $hidden = ['client_secret', 'hash'];

    protected $casts = [
        'expiry'         => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    /** Ensure we always store digits-only with country code (e.g., 91XXXXXXXXXX). */
 public function setMobileNumberAttribute($value)
{
    $digits = preg_replace('/\D+/', '', (string) $value);
    if ($digits === '') {
        $this->attributes['mobile_number'] = null;
        return;
    }
    if (strlen($digits) === 10) {
        $digits = '91'.$digits;
    }
    if (strlen($digits) === 11 && $digits[0] === '0') {
        $digits = '91'.substr($digits, 1);
    }
    $this->attributes['mobile_number'] = $digits;
}


    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'pratihari_id', 'pratihari_id');
    }
}

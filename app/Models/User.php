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
        'otp_expires_at',   // <- add this
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

    protected $hidden = [
        'client_secret',
        'hash',
    ];

    protected $casts = [
        'expiry'         => 'datetime',
        'otp_expires_at' => 'datetime', // <- add this
    ];

    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'pratihari_id', 'pratihari_id');
    }
}

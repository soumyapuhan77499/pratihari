<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    use Notifiable;
    
    protected $fillable = [
        'pratihari_id', 'name', 'mobile_number', 'email', 
        'order_id', 'expiry', 'hash', 'client_id', 'client_secret', 
        'otp_length', 'channel', 'userphoto',
    ];

    protected $hidden = [
        'client_secret', 'hash',
    ];

    protected $casts = [
        'expiry' => 'datetime',
    ];

    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'pratihari_id', 'pratihari_id');
    }
}

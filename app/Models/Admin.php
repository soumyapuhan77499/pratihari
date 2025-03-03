<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'admin_id', 'first_name', 'last_name', 'mobile_no', 'otp', 
        'order_id', 'expiry', 'hash', 'client_id', 'client_secret', 
        'otp_length', 'channel','photo', 'status'
    ];

    // Optionally, set the table name if different from "admins"
    protected $table = 'admins';
}

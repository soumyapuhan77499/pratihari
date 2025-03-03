<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use HasFactory;

    protected $table = 'super_admin';

    protected $fillable = [
        'super_admin_id',
        'name',
        'email',
        'mobile_no',
        'password',
    ];



}

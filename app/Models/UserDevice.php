<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    
    protected $fillable = ['pratihari_id', 'device_id', 'platform','device_model','version', 'last_login_time'];

    protected $casts = [
        'last_login_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pratihari_id', 'pratihari_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSahi extends Model
{
    use HasFactory;

    protected $table = 'master__sahi';

    protected $fillable = [
        'sahi_name',
        'status',
    ];

}

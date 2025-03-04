<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSebaMaster extends Model
{
    use HasFactory;

    protected $table = 'master__seba';

    protected $fillable = [
        'seba_name',        
    ];
}

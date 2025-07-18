<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSebaMapping extends Model
{
    use HasFactory;

    protected $table = 'pratihari__seba_mapping';

    protected $fillable = [
        'pratihari_id',
        'seba_1', 'seba_2', 'seba_3', 'seba_4', 'seba_5', 'seba_8'
    ];
}


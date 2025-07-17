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
        '1', '2', '3', '4', '5', '8'
    ];
}


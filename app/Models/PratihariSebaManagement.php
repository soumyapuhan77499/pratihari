<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSebaManagement extends Model
{
    use HasFactory;

    protected $table = 'pratihari__seba_management';

    protected $fillable = [
        'pratihari_id',
        'seba_id',
        'beddha_id',
        'date',
        'start_time',
        'end_time',
        'seba_status',
    ];

}

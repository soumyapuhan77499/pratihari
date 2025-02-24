<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariOccupation extends Model
{
    use HasFactory;

    protected $table = 'pratihari__occupation_details';

    protected $fillable = [
        'pratihari_id',
        'occupation_type',
        'extra_activity',
    ];

}

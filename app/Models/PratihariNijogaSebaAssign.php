<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariNijogaSebaAssign extends Model
{
    use HasFactory;

    protected $table = 'master__nijoga_seba_assign';

    protected $fillable = [
        'nijoga_id',
        'seba_id',
    ];
}

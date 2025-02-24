<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariNijogaMaster extends Model
{
    use HasFactory;

    protected $table = 'master__nijoga';

    protected $fillable = [
        'nijoga_name',       
    ];
}

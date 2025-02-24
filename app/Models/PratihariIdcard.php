<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariIdcard extends Model
{
    use HasFactory;

    protected $table = 'pratihari__idcard_details';

    protected $fillable = [
        'pratihari_id',
        'id_type',
         'id_number',
         'id_photo',
    ];
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariBeddhaMaster extends Model
{
    use HasFactory;

    protected $table = 'master__beddha';

    protected $fillable = [
        'beddha_name',       
    ];

}

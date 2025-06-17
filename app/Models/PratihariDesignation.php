<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariDesignation extends Model
{
    use HasFactory;

    protected $table = 'pratihari__designation';

    protected $fillable = [
        'pratihari_id',
        'year',
        'designation',
    ];

    public function pratihariProfile()
    {
        return $this->belongsTo(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

}

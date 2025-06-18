<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariApplication extends Model
{
    use HasFactory;

    protected $table = 'pratihari__application_details';

    protected $fillable = [
        'pratihari_id',
        'date',
        'header',
        'body',
        'photo'
    ];

    
    public function profile()
    {
        return $this->hasOne(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    
}

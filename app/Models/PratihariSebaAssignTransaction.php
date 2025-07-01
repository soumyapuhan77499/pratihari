<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSebaAssignTransaction extends Model
{
    use HasFactory;

    protected $table = 'pratihari__seba_assign_transaction';

    protected $fillable = [
        'pratihari_id',
        'assigned_by',
        'header',
        'body',
        'photo',
        'status',
    ];

    
    public function profile()
    {
        return $this->hasOne(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    
}

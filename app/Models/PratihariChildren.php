<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariChildren extends Model
{
    use HasFactory;

    protected $table = 'pratihari__children_details';

    protected $fillable = [
        'pratihari_id',
        'children_name',
        'date_of_birth',
        'gender',
        'photo',
        'marital_status',
        'spouse_name',
    ];

    public function family()
    {
        return $this->belongsTo(PratihariFamily::class, 'pratihari_id');
    }
}

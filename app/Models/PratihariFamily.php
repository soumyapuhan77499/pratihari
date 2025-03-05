<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariFamily extends Model
{
    use HasFactory;

    protected $table = 'pratihari__family_details';

    protected $fillable = [
        'pratihari_id',
        'father_name',
         'father_photo',
         'mother_name',
         'mother_photo',
         'maritial_status',
         'spouse_name',
         'spouse_photo',
         'spouse_father_name',
         'spouse_mother_name',
         'spouse_father_photo',
         'spouse_mother_photo',

    ];

    public function children()
    {
        return $this->hasMany(PratihariChildren::class, 'pratihari_id', 'pratihari_id');
    }
}

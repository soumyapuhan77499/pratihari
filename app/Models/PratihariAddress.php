<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariAddress extends Model
{
    use HasFactory;

    protected $table = 'pratihari__address_details';
    
    protected $fillable = [
        'pratihari_id',
        'address',
         'sahi',
         'landmark',
         'pincode',
         'post',
         'police_station',
         'district',
         'state',
         'country',
         'per_address',
         'per_sahi',
         'per_landmark',
         'per_pincode',
         'per_post',
         'per_police_station',
         'per_district',
         'per_state',
         'per_country'
    ];

    public function sahiDetail()
{
    return $this->belongsTo(PratihariSahi::class, 'sahi', 'id');
}


}


    

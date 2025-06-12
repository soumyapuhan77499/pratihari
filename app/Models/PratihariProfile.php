<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariProfile extends Model
{
    use HasFactory;

    protected $table = 'pratihari__profile_details';

    protected $fillable = [
         'pratihari_id',
         'nijoga_id',
         'first_name',
         'middle_name',
         'last_name',
         'alias_name',
         'email',
         'whatsapp_no',
         'phone_no',
         'alt_phone_no',
         'blood_group',
         'healthcard_no',
         'health_card_photo',
         'profile_photo',
         'joining_date',
         'joining_year',
         'date_of_birth',
         'pratihari_status',
         'reject_reason'
    ];

    public function occupation()
    {
        return $this->hasOne(PratihariOccupation::class, 'pratihari_id', 'pratihari_id');
    }

    public function address()
    {
        return $this->hasOne(PratihariAddress::class, 'pratihari_id', 'pratihari_id');
    }

    public function idcard()
    {
        return $this->hasOne(PratihariIdcard::class, 'pratihari_id', 'pratihari_id');
    }

    public function getCompletionPercentage()
    {
        $totalFields = 16; // Total number of columns in the table
        $filledFields = 0;
    
        foreach ($this->fillable as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }
    
        return ($filledFields / $totalFields) * 100;
    }
    
}

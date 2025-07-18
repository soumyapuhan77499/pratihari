<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSeba extends Model
{
    use HasFactory;
    
    protected $table = 'pratihari__seba_details';

    protected $fillable = [
        'pratihari_id',
        'seba_id',
        'beddha_id',
    ];

    public function beddhaAssigns()
    {
        return $this->hasMany(PratihariSebaBeddhaAssign::class, 'seba_id', 'seba_id');
    }
    
    public function sebaMaster()
    {
        return $this->belongsTo(PratihariSebaMaster::class, 'seba_id', 'id');
    }


    public function pratihari()
    {
        return $this->belongsTo(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    public function nijogaMaster()
    {
        return $this->belongsTo(PratihariNijogaMaster::class, 'nijoga_id', 'id');
    }

    public function beddhaMaster()
    {
        return $this->hasManyThrough(
            PratihariBeddhaMaster::class,
            PratihariSebaBeddhaAssign::class,
            'seba_id',    // Foreign key on PratihariSebaBeddhaAssign
            'id',         // Local key on PratihariBeddhaMaster
            'seba_id',    // Local key on PratihariSeba
            'beddha_id'   // Foreign key on PratihariSebaBeddhaAssign
        );
    }

    // Instead of hasManyThrough, define a method to fetch related beddhas
    public function beddhas()
    {
        // $this->beddha_id is now an array because of accessor
        return PratihariBeddhaMaster::whereIn('id', $this->beddha_id)->get();
    }

    // Accessor to handle comma-separated beddha_id values
    public function getBeddhaIdAttribute($value)
    {
        return explode(',', $value);
    }

    // Mutator to save beddha_id as comma-separated values
    public function setBeddhaIdAttribute($value)
    {
        $this->attributes['beddha_id'] = is_array($value) ? implode(',', $value) : $value;
    }

}

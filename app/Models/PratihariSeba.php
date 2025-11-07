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
        'status',
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

    // If you still need it:
    public function beddhaMaster()
    {
        return $this->hasManyThrough(
            PratihariBeddhaMaster::class,
            PratihariSebaBeddhaAssign::class,
            'seba_id',    // FK on assign table
            'id',         // PK on beddha master
            'seba_id',    // local key on this model
            'beddha_id'   // FK on assign table
        );
    }

    public function beddhas()
    {
        return PratihariBeddhaMaster::whereIn('id', $this->beddha_id)->get();
    }

    /** Accessor: make sure we always get an ARRAY OF INTS like [1,2,14] */
    public function getBeddhaIdAttribute($value)
    {
        if ($value === null || $value === '') return [];
        return collect(explode(',', $value))
            ->map(fn($v) => (int) trim($v))
            ->filter(fn($v) => $v > 0)
            ->values()
            ->all();
    }

    /** Mutator: accept array or CSV; store as CSV */
    public function setBeddhaIdAttribute($value)
    {
        if (is_array($value)) {
            $value = collect($value)
                ->map(fn($v) => (int) $v)
                ->filter(fn($v) => $v > 0)
                ->unique()
                ->implode(',');
        }
        $this->attributes['beddha_id'] = $value;
    }
}

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
        'seba_id',
        'beddha_id',
        'date_time',
        'status',
    ];

    // app/Models/PratihariSebaAssignTransaction.php

    public function pratihari()
    {
        return $this->belongsTo(\App\Models\PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    public function seba()
    {
        return $this->belongsTo(\App\Models\PratihariSebaMaster::class, 'seba_id', 'seba_id');
    }

}

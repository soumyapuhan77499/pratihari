<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSebaBeddhaAssign extends Model
{
    use HasFactory;

    protected $table = 'master__seba_beddha_assign';

    protected $fillable = [
        'seba_id',
        'beddha_id',
        'beddha_status',
    ];

    public function beddha()
    {
        return $this->belongsTo(PratihariBeddhaMaster::class, 'beddha_id');
    }
    
}

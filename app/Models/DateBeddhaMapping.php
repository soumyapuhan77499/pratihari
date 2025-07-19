<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateBeddhaMapping extends Model
{
    use HasFactory;

    protected $table = 'beddha__date_mapping';
    
    protected $fillable = [
        'date',
        'pratihari_beddha',
        'gochhikar_beddha'
    ];

    public function pratihariBeddha()
    {
        return $this->belongsTo(PratihariBeddhaMaster::class, 'pratihari_beddha', 'id');
    }

}

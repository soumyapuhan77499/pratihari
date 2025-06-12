<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_name', 'from_date', 'to_date', 'description', 'status'
    ];

    protected $table = 'pratihari__news_notice';

}

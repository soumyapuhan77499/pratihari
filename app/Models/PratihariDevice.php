<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariDevice extends Model
{
    use HasFactory;

    // If you create pratihari_devices table, you can OMIT this line.
    // protected $table = 'pratihari_devices';

    protected $fillable = [
        'pratihari_id',
        'device_id',
        'platform',
        'device_model',
        'version',
        'last_login_time',
    ];

    public function pratihari()
    {
        return $this->belongsTo(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    // Only authorized devices (blocked tokens kept in user_unauthorised_devices)
    public function scopeAuthorized($query)
    {
        return $query->whereNotIn('device_id', function ($q) {
            $q->select('device_id')->from('user_unauthorised_devices');
        });
    }

    public function scopePlatformIn($query, array $platforms)
    {
        return $query->when(!empty($platforms), fn ($q) => $q->whereIn('platform', $platforms));
    }
}

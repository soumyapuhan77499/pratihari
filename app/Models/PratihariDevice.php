<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariDevice extends Model
{
    use HasFactory;

    protected $table = 'pratihari_devices';


    protected $fillable = ['pratihari_id', 'device_id', 'platform', 'device_model', 'version', 'last_login_time'];

    public function pratihari()
    {
        // Your users table seems to use "userid" as the logical key
        return $this->belongsTo(PratihariProfile::class, 'pratihari_id', 'pratihari_id');
    }

    // Scope: only authorized devices
    public function scopeAuthorized($query)
    {
        return $query->whereNotIn('device_id', function ($q) {
            $q->select('device_id')->from('user_unauthorised_devices');
        });
    }

    // Scope: by platform(s)
    public function scopePlatformIn($query, array $platforms)
    {
        return $query->when(!empty($platforms), fn($q) => $q->whereIn('platform', $platforms));
    }
}

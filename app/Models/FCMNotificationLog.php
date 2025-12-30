<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FCMNotificationLog extends Model
{
    use HasFactory;

    protected $table = 'f_c_m_notification_logs';

    protected $fillable = [
        'fcm_notification_id',
        'pratihari_id',
        'device_token',
        'platform',
        'status',
        'error_code',
        'error_message',
    ];

    public function notification()
    {
        return $this->belongsTo(FCMNotification::class, 'fcm_notification_id');
    }
}

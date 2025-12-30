<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FCMNotification extends Model
{
    use HasFactory;

    protected $table = 'f_c_m_notifications';

    protected $fillable = [
        'notice_id',
        'title',
        'description',
        'image',
        'audience',     // 'all' | 'users' | 'platform' | etc
        'pratihari_id',
        'pratihari_ids',
        'platforms',    // ["android","ios","web"]
        'status',       // queued|sending|sent|partial|failed
        'success_count',
        'failure_count',
    ];

    protected $casts = [
        'pratihari_ids' => 'array',
        'platforms'     => 'array',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // If already absolute (e.g. CDN URL), return as-is
        if (preg_match('#^https?://#i', $this->image)) {
            return $this->image;
        }

        // Otherwise, build a public URL from storage ("public" disk)
        return Storage::disk('public')->url($this->image);
    }

    // Device-wise delivery logs
    public function logs()
    {
        return $this->hasMany(FCMNotificationLog::class, 'fcm_notification_id');
    }
}

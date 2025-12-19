<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PratihariNotice extends Model
{
    use HasFactory;

    protected $table = 'pratihari__news_notice';

    protected $fillable = [
        'notice_name', 'notice_photo', 'from_date', 'to_date', 'description', 'status'
    ];

    protected $appends = ['notice_photo_url'];

    public function getNoticePhotoUrlAttribute(): ?string
    {
        if (!$this->notice_photo) return null;

        // If you store "notices/xyz.jpg" already, just resolve it.
        $path = ltrim($this->notice_photo, '/');

        // If someone stored only filename, assume notices/
        if (!str_starts_with($path, 'notices/')) {
            $path = 'notices/' . $path;
        }

        // Return absolute URL if you want mobile apps to load it reliably
        $relative = Storage::disk('public')->url($path); // "/storage/notices/xyz.jpg"
        $baseUrl  = rtrim(env('APP_PHOTO_URL', config('app.url')), '/');

        return $baseUrl . $relative;
    }
}

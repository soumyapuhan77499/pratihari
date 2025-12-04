<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PratihariNotice extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_name', 'notice_photo', 'from_date', 'to_date', 'description', 'status'
    ];

    protected $table = 'pratihari__news_notice';

    // This will make "notice_photo_url" automatically appear in JSON
    protected $appends = ['notice_photo_url'];

    public function getNoticePhotoUrlAttribute()
    {
        if (!$this->notice_photo) {
            return null;
        }

        // Adjust the path as per your storage structure
        // e.g. if file is stored at storage/app/public/notice_photos/xyz.jpg
        return Storage::url('notice_photos/' . $this->notice_photo);
        // or, if you directly store full path in notice_photo, just:
        // return Storage::url($this->notice_photo);
    }
}

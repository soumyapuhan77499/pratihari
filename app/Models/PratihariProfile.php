<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class PratihariProfile extends Model
{
    use HasFactory;

    protected $table = 'pratihari__profile_details';

    public const CATEGORY_A = 'a';
    public const CATEGORY_B = 'b';
    public const CATEGORY_C = 'c';

    protected $fillable = [
        'pratihari_id','nijoga_id','first_name','middle_name','last_name','alias_name','bhagari','baristha_bhai_pua',
        'email','whatsapp_no','phone_no','alt_phone_no','blood_group','healthcard_no',
        'healthcard_photo','profile_photo','joining_date','joining_year','date_of_birth',
        'pratihari_status','reject_reason'
    ];

    protected $casts = [
        'bhagari'            => 'boolean',
        'baristha_bhai_pua'  => 'boolean',
    ];

    protected $appends = ['photo_url', 'full_name'];

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name])));
    }

    public function scopeApproved($q)
    {
        return $q->where('pratihari_status', 'approved');
    }

    public function scopeCategory($q, ?string $category)
    {
        if (!$category || $category === 'all') return $q;
        return $q->where('category', strtolower($category));
    }

    public function getPhotoUrlAttribute(): ?string
    {
        // Try common columns in order
        $candidates = [
            $this->profile_photo ?? null,
            $this->profile_photo_path ?? null,
            $this->avatar ?? null,
            $this->photo ?? null,
            $this->image ?? null,
        ];

        foreach ($candidates as $path) {
            if (!$path) continue;

            // Already absolute?
            if (preg_match('/^https?:\/\//i', $path)) {
                return $path;
            }

            // If someone saved "storage/xxx.jpg"
            if (str_starts_with($path, 'storage/')) {
                return asset($path);
            }

            // Public disk (storage/app/public/…)
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }

            // Direct public/ file
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }

    public function occupation(){ return $this->hasOne(PratihariOccupation::class, 'pratihari_id', 'pratihari_id'); }
    public function address(){ return $this->hasOne(PratihariAddress::class, 'pratihari_id', 'pratihari_id'); }
    public function family(){ return $this->hasOne(PratihariFamily::class, 'pratihari_id', 'pratihari_id'); }
    public function children(){ return $this->hasMany(PratihariChildren::class, 'pratihari_id', 'pratihari_id'); }
    public function idcard(){ return $this->hasMany(PratihariIdcard::class, 'pratihari_id', 'pratihari_id'); }
    public function seba(){ return $this->hasMany(PratihariSeba::class, 'pratihari_id', 'pratihari_id'); }
    public function socialMedia(){ return $this->hasOne(PratihariSocialMedia::class, 'pratihari_id', 'pratihari_id'); }

    public function getCompletionPercentage()
    {
        $totalFields = 16;
        $filled = 0;
        foreach ($this->fillable as $field) {
            if (!empty($this->$field)) $filled++;
        }
        return ($filled / $totalFields) * 100;
    }
}

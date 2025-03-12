<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PratihariSocialMedia extends Model
{
    use HasFactory;

    protected $table = 'pratihari__social_media';

    protected $fillable = [
        'pratihari_id', 
        'facebook_url',        
        'instagram_url',        
        'youtube_url',        
        'twitter_url',        
        'linkedin_url',        
    ];
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariSocialMedia;


class PratihariSocialMediaController extends Controller
{
    public function pratihariSocialMedia()
    {
        return view('admin.pratihari-social-media');
    }

    public function saveSocialMedia(Request $request)
    {
        $socialMedia = new PratihariSocialMedia();
        $socialMedia->pratihari_id = $request->pratihari_id;
        $socialMedia->facebook_url = $request->facebook;
        $socialMedia->instagram_url = $request->instagram;
        $socialMedia->youtube_url = $request->youtube;
        $socialMedia->twitter_url = $request->twitter;
        $socialMedia->linkedin_url = $request->linkedin;
        $socialMedia->save();

        return redirect()->route('admin.pratihariManageProfile')->with('success', 'Pratihari Social Media details saved successfully');
    }
}

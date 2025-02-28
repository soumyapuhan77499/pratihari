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

    public function edit($pratihari_id)
    {
        $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->first();
        return view('admin.update-social-media', compact('socialMedia', 'pratihari_id'));
    }

    public function update(Request $request, $pratihari_id)
    {
        try {
            // Find the existing record for this pratihari_id
            $socialMedia = PratihariSocialMedia::where('pratihari_id', $pratihari_id)->first();
    
            if ($socialMedia) {
                // If record exists, update it
                $socialMedia->update([
                    'facebook_url' => $request->facebook_url,
                    'twitter_url' => $request->twitter_url,
                    'instagram_url' => $request->instagram_url,
                    'linkedin_url' => $request->linkedin_url,
                    'youtube_url' => $request->youtube_url,
                ]);
            } else {
                // Optional - Handle if record does not exist
                return redirect()->back()->with('error', 'Social media record not found for this Pratihari.');

            }
    
            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihari_id])->with('success', 'Social media links updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihari_id])->with('error', 'Failed to update social media links. Error: ' . $e->getMessage());
        }
    }
    
}

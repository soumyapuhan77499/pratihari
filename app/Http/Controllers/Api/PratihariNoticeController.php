<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNotice;

class PratihariNoticeController extends Controller
{
    
public function getNotice(Request $request)
{
    try {
        $notices = PratihariNotice::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notice) {
                // Base URL from .env
                $baseUrl = rtrim(env('APP_PHOTO_URL', config('app.url')), '/');

                if ($notice->notice_photo) {
                    // Clean initial value
                    $photo = ltrim($notice->notice_photo, '/'); // remove leading "/"

                    // 1. Remove "notice_photos/" if present
                    if (str_starts_with($photo, 'notice_photos/')) {
                        $photo = substr($photo, strlen('notice_photos/'));
                    }

                    // 2. Ensure it starts with "notices/"
                    if (!str_starts_with($photo, 'notices/')) {
                        $photo = 'notices/' . $photo;
                    }

                    // 3. Final URL: https://domain.com/storage/notices/xxx.jpeg
                    $photoUrl = $baseUrl . '/storage/' . $photo;
                } else {
                    $photoUrl = '';
                }

                // Add URL field to response
                $notice->notice_photo_url = $photoUrl;

                return $notice;
            });

        return response()->json([
            'status'  => true,
            'message' => 'Notice fetched successfully',
            'data'    => $notices
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong',
            'error'   => $e->getMessage()
        ], 500);
    }
}

}

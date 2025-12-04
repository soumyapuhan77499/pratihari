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
                        // If value already like "notices/xxx.jpg"
                        if (str_starts_with($notice->notice_photo, 'notices/')) {
                            $path = 'storage/' . $notice->notice_photo;
                        } else {
                            // If only filename stored, put into notices folder
                            $path = 'storage/notices/' . ltrim($notice->notice_photo, '/');
                        }

                        $photoUrl = $baseUrl . '/' . $path;
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

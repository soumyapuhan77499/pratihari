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
                    $baseUrl = rtrim(env('APP_PHOTO_URL', config('app.url')), '/');
                    
                    $photoUrl = $notice->notice_photo
                        ? $baseUrl . '/storage/' . ltrim($notice->notice_photo, '/')
                        : '';

                    // Add new field to response
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

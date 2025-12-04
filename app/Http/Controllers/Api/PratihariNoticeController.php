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
            $notice = PratihariNotice::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Each item now has: notice_photo_url
            return response()->json([
                'status'  => true,
                'message' => 'Notice fetched successfully',
                'data'    => $notice
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

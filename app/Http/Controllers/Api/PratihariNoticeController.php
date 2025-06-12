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
    
            return response()->json([
                'status' => true,
                'message' => 'otice fetched successfully',
                'data' => $notice
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => true,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
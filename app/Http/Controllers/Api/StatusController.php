<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariProfile;
use App\Models\PratihariFamily;
use App\Models\PratihariIdcard;
use App\Models\PratihariAddress;
use App\Models\PratihariSeba;
use App\Models\PratihariSocialMedia;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function checkCompletionStatus()
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $pratihari_id = $user->pratihari_id;
    
        $tables = [
            'profile' => PratihariProfile::where('pratihari_id', $pratihari_id)->exists(),
            'family' => PratihariFamily::where('pratihari_id', $pratihari_id)->exists(),
            'id_card' => PratihariIdcard::where('pratihari_id', $pratihari_id)->exists(),
            'address' => PratihariAddress::where('pratihari_id', $pratihari_id)->exists(),
            'seba' => PratihariSeba::where('pratihari_id', $pratihari_id)->exists(),
            'social_media' => PratihariSocialMedia::where('pratihari_id', $pratihari_id)->exists(),
        ];
    
        $filledTables = array_keys(array_filter($tables));
        $emptyTables = array_keys(array_filter($tables, fn($filled) => !$filled));
    
        return response()->json([
            'pratihari_id' => $pratihari_id,
            'filled_tables' => $filledTables,
            'empty_tables' => $emptyTables,
        ], 200);
    }
}

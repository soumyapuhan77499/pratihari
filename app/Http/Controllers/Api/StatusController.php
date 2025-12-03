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
        // 1. Get the currently authenticated user
        $user = Auth::user();

        // 2. If no user, return 401
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // 3. Use the pratihari_id from the logged-in user
        $pratihari_id = $user->pratihari_id;

        // 4. Check which related records exist for this pratihari_id
        $tables = [
            'profile'       => PratihariProfile::where('pratihari_id', $pratihari_id)->exists(),
            'family'        => PratihariFamily::where('pratihari_id', $pratihari_id)->exists(),
            'id_card'       => PratihariIdcard::where('pratihari_id', $pratihari_id)->exists(),
            'address'       => PratihariAddress::where('pratihari_id', $pratihari_id)->exists(),
            'seba'          => PratihariSeba::where('pratihari_id', $pratihari_id)->exists(),
            'social_media'  => PratihariSocialMedia::where('pratihari_id', $pratihari_id)->exists(),
        ];

        // 5. Tables that are FILLED (true in $tables)
        $filledTables = array_keys(
            array_filter($tables, fn ($exists) => $exists)
        );

        // 6. Tables that are EMPTY (false in $tables)
        $emptyTables = array_keys(
            array_filter($tables, fn ($exists) => !$exists)
        );

        // 7. Return a clear JSON response
        return response()->json([
            'pratihari_id'   => $pratihari_id,
            'tables'         => $tables,        // raw true/false per table
            'filled_tables'  => $filledTables,  // names of tables with data
            'empty_tables'   => $emptyTables,   // names of tables without data
        ], 200);
    }
}

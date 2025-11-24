<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PratihariAddress;
use App\Models\PratihariSahi;

class PratihariAddressApiController extends Controller
{
public function saveAddress(Request $request)
{
    try {
        $user = Auth::user();
        if (!$user || !$user->pratihari_id) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $pratihariId = $user->pratihari_id;

        // Find or create address row for this pratihari
        $address = PratihariAddress::where('pratihari_id', $pratihariId)->first();
        if (!$address) {
            $address = new PratihariAddress();
            $address->pratihari_id = $pratihariId;
        }

        // -------- Present address fields --------
        $address->sahi           = $request->sahi;
        $address->landmark       = $request->landmark;
        $address->post           = $request->post;
        $address->police_station = $request->police_station;
        $address->pincode        = $request->pincode;
        $address->district       = $request->district;
        $address->state          = $request->state;
        $address->country        = $request->country;
        $address->address        = $request->address;

        // Checkbox: "permanent address same as present?"
        $sameAddress = $request->boolean('same_as_permanent_address');
        $address->same_as_permanent_address = $sameAddress ? 1 : 0;

        if ($sameAddress) {
            // ✅ Checkbox TRUE: permanent address = same as present
            $address->per_address        = $request->address;
            $address->per_sahi           = $request->sahi;
            $address->per_landmark       = $request->landmark;
            $address->per_post           = $request->post;
            $address->per_police_station = $request->police_station;
            $address->per_pincode        = $request->pincode;
            $address->per_district       = $request->district;
            $address->per_state          = $request->state;
            $address->per_country        = $request->country;
        } else {
            // ✅ Checkbox FALSE: use user-input permanent address fields
            // (if user leaves them empty they’ll be null/blank, but we don’t force copy or clear)
            $address->per_address        = $request->per_address;
            $address->per_sahi           = $request->per_sahi;
            $address->per_landmark       = $request->per_landmark;
            $address->per_post           = $request->per_post;
            $address->per_police_station = $request->per_police_station;
            $address->per_pincode        = $request->per_pincode;
            $address->per_district       = $request->per_district;
            $address->per_state          = $request->per_state;
            $address->per_country        = $request->per_country;
        }

        $address->save();

        return response()->json([
            'status'  => true,
            'message' => 'Address saved successfully.',
            'data'    => $address,
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Error saving address: ' . $e->getMessage());

        return response()->json([
            'status'  => false,
            'message' => 'Failed to save address.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}

    public function getSahi()
    {
        try {
            // Get applications
            $pratihari_sahi = PratihariSahi::where('status', 'active')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Sahi fetched successfully.',
                'data' => $pratihari_sahi
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching applications: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch applications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
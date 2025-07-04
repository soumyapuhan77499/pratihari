<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariAddress;
use App\Models\PratihariSahi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PratihariAddressController extends Controller
{
    public function pratihariAddress()
    {
        $sahiList = PratihariSahi::where('status', 'active')->get();
        return view('admin.pratihari-address-details', compact('sahiList'));
    }

    public function addSahi()
    {
        return view('admin.master-sahi-details');
    }

    public function manageSahi(){
       $sahis = PratihariSahi::where('status', 'active')->get();
        return view('admin.manage-sahi-details', compact('sahis'));
    }

    public function saveSahi(Request $request)
    {

        try {
            PratihariSahi::create([
                'sahi_name' => $request->sahi_name,
                'status' => 'active', 
            ]);

            return redirect()->back()->with('success', 'Sahi added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to save sahi details.');

        }
    }
   
    public function saveAddress(Request $request)
    {
        try {
            // Create a new address entry
            $address = new PratihariAddress();
            $address->pratihari_id = $request->pratihari_id;
            $address->sahi = $request->sahi;
            $address->landmark = $request->landmark;
            $address->post = $request->post;
            $address->police_station = $request->police_station;
            $address->pincode = $request->pincode;
            $address->district = $request->district;
            $address->state = $request->state;
            $address->country = $request->country;
            $address->address = $request->address;

            // Check if the checkbox is unchecked, meaning both addresses should be the same
            if (!$request->has('differentAsPermanent')) {
                $address->per_address = $request->address;
                $address->per_sahi = $request->sahi;
                $address->per_landmark = $request->landmark;
                $address->per_post = $request->post;
                $address->per_police_station = $request->police_station;
                $address->per_pincode = $request->pincode;
                $address->per_district = $request->district;
                $address->per_state = $request->state;
                $address->per_country = $request->country;
            } else {
                // If checkbox is checked, save entered permanent address
                $address->per_address = $request->per_address;
                $address->per_sahi = $request->per_sahi;
                $address->per_landmark = $request->per_landmark;
                $address->per_post = $request->per_post;
                $address->per_police_station = $request->per_police_station;
                $address->per_pincode = $request->per_pincode;
                $address->per_district = $request->per_district;
                $address->per_state = $request->per_state;
                $address->per_country = $request->per_country;
            }

            $address->save();

            return redirect()->route('admin.pratihariOccupation', ['pratihari_id' => $address->pratihari_id])->with('success', 'Address saved successfully');

        } catch (\Exception $e) {

            Log::error('Error saving address: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to save address. Please try again.');
        }
    }

public function update(Request $request, $id)
{
    $request->validate([
        'sahi_name' => 'required|string|max:255',
    ]);

    $sahi = PratihariSahi::find($id);

    if (!$sahi) {
        return response()->json(['success' => false, 'message' => 'Sahi not found!'], 404);
    }

    $sahi->sahi_name = $request->sahi_name;
    $sahi->save();

    return response()->json(['success' => true, 'message' => 'Sahi updated successfully!']);
}

public function delete($id)
{
    $sahi = PratihariSahi::find($id);

    if (!$sahi) {
        return response()->json(['success' => false, 'message' => 'Sahi not found!'], 404);
    }

    $sahi->status = 'deleted'; // Soft delete by updating status
    $sahi->save();

    return response()->json(['success' => true, 'message' => 'Sahi marked as deleted!']);
}

public function updateAddress(Request $request)
{
    try {
        // Find existing address for the pratihari_id, if exists
        $address = PratihariAddress::where('pratihari_id', $request->pratihari_id)->first();

        if (!$address) {
            $address = new PratihariAddress(); // If no record exists, create new
            $address->pratihari_id = $request->pratihari_id;
        }

        // Common fields - present & permanent both
        $address->sahi = $request->sahi;
        $address->landmark = $request->landmark;
        $address->post = $request->post;
        $address->police_station = $request->police_station;
        $address->pincode = $request->pincode;
        $address->district = $request->district;
        $address->state = $request->state;
        $address->country = $request->country;
        $address->address = $request->address;

        // Handle permanent address fields
        if (!$request->has('differentAsPermanent')) {
            // Same as present address
            $address->per_address = $request->address;
            $address->per_sahi = $request->sahi;
            $address->per_landmark = $request->landmark;
            $address->per_post = $request->post;
            $address->per_police_station = $request->police_station;
            $address->per_pincode = $request->pincode;
            $address->per_district = $request->district;
            $address->per_state = $request->state;
            $address->per_country = $request->country;
        } else {
            // Use separate permanent address
            $address->per_address = $request->per_address;
            $address->per_sahi = $request->per_sahi;
            $address->per_landmark = $request->per_landmark;
            $address->per_post = $request->per_post;
            $address->per_police_station = $request->per_police_station;
            $address->per_pincode = $request->per_pincode;
            $address->per_district = $request->per_district;
            $address->per_state = $request->per_state;
            $address->per_country = $request->per_country;
        }

        $address->save();

        return redirect()->route('admin.viewProfile', ['pratihari_id' => $address->pratihari_id])->with('success', 'Address saved successfully');
       
    } catch (\Exception $e) {
        Log::error('Error saving/updating address: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to save address. Please try again.');
    }
}
public function edit($pratihari_id)
{
    $pratihariAddress = PratihariAddress::where('pratihari_id', $pratihari_id)->first();

    // Prevent null by using an empty model if not found
    if (!$pratihariAddress) {
        $pratihariAddress = new \App\Models\PratihariAddress();
        $pratihariAddress->pratihari_id = $pratihari_id; // assign the ID manually
    }

    $sahiList = PratihariSahi::where('status', 'active')->get();

    return view('admin.update-address-details', compact('pratihariAddress', 'sahiList'));
}

}

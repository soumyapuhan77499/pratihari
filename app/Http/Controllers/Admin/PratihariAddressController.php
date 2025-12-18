<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariAddress;
use App\Models\PratihariSahi;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

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

    public function manageSahi()
    {
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
            DB::beginTransaction();

            // Backend Validation (Sahi required + conditional permanent required)
            $request->validate([
                'pratihari_id' => ['required', 'integer'],

                // Current address (make sahi required as you asked)
                'sahi'           => ['required', 'string', 'max:255'],
                'landmark'       => ['nullable', 'string', 'max:255'],
                'post'           => ['nullable', 'string', 'max:255'],
                'police_station' => ['nullable', 'string', 'max:255'],
                'pincode'        => ['nullable', 'digits:6'],
                'district'       => ['nullable', 'string', 'max:255'],
                'state'          => ['nullable', 'string', 'max:255'],
                'country'        => ['nullable', 'string', 'max:255'],
                'address'        => ['nullable', 'string'],

                // Permanent address required ONLY when checkbox is checked (value "on")
                'per_sahi'           => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_landmark'       => ['nullable', 'string', 'max:255'],
                'per_post'           => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_police_station' => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_pincode'        => ['required_if:same_as_permanent_address,on', 'digits:6'],
                'per_district'       => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_state'          => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_country'        => ['required_if:same_as_permanent_address,on', 'string', 'max:255'],
                'per_address'        => ['required_if:same_as_permanent_address,on', 'string'],
            ], [
                'sahi.required' => 'Current address Sahi is required.',
                'per_sahi.required_if' => 'Permanent Sahi is required when permanent address is different.',
                'per_pincode.required_if' => 'Permanent Pincode is required when permanent address is different.',
                'per_pincode.digits' => 'Permanent Pincode must be exactly 6 digits.',
                'pincode.digits' => 'Pincode must be exactly 6 digits.',
            ]);

            $address = new PratihariAddress();
            $address->pratihari_id    = $request->pratihari_id;

            // Current Address
            $address->sahi            = $request->sahi;
            $address->landmark        = $request->landmark;
            $address->post            = $request->post;
            $address->police_station  = $request->police_station;
            $address->pincode         = $request->pincode;
            $address->district        = $request->district;
            $address->state           = $request->state;
            $address->country         = $request->country;
            $address->address         = $request->address;

            // Checkbox checked = permanent is different -> isSame = false
            $isSame = !$request->has('same_as_permanent_address');
            $address->same_as_permanent_address = $isSame;

            if ($isSame) {
                // Copy current to permanent
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
                // Separate permanent
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

            DB::commit();

            return redirect()
                ->route('admin.pratihariOccupation', ['pratihari_id' => $address->pratihari_id])
                ->with('success', 'Address saved successfully');
        }

        catch (ValidationException $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        catch (QueryException $e) {
            DB::rollBack();

            $userMessage = 'Something went wrong while saving the address. Please try again.';

            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                $raw = $e->errorInfo[2] ?? $e->getMessage();

                if (Str::contains($raw, 'pratihari_id')) {
                    $userMessage = 'Address for this member already exists.';
                } else {
                    $userMessage = 'Duplicate entry detected. Please check the address details and try again.';
                }
            }

            Log::error('DB error saving address: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $userMessage);
        }

        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error saving address: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to save address. Please try again.');
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
                $address = new PratihariAddress();
                $address->pratihari_id = $request->pratihari_id;
            }

            // Assign present address fields
            $address->sahi = $request->sahi;
            $address->landmark = $request->landmark;
            $address->post = $request->post;
            $address->police_station = $request->police_station;
            $address->pincode = $request->pincode;
            $address->district = $request->district;
            $address->state = $request->state;
            $address->country = $request->country;
            $address->address = $request->address;

            // Determine if the present and permanent addresses are the same
            $isSame = !$request->has('same_as_permanent_address'); // true if unchecked
            $address->same_as_permanent_address = $isSame;

            if ($isSame) {
                // Copy present address to permanent
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
                // Assign permanent address separately
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

            return redirect()->route('admin.viewProfile', ['pratihari_id' => $address->pratihari_id])
                            ->with('success', 'Address updated successfully');

        } catch (\Exception $e) {
            Log::error('Error saving/updating address: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to update address. Please try again.');
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

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;
use Illuminate\Support\Facades\Auth;    


class PratihariFamilyApiController extends Controller
{
    public function saveFamily(Request $request)
    {
        try {
            $user = Auth::user();
            
            $pratihariId = $user->pratihari_id;

            if (!$pratihariId) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized. Please log in.',
                ], 401);
            }
            // Save Family Data
            $family = new PratihariFamily();
            $family->pratihari_id = $pratihariId;
            $family->father_name = $request->father_name;
            $family->mother_name = $request->mother_name;
            $family->maritial_status = $request->marital_status;
            $family->spouse_name = $request->spouse_name;

            // Handle File Uploads
            if ($request->hasFile('father_photo')) {
                $family->father_photo = $request->file('father_photo')->store('uploads/family', 'public');
            }

            if ($request->hasFile('mother_photo')) {
                $family->mother_photo = $request->file('mother_photo')->store('uploads/family', 'public');
            }

            if ($request->hasFile('spouse_photo')) {
                $family->spouse_photo = $request->file('spouse_photo')->store('uploads/family', 'public');
            }

            $family->save();

            // Save Children Data
            $childrenData = [];
            if ($request->has('children')) {
                foreach ($request->children as $child) {
                    $childData = new PratihariChildren();
                    $childData->pratihari_id = $pratihariId;
                    $childData->children_name = $child['name'];
                    $childData->date_of_birth = $child['dob'];
                    $childData->gender = $child['gender'];

                    if (isset($child['photo']) && $child['photo']->isValid()) {
                        $childData->photo = $child['photo']->store('uploads/children', 'public');
                    }

                    $childData->save();
                    $childrenData[] = $childData;
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Family data saved successfully.',
                'data' => [
                    'family' => $family,
                    'children' => $childrenData
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

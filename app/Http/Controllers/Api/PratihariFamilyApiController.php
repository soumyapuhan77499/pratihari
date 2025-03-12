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
    
            // Handle File Uploads for Parents & Spouse
            if ($request->hasFile('father_photo')) {
                $fatherPhoto = $request->file('father_photo');
                $fatherPhotoName = time() . '_father.' . $fatherPhoto->getClientOriginalExtension();
                $fatherPhoto->move(public_path('uploads/family'), $fatherPhotoName);
                $family->father_photo = 'uploads/family/' . $fatherPhotoName;
            }
    
            if ($request->hasFile('mother_photo')) {
                $motherPhoto = $request->file('mother_photo');
                $motherPhotoName = time() . '_mother.' . $motherPhoto->getClientOriginalExtension();
                $motherPhoto->move(public_path('uploads/family'), $motherPhotoName);
                $family->mother_photo = 'uploads/family/' . $motherPhotoName;
            }
    
            if ($request->hasFile('spouse_photo')) {
                $spousePhoto = $request->file('spouse_photo');
                $spousePhotoName = time() . '_spouse.' . $spousePhoto->getClientOriginalExtension();
                $spousePhoto->move(public_path('uploads/family'), $spousePhotoName);
                $family->spouse_photo = 'uploads/family/' . $spousePhotoName;
            }
    
            $family->save();
    
            // Save Children Data
            $childrenData = [];
            if ($request->has('children_name')) {
                foreach ($request->children_name as $index => $name) {
                    if ($name) {  // Ensure name is provided
                        $childData = new PratihariChildren();
                        $childData->pratihari_id = $pratihariId;
                        $childData->children_name = $name;
                        $childData->date_of_birth = $request->children_dob[$index] ?? null;
                        $childData->gender = $request->children_gender[$index] ?? null;
    
                        // Handle File Uploads for Children
                        if (isset($child['photo']) && $request->hasFile("children.{$child['id']}.photo")) {
                            $childPhoto = $request->file("children.{$child['id']}.photo");
                            $childPhotoName = time() . '_child.' . $childPhoto->getClientOriginalExtension();
                            $childPhoto->move(public_path('uploads/children'), $childPhotoName);
                            $childData->photo = 'uploads/children/' . $childPhotoName;
                        }
    
                        $childData->save();
                        $childrenData[] = $childData;
                    }
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

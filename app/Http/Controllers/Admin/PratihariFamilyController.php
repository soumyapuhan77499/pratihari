<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariFamily;
use App\Models\PratihariChildren;


class PratihariFamilyController extends Controller
{
    public function pratihariFamily()
    {
        return view('admin.pratihari-family-details');
    }
    
    public function saveFamily(Request $request)
    {
        try {

            $pratihariId = $request->input('pratihari_id');

            if (!$pratihariId) {
                throw new \Exception('Pratihari ID is missing.');
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
                $fatherPhotoPath = $request->file('father_photo')->store('uploads/family', 'public');
                $family->father_photo = $fatherPhotoPath;
            }

            if ($request->hasFile('mother_photo')) {
                $motherPhotoPath = $request->file('mother_photo')->store('uploads/family', 'public');
                $family->mother_photo = $motherPhotoPath;
            }

            if ($request->hasFile('spouse_photo')) {
                $spousePhotoPath = $request->file('spouse_photo')->store('uploads/family', 'public');
                $family->spouse_photo = $spousePhotoPath;
            }

            $family->save();

            // Save Children Data
            if ($request->has('children')) {
                foreach ($request->children as $child) {
                    $childData = new PratihariChildren();
                    $childData->pratihari_id = $pratihariId;
                    $childData->children_name = $child['name'];
                    $childData->date_of_birth = $child['dob'];
                    $childData->gender = $child['gender'];

                    if (isset($child['photo'])) {
                        $childPhotoPath = $child['photo']->store('uploads/children', 'public');
                        $childData->photo = $childPhotoPath;
                    }

                    $childData->save();
                }
            }
            return redirect()->route('admin.pratihariIdcard', ['pratihari_id' => $family->pratihari_id])->with('success', 'Family data saved successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    
}

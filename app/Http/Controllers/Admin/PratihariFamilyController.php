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

    $familyDetails = PratihariFamily::where('status', 'active')->get();

    return view('admin.pratihari-family-details', compact('familyDetails'));

}

public function saveFamily(Request $request)
{
    try {

        $pratihariId = $request->input('pratihari_id');

        // Save Family Data
        $family = new PratihariFamily();
        $family->pratihari_id = $pratihariId;
        $family->father_name = $request->father_name;
        $family->mother_name = $request->mother_name;
        // Father Name Handling
        if ($request->father_id === 'other') {
        $family->father_name = $request->father_name;

        if ($request->hasFile('father_photo')) {
            $fatherPhoto = $request->file('father_photo');
            $fatherPhotoName = time() . '_father.' . $fatherPhoto->getClientOriginalExtension();
            $fatherPhoto->move(public_path('uploads/family'), $fatherPhotoName);
            $family->father_photo = asset('uploads/family/' . $fatherPhotoName); // Save full file path
        }

    } else {
        $selectedFather = PratihariFamily::find($request->father_id);
        $family->father_name = $selectedFather ? $selectedFather->father_name : null;
        $family->father_photo = $selectedFather ? $selectedFather->father_photo : null;
    }

        // Mother Name Handling
        if ($request->mother_id === 'other') {
            $family->mother_name = $request->mother_name;

            if ($request->hasFile('mother_photo')) {
                $motherPhoto = $request->file('mother_photo');
                $motherPhotoName = time() . '_mother.' . $motherPhoto->getClientOriginalExtension();
                $motherPhoto->move(public_path('uploads/family'), $motherPhotoName);
                $family->mother_photo = asset('uploads/family/' . $motherPhotoName); // Save full file path
            }
        } else {
            $selectedMother = PratihariFamily::find($request->mother_id);
            $family->mother_name = $selectedMother ? $selectedMother->mother_name : null;
            $family->mother_photo = $selectedMother ? $selectedMother->mother_photo : null;
        }

        $family->maritial_status = $request->marital_status;
        $family->spouse_name = $request->spouse_name;
        $family->spouse_father_name = $request->spouse_father_name;
        $family->spouse_mother_name = $request->spouse_mother_name;

        if ($request->hasFile('spouse_photo')) {
            $spousePhoto = $request->file('spouse_photo');
            $spousePhotoName = time() . '_spouse.' . $spousePhoto->getClientOriginalExtension();
            $spousePhoto->move(public_path('uploads/family'), $spousePhotoName);
            $family->spouse_photo = asset('uploads/family/' . $spousePhotoName); // Save full file path
        }

        if ($request->hasFile('spouse_father_photo')) {
            $spouseFatherPhoto = $request->file('spouse_father_photo');
            $spouseFatherPhotoName = time() . '_spouse.' . $spouseFatherPhoto->getClientOriginalExtension();
            $spouseFatherPhoto->move(public_path('uploads/family'), $spouseFatherPhotoName);
            $family->spouse_father_photo = asset('uploads/family/' . $spouseFatherPhotoName); // Save full file path
        }

        if ($request->hasFile('spouse_mother_photo')) {
            $spouseMotherPhoto = $request->file('spouse_mother_photo');
            $spouseMotherPhotoName = time() . '_spouse.' . $spouseMotherPhoto->getClientOriginalExtension();
            $spouseMotherPhoto->move(public_path('uploads/family'), $spouseMotherPhotoName);
            $family->spouse_mother_photo = asset('uploads/family/' . $spouseMotherPhotoName); // Save full file path
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
                    $childPhoto = $child['photo'];
                    $childPhotoName = time() . '_child.' . $childPhoto->getClientOriginalExtension();
                    $childPhoto->move(public_path('uploads/children'), $childPhotoName);
                    $childData->photo = asset('uploads/children/' . $childPhotoName); // Save full file path
                }
                
                $childData->save();
            }
        }
        return redirect()->route('admin.pratihariIdcard', ['pratihari_id' => $family->pratihari_id])->with('success', 'Family data saved successfully');

    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function edit($pratihari_id)
{
    $family = PratihariFamily::with('children')->where('pratihari_id', $pratihari_id)->first();
    return view('admin.update-family-details', compact('family'));
}

public function updateFamily(Request $request, $pratihariId)
{
    try {
        $family = PratihariFamily::where('pratihari_id', $pratihariId)->first();

        if (!$family) {
            throw new \Exception('Family record not found.');
        }

        // Update Family Data
        $family->father_name = $request->father_name;
        $family->mother_name = $request->mother_name;
        $family->maritial_status = $request->marital_status;
        $family->spouse_name = $request->spouse_name;

        // Handle File Uploads
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

        // Update Children Data
        if ($request->has('children')) {
            foreach ($request->children as $child) {
                $childData = PratihariChildren::where('id', $child['id'])->where('pratihari_id', $pratihariId)->first();
                
                if (!$childData) {
                    $childData = new PratihariChildren();
                    $childData->pratihari_id = $pratihariId;
                }

                $childData->children_name = $child['name'];
                $childData->date_of_birth = $child['dob'];
                $childData->gender = $child['gender'];

                if (isset($child['photo']) && $request->hasFile("children.{$child['id']}.photo")) {
                    $childPhoto = $request->file("children.{$child['id']}.photo");
                    $childPhotoName = time() . '_child.' . $childPhoto->getClientOriginalExtension();
                    $childPhoto->move(public_path('uploads/children'), $childPhotoName);
                    $childData->photo = 'uploads/children/' . $childPhotoName;
                }

                $childData->save();
            }
        }
        
        return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihariId])->with('success', 'Family data updated successfully');


    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }

}

}

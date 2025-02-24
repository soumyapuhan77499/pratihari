<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariIdcard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PratihariIdcardController extends Controller
{
    public function pratihariIdcard()
    {
        return view('admin.pratihari-idcard-details');
    }


public function saveIdcard(Request $request)
{
    try {
        // Validate the incoming data
        $request->validate([
            'id_type' => 'required|array',
            'id_number' => 'required|array',
            'id_photo' => 'required|array',
            'id_photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validating image files
        ]);

        // Loop through each ID card entry and save them
        foreach ($request->id_type as $key => $type) {
            $idCard = new PratihariIdcard();
            $idCard->pratihari_id = $request->pratihari_id;
            $idCard->id_type = $request->id_type[$key];
            $idCard->id_number = $request->id_number[$key];

            // Handle file upload
            if ($request->hasFile('id_photo') && $request->file('id_photo')[$key]->isValid()) {
                $imagePath = $request->file('id_photo')[$key]->store('uploads/id_photo', 'public');

                $idCard->id_photo = $imagePath; // Store path to the image in the database
            }

            $idCard->save();
        }
        return redirect()->route('admin.pratihariAddress', ['pratihari_id' => $idCard->pratihari_id])->with('success', 'ID cards added successfully');

    } catch (ValidationException $e) {
        // Catch validation exceptions and handle the error messages
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // Catch any other exceptions and handle the error
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

}

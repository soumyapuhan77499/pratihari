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
            // Loop through each ID card entry and save them
            foreach ($request->id_type as $key => $type) {
                $idCard = new PratihariIdcard();
                $idCard->pratihari_id = $request->pratihari_id;
                $idCard->id_type = $request->id_type[$key];

                // Handle file upload
                if ($request->hasFile('id_photo') && isset($request->file('id_photo')[$key])) {
                    $idPhoto = $request->file('id_photo')[$key];
                
                    if ($idPhoto->isValid()) {
                        $imageName = time() . '_id.' . $idPhoto->getClientOriginalExtension();
                        $idPhoto->move(public_path('uploads/id_photo'), $imageName);
                        $idCard->id_photo = asset('uploads/id_photo/' . $imageName); // Save full file path
                    }
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

    public function edit($pratihariId)
    {
        $idCards = PratihariIdcard::where('pratihari_id', $pratihariId)->get();
        
        return view('admin.update-idcard-details', compact('idCards', 'pratihariId'));
    }

    public function update(Request $request, $pratihariId)
    {
        try {
            // Get all existing records for this Pratihari
            $existingRecords = PratihariIdcard::where('pratihari_id', $pratihariId)->get();

            if ($existingRecords->isEmpty()) {
                return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihariId])
                    ->with('error', 'No ID cards found for this Pratihari.');
            }

            foreach ($existingRecords as $index => $record) {
                if (isset($request->id_type[$index]) && isset($request->id_number[$index])) {
                    $record->id_type = $request->id_type[$index];
                    $record->id_number = $request->id_number[$index];

                    // Handle file upload
                    if ($request->hasFile("id_photo.$index")) {
                        $idPhoto = $request->file("id_photo.$index");

                        if ($idPhoto->isValid()) {
                            // Delete the old image if it exists
                            if (!empty($record->id_photo)) {
                                Storage::delete('public/uploads/id_photo/' . basename($record->id_photo));
                            }

                            // Save the new image
                            $imageName = time() . "_id_" . $index . "." . $idPhoto->getClientOriginalExtension();
                            $idPhoto->move(public_path('uploads/id_photo'), $imageName);
                            $record->id_photo = asset('uploads/id_photo/' . $imageName);
                        }
                    }

                    $record->save();
                }
            }

            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihariId])->with('success', 'ID cards updated successfully.');

        } catch (ValidationException $e) {

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());

        }
    }

}

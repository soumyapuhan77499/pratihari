<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariIdcard;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class PratihariIdcardController extends Controller
{
    public function pratihariIdcard()
    {
        return view('admin.pratihari-idcard-details');
    }
public function saveIdcard(Request $request)
{
    try {
        // Start transaction so all ID cards save atomically
        \DB::beginTransaction();

        // Optional: basic safety check
        if (!is_array($request->id_type) || empty($request->id_type)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Please add at least one ID card.');
        }

        // Loop through each ID card entry and save them
        foreach ($request->id_type as $key => $type) {
            $idCard = new PratihariIdcard();
            $idCard->pratihari_id = $request->pratihari_id;
            $idCard->id_type      = $type;

            // Handle file upload
            if ($request->hasFile('id_photo') && isset($request->file('id_photo')[$key])) {
                $idPhoto = $request->file('id_photo')[$key];

                if ($idPhoto && $idPhoto->isValid()) {
                    $imageName = time() . '_id.' . $idPhoto->getClientOriginalExtension();
                    $idPhoto->move(public_path('uploads/id_photo'), $imageName);
                    // Store full path (as you were doing)
                    $idCard->id_photo = asset('uploads/id_photo/' . $imageName);
                }
            }

            $idCard->save();
        }

        \DB::commit();

        return redirect()
            ->route('admin.pratihariAddress', ['pratihari_id' => $idCard->pratihari_id])
            ->with('success', 'ID cards added successfully');
    }

    // Validation errors (if you ever throw/trigger them for this form)
    catch (\Illuminate\Validation\ValidationException $e) {
        \DB::rollBack();

        return redirect()
            ->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    // Database / query errors (e.g. duplicate entry)
    catch (\Illuminate\Database\QueryException $e) {
        \DB::rollBack();

        // Default friendly message
        $userMessage = 'Something went wrong while saving ID cards. Please try again.';

        // MySQL duplicate entry error code = 1062
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
            $raw = $e->errorInfo[2] ?? $e->getMessage();

            // Adjust these checks to match your unique indexes
            if (\Illuminate\Support\Str::contains($raw, 'pratihari_id')) {
                $userMessage = 'ID card details for this member already exist.';
            } elseif (\Illuminate\Support\Str::contains($raw, 'id_type')) {
                $userMessage = 'This ID type is already added for this member.';
            } else {
                $userMessage = 'Duplicate entry detected. Please check your ID card details and try again.';
            }
        }

        // Log full error for debugging (not shown to user)
        \Log::error('DB error in saveIdcard: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $userMessage);
    }

    // Any other generic errors
    catch (\Exception $e) {
        \DB::rollBack();

        \Log::error('Error in saveIdcard: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Something went wrong while saving ID cards. Please try again.');
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

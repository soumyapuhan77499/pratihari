<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariOccupation;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PratihariOccupationController extends Controller
{
    public function pratihariOccupation()
    {
        return view('admin.pratihari-occupation-details');
    }
public function saveOccupation(Request $request)
{
    try {
        DB::beginTransaction();

        // (Optional) basic validation – extend as needed
        $request->validate([
            'pratihari_id' => 'required',
            // 'occupation' => 'required|string',
        ]);

        // Convert the extra_activity array into a comma-separated string
        $extraActivities = $request->extra_activity
            ? implode(',', $request->extra_activity)
            : null;

        $pratihari_id = $request->pratihari_id;

        // Save the data
        PratihariOccupation::create([
            'pratihari_id'   => $pratihari_id,
            'occupation_type'=> $request->occupation,
            'extra_activity' => $extraActivities, // Stores activities as "Music,Dance,Painting"
        ]);

        DB::commit();

        return redirect()
            ->route('admin.pratihariSeba', ['pratihari_id' => $pratihari_id])
            ->with('success', 'Occupation details saved successfully');

    }
    // Validation errors
    catch (ValidationException $e) {
        DB::rollBack();

        return redirect()
            ->back()
            ->withErrors($e->errors())
            ->withInput();
    }
    // DB / duplicate errors
    catch (QueryException $e) {
        DB::rollBack();

        // Default friendly message
        $userMessage = 'Something went wrong while saving occupation details. Please try again.';

        // MySQL duplicate entry error code = 1062
        if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
            $raw = $e->errorInfo[2] ?? $e->getMessage();

            // Adjust according to your unique indexes
            if (Str::contains($raw, 'pratihari_id')) {
                $userMessage = 'Occupation details for this member already exist.';
            } else {
                $userMessage = 'Duplicate entry detected. Please check the occupation details and try again.';
            }
        }

        Log::error('DB error saving occupation details: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $userMessage);
    }
    // Any other error
    catch (\Exception $e) {
        DB::rollBack();

        Log::error('Error saving occupation details: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to save occupation details. Please try again.');
    }
}
public function edit($pratihariId)
{
    $occupation = PratihariOccupation::where('pratihari_id', $pratihariId)->first();

    // Fallback to empty model if not found
    if (!$occupation) {
        $occupation = new \App\Models\PratihariOccupation();
    }

    return view('admin.update-occupation-details', compact('occupation', 'pratihariId'));
}

    public function update(Request $request, $pratihari_id)
    {
        try {
            $occupation = PratihariOccupation::where('pratihari_id', $pratihari_id)->first();

            $extraActivities = $request->extra_activity ? implode(',', array_filter($request->extra_activity)) : null;

            $occupation->update([
                'occupation_type' => $request->occupation,
                'extra_activity' => $extraActivities,
            ]);

            return redirect()->route('admin.viewProfile', ['pratihari_id' => $pratihari_id])->with('success', 'Occupation details updated successfully');

        } catch (\Exception $e) {
            \Log::error('Error updating occupation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update occupation details. Please try again.');
        }
    }

}

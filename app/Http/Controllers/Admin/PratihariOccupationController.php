<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariOccupation;


class PratihariOccupationController extends Controller
{
    public function pratihariOccupation()
    {
        return view('admin.pratihari-occupation-details');
    }

    public function saveOccupation(Request $request)
    {
        try {
            // Convert the extra_activity array into a comma-separated string
            $extraActivities = $request->extra_activity ? implode(',', $request->extra_activity) : null;

            $pratihari_id = $request->pratihari_id;
            // Save the data
            PratihariOccupation::create([
                'pratihari_id' => $pratihari_id,
                'occupation_type' => $request->occupation,
                'extra_activity' => $extraActivities, // Stores activities as "Music, Dance, Painting"
            ]);

            return redirect()->route('admin.pratihariSeba', ['pratihari_id' => $pratihari_id])->with('success', 'Occupation details saved successfully');

        } catch (\Exception $e) {
            \Log::error('Error saving occupation details: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save occupation details. Please try again. ' . $e->getMessage());
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

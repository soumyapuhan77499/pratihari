<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNotice;

class PratihariNoticeController extends Controller
{
    public function showNoticeForm()
    {
        return view('admin.add-notice');
    }

    public function saveNotice(Request $request)
    {
        // Validate input data (optional, you can add more rules)
        $validated = $request->validate([
            'notice_name' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'description' => 'nullable|string',
        ]);

        // Save data to database
        PratihariNotice::create([
            'notice_name' => $validated['notice_name'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'description' => $validated['description'] ?? null,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Notice saved successfully!');
    }

    public function manageNotice()
    {
        $notices = PratihariNotice::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.manage-notice', compact('notices'));
    }
    
}

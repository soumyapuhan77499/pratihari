<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNotice;
use Illuminate\Support\Facades\Storage;

class PratihariNoticeController extends Controller
{
    public function showNoticeForm()
    {
        return view('admin.add-notice');
    }

    public function saveNotice(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'notice_name'   => ['required', 'string', 'max:150'],
            'from_date'     => ['required', 'date'],
            'to_date'       => ['required', 'date', 'after_or_equal:from_date'],
            'description'   => ['nullable', 'string'],
            'notice_photo'  => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'], // 2MB
        ]);

        // Default: no photo
        $photoPath = null;

        // If a file is uploaded, store it in /storage/app/public/notices
        if ($request->hasFile('notice_photo')) {
            // Optional: give it a friendly filename
            $base = Str::slug($validated['notice_name'] ?? 'notice');
            $ext  = $request->file('notice_photo')->getClientOriginalExtension();
            $filename = $base . '-' . now()->format('YmdHis') . '.' . $ext;

            $photoPath = $request->file('notice_photo')->storeAs('notices', $filename, 'public');
            // $photoPath will be something like "notices/notice-20251110xxxxxx.jpg"
        }

        // Save to database
        PratihariNotice::create([
            'notice_name'   => $validated['notice_name'],
            'notice_photo'  => $photoPath,                // can be null
            'from_date'     => $validated['from_date'],
            'to_date'       => $validated['to_date'],
            'description'   => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Notice saved successfully!');
    }

    public function manageNotice()
    {
        $notices = PratihariNotice::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.manage-notice', compact('notices'));
    }

    public function updateNotice(Request $request, $id)
    {
        $notice = PratihariNotice::findOrFail($id);

        $validated = $request->validate([
            'notice_name'  => ['required','string','max:150'],
            'from_date'    => ['required','date'],
            'to_date'      => ['required','date','after_or_equal:from_date'],
            'description'  => ['nullable','string'],
            'notice_photo' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'], // 2MB
            'remove_photo' => ['nullable','boolean'],
        ]);

        // Text fields
        $notice->notice_name = $validated['notice_name'];
        $notice->from_date   = $validated['from_date'];
        $notice->to_date     = $validated['to_date'];
        $notice->description = $validated['description'] ?? null;

        // Remove existing photo (if requested)
        if ($request->boolean('remove_photo') && $notice->notice_photo) {
            if (Storage::disk('public')->exists($notice->notice_photo)) {
                Storage::disk('public')->delete($notice->notice_photo);
            }
            $notice->notice_photo = null;
        }

        // Upload/replace photo (if a new one is provided)
        if ($request->hasFile('notice_photo')) {
            if ($notice->notice_photo && Storage::disk('public')->exists($notice->notice_photo)) {
                Storage::disk('public')->delete($notice->notice_photo);
            }

            $base = Str::slug($validated['notice_name']);
            $ext  = $request->file('notice_photo')->getClientOriginalExtension();
            $filename = $base . '-' . now()->format('YmdHis') . '.' . $ext;

            $path = $request->file('notice_photo')->storeAs('notices', $filename, 'public');
            $notice->notice_photo = $path; // e.g. notices/abc.jpg
        }

        $notice->save();

        return redirect()->back()->with('success', 'Notice updated successfully.');
    }

    public function deleteNotice($id)
    {
        $notice = PratihariNotice::findOrFail($id);
        $notice->status = 'deleted'; // Soft delete
        $notice->save();

        return redirect()->back()->with('success', 'Notice deleted successfully.');
    }

}

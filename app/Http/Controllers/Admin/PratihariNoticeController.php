<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNotice;
use App\Models\PratihariProfile;
use App\Models\PratihariDevice;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PratihariNoticeController extends Controller
{
    public function showNoticeForm()
    {
        // Load all Pratihari names for the form
        $pratiharIs = PratihariProfile::select('pratihari_id', 'first_name', 'middle_name', 'last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($p) {
                $p->full_name = trim(implode(' ', array_filter([$p->first_name, $p->middle_name, $p->last_name])));
                return $p;
            });

        return view('admin.add-notice', compact('pratiharIs'));
    }

    public function saveNotice(Request $request)
    {
        $validated = $request->validate([
            'notice_name'   => ['required', 'string', 'max:150'],
            'from_date'     => ['required', 'date'],
            'to_date'       => ['required', 'date', 'after_or_equal:from_date'],
            'description'   => ['nullable', 'string'],
            'notice_photo'  => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],

            // Notification controls
            'send_notification' => ['nullable', 'boolean'],
            'send_to_all'       => ['nullable', 'boolean'],
            'platforms'         => ['nullable', 'array'],
            'platforms.*'       => ['in:android,ios,web'],
            'pratihari_ids'     => ['nullable', 'array'],
            'pratihari_ids.*'   => ['string', 'exists:pratihari__profile_details,pratihari_id'],
        ]);

        $photoPath = null;

        if ($request->hasFile('notice_photo')) {
            $base = Str::slug($validated['notice_name'] ?? 'notice');
            $ext  = $request->file('notice_photo')->getClientOriginalExtension();
            $filename  = $base . '-' . now()->format('YmdHis') . '.' . $ext;

            $photoPath = $request->file('notice_photo')->storeAs('notices', $filename, 'public');
            // "notices/notice-YYYYmmddHHMMSS.jpg"
        }

        $notice = PratihariNotice::create([
            'notice_name'  => $validated['notice_name'],
            'notice_photo' => $photoPath,
            'from_date'    => $validated['from_date'],
            'to_date'      => $validated['to_date'],
            'description'  => $validated['description'] ?? null,
            'status'       => 'active',
        ]);

        // -------------------------------
        // Send notifications (device-wise)
        // -------------------------------
        if ($request->boolean('send_notification')) {
            try {
                $sendToAll  = $request->boolean('send_to_all');
                $platforms  = $validated['platforms'] ?? [];

                $targetPratihariIds = $sendToAll
                    ? PratihariProfile::pluck('pratihari_id')->toArray()
                    : ($validated['pratihari_ids'] ?? []);

                if (empty($targetPratihariIds)) {
                    return redirect()->back()->with('error', 'Notice saved, but no Pratihari selected for notification.');
                }

                // Get authorized device tokens for these users (optionally filtered by platform)
                $tokensQuery = PratihariDevice::query()
                    ->authorized()
                    ->whereIn('pratihari_id', $targetPratihariIds);

                if (!empty($platforms)) {
                    $tokensQuery->platformIn($platforms);
                }

                // IMPORTANT: using device_id as token here
                $deviceTokens = $tokensQuery->pluck('device_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($deviceTokens)) {
                    return redirect()->back()->with('error', 'Notice saved, but no authorized devices found for selected Pratihari.');
                }

                $title = $notice->notice_name;

                // Keep body short for notification display
                $body = Str::limit(strip_tags((string) $notice->description), 120, '...');

                $data = [
                    'type'      => 'notice',
                    'notice_id' => (string) $notice->id,
                    'from_date' => (string) $notice->from_date,
                    'to_date'   => (string) $notice->to_date,
                ];

                $notifier = new NotificationService('pratihari');
                $notifier->sendBulkNotifications($deviceTokens, $title, $body, $data);

            } catch (\Throwable $e) {
                \Log::error('Notice notification failed', [
                    'notice_id' => $notice->id ?? null,
                    'error'     => $e->getMessage(),
                ]);

                return redirect()->back()->with('error', 'Notice saved, but notification failed. Check logs.');
            }
        }

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
            'notice_photo' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
            'remove_photo' => ['nullable','boolean'],
        ]);

        $notice->notice_name = $validated['notice_name'];
        $notice->from_date   = $validated['from_date'];
        $notice->to_date     = $validated['to_date'];
        $notice->description = $validated['description'] ?? null;

        if ($request->boolean('remove_photo') && $notice->notice_photo) {
            if (Storage::disk('public')->exists($notice->notice_photo)) {
                Storage::disk('public')->delete($notice->notice_photo);
            }
            $notice->notice_photo = null;
        }

        if ($request->hasFile('notice_photo')) {
            if ($notice->notice_photo && Storage::disk('public')->exists($notice->notice_photo)) {
                Storage::disk('public')->delete($notice->notice_photo);
            }

            $base = Str::slug($validated['notice_name']);
            $ext  = $request->file('notice_photo')->getClientOriginalExtension();
            $filename = $base . '-' . now()->format('YmdHis') . '.' . $ext;

            $path = $request->file('notice_photo')->storeAs('notices', $filename, 'public');
            $notice->notice_photo = $path;
        }

        $notice->save();

        return redirect()->back()->with('success', 'Notice updated successfully.');
    }

    public function deleteNotice($id)
    {
        $notice = PratihariNotice::findOrFail($id);
        $notice->status = 'deleted';
        $notice->save();

        return redirect()->back()->with('success', 'Notice deleted successfully.');
    }
}

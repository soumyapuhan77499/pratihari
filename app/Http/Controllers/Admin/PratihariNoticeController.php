<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\PratihariNotice;
use App\Models\PratihariProfile;
use App\Models\PratihariDevice;
use App\Models\PratihariSeba;
use App\Models\PratihariSebaMaster;

use App\Services\NotificationService;

class PratihariNoticeController extends Controller
{
        
    private function sebaBasedRecipientIds(): array
    {
        // Get seba_ids by type
        $pratihariSebaIds = PratihariSebaMaster::where('type', 'pratihari')->pluck('id');
        $gochhikarSebaIds = PratihariSebaMaster::where('type', 'gochhikar')->pluck('id');

        // Get unique pratihari_ids (only active if column exists)
        $pratihariIds = PratihariSeba::whereIn('seba_id', $pratihariSebaIds)
            ->when(
                Schema::hasColumn('pratihari__seba_details', 'status'),
                fn ($q) => $q->where('status', 'active')
            )
            ->distinct()
            ->pluck('pratihari_id');

        $gochhikarIds = PratihariSeba::whereIn('seba_id', $gochhikarSebaIds)
            ->when(
                Schema::hasColumn('pratihari__seba_details', 'status'),
                fn ($q) => $q->where('status', 'active')
            )
            ->distinct()
            ->pluck('pratihari_id');

        return [$pratihariIds, $gochhikarIds];
    }

    public function showNoticeForm()
    {
        [$pratihariIds, $gochhikarIds] = $this->sebaBasedRecipientIds();

        $pratihari_name = PratihariProfile::whereIn('pratihari_id', $pratihariIds)
            ->approved()
            ->orderBy('first_name')->orderBy('middle_name')->orderBy('last_name')
            ->get(['pratihari_id', 'first_name', 'middle_name', 'last_name', 'bhagari', 'baristha_bhai_pua']);

        $gochhikar_name = PratihariProfile::whereIn('pratihari_id', $gochhikarIds)
            ->approved()
            ->orderBy('first_name')->orderBy('middle_name')->orderBy('last_name')
            ->get(['pratihari_id', 'first_name', 'middle_name', 'last_name', 'bhagari', 'baristha_bhai_pua']);

        $bhagariFilters = [
            'all' => 'All',
            'yes' => 'Only Bhagari (Yes)',
            'no'  => 'Only Bhagari (No)',
        ];

        $baristhaFilters = [
            'all' => 'All',
            'yes' => 'Only Baristha Bhai Pua (Yes)',
            'no'  => 'Only Baristha Bhai Pua (No)',
        ];

        return view('admin.add-notice', compact(
            'pratihari_name',
            'gochhikar_name',
            'bhagariFilters',
            'baristhaFilters'
        ));
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
            'recipient_group'   => ['nullable', 'in:all,pratihari,gochhikar,selected'],

            // ✅ NEW FILTERS
            'bhagari_filter'    => ['nullable', 'in:all,yes,no'],
            'baristha_filter'   => ['nullable', 'in:all,yes,no'],

            'pratihari_ids'     => ['nullable', 'array'],
            'pratihari_ids.*'   => ['string', 'exists:pratihari__profile_details,pratihari_id'],
        ]);

        // -------------------------------
        // Save Notice
        // -------------------------------
        $photoPath = null;

        if ($request->hasFile('notice_photo')) {
            $base = Str::slug($validated['notice_name'] ?? 'notice');
            $ext  = $request->file('notice_photo')->getClientOriginalExtension();
            $filename  = $base . '-' . now()->format('YmdHis') . '.' . $ext;
            $photoPath = $request->file('notice_photo')->storeAs('notices', $filename, 'public');
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
        // Send notifications (UPDATED)
        // -------------------------------
        if ($request->boolean('send_notification')) {
            try {
                $group          = $validated['recipient_group'] ?? 'all';
                $bhagariFilter  = $validated['bhagari_filter'] ?? 'all';
                $baristhaFilter = $validated['baristha_filter'] ?? 'all';

                [$pratihariIds, $gochhikarIds] = $this->sebaBasedRecipientIds();

                // Choose base IDs by group
                if ($group === 'pratihari') {
                    $baseIds = $pratihariIds->toArray();
                } elseif ($group === 'gochhikar') {
                    $baseIds = $gochhikarIds->toArray();
                } elseif ($group === 'selected') {
                    $baseIds = $validated['pratihari_ids'] ?? [];
                } else { // all
                    $baseIds = array_values(array_unique(array_merge(
                        $pratihariIds->toArray(),
                        $gochhikarIds->toArray()
                    )));
                }

                if (empty($baseIds)) {
                    return redirect()->back()->with('error', 'Notice saved, but no recipients found for notification.');
                }

                // ✅ Filter only approved + bhagari + baristha
                $profilesQ = PratihariProfile::approved()
                    ->whereIn('pratihari_id', $baseIds);

                if ($bhagariFilter === 'yes') {
                    $profilesQ->where('bhagari', 1);
                } elseif ($bhagariFilter === 'no') {
                    $profilesQ->where('bhagari', 0);
                }

                if ($baristhaFilter === 'yes') {
                    $profilesQ->where('baristha_bhai_pua', 1);
                } elseif ($baristhaFilter === 'no') {
                    $profilesQ->where('baristha_bhai_pua', 0);
                }

                $profiles = $profilesQ->get(['pratihari_id', 'bhagari', 'baristha_bhai_pua']);

                $finalIds = $profiles->pluck('pratihari_id')->unique()->values()->all();

                if (empty($finalIds)) {
                    return redirect()->back()->with('error', 'Notice saved, but no approved recipients match the selected filters.');
                }

                // Device tokens (authorized only)
                $deviceTokens = PratihariDevice::query()
                    ->authorized()
                    ->whereIn('pratihari_id', $finalIds)
                    ->pluck('device_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($deviceTokens)) {
                    return redirect()->back()->with('error', 'Notice saved, but no authorized devices found for selected recipients.');
                }

                // Notification FORMAT (Title/Body/Data)
                $title = $notice->notice_name;

                $prefixParts = ['[' . strtoupper($group) . ']'];

                if ($bhagariFilter !== 'all') {
                    $prefixParts[] = '[BHAGARI:' . strtoupper($bhagariFilter) . ']';
                }
                if ($baristhaFilter !== 'all') {
                    $prefixParts[] = '[BARISTHA:' . strtoupper($baristhaFilter) . ']';
                }

                $bodyPrefix = implode('', $prefixParts);

                $body = $bodyPrefix . ' ' . Str::limit(strip_tags((string)$notice->description), 120, '...');

                $data = [
                    'type'               => 'notice',
                    'notice_id'          => (string) $notice->id,
                    'from_date'          => (string) $notice->from_date,
                    'to_date'            => (string) $notice->to_date,
                    'recipient_group'    => (string) $group,
                    'bhagari_filter'     => (string) $bhagariFilter,
                    'baristha_filter'    => (string) $baristhaFilter,
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

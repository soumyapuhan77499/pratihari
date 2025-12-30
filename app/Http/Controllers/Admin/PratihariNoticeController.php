<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FCMNotification;
use App\Models\FCMNotificationLog;
use App\Models\PratihariDevice;
use App\Models\PratihariNotice;
use App\Models\PratihariProfile;
use App\Services\NotificationService;
use App\Models\PratihariSeba;
use App\Models\PratihariSebaMaster;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

            'send_notification' => ['nullable', 'boolean'],
            'recipient_group'   => ['nullable', 'in:all,pratihari,gochhikar,selected'],

            'bhagari_filter'    => ['nullable', 'in:all,yes,no'],
            'baristha_filter'   => ['nullable', 'in:all,yes,no'],

            'pratihari_ids'     => ['nullable', 'array'],
            'pratihari_ids.*'   => ['string', 'exists:pratihari__profile_details,pratihari_id'],
        ]);

        // -------------------------------
        // 1) Save Notice
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

        // Messages for Blade
        $flashWarning = null;
        $flashErrorDetails = null;

        // -------------------------------
        // 2) Send Notification (optional)
        // -------------------------------
        if ($request->boolean('send_notification')) {
            $group          = $validated['recipient_group'] ?? 'all';
            $bhagariFilter  = $validated['bhagari_filter'] ?? 'all';
            $baristhaFilter = $validated['baristha_filter'] ?? 'all';

            $fcmRow = null;

            try {
                // Get seba based recipients (your existing function)
                [$pratihariIds, $gochhikarIds] = $this->sebaBasedRecipientIds();

                // Base IDs by group
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
                    $flashWarning = 'Notice saved, but no recipients found for notification.';
                    goto done;
                }

                // Approved + filters
                $profilesQ = PratihariProfile::query()
                    ->when(method_exists(PratihariProfile::class, 'approved'), fn ($q) => $q->approved())
                    ->when(!method_exists(PratihariProfile::class, 'approved'), fn ($q) => $q->where('pratihari_status', 'approved'))
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

                $finalIds = $profilesQ->pluck('pratihari_id')->unique()->values()->all();

                if (empty($finalIds)) {
                    $flashWarning = 'Notice saved, but no approved recipients match the selected filters.';
                    goto done;
                }

                // Device tokens
                $deviceTokens = PratihariDevice::query()
                    ->when(method_exists(PratihariDevice::class, 'scopeAuthorized'), fn ($q) => $q->authorized())
                    ->whereIn('pratihari_id', $finalIds)
                    ->pluck('device_id')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (empty($deviceTokens)) {
                    $flashWarning = 'Notice saved, but no authorized devices found for selected recipients.';
                    goto done;
                }

                // -------------------------------
                // Build notification content
                // -------------------------------
                $title = (string) $notice->notice_name;

                // ✅ DB description should be clean (NO [SELECTED])
                $dbDescription = $notice->description ?? null;

                // ✅ Push body may include prefix (optional)
                $prefixParts = ['[' . strtoupper($group) . ']'];
                if ($bhagariFilter !== 'all')  $prefixParts[] = '[BHAGARI:' . strtoupper($bhagariFilter) . ']';
                if ($baristhaFilter !== 'all') $prefixParts[] = '[BARISTHA:' . strtoupper($baristhaFilter) . ']';

                $bodyPrefix = implode('', $prefixParts);

                $shortText = Str::limit(strip_tags((string) ($notice->description ?? '')), 120, '...');
                $pushBody  = trim($bodyPrefix . ' ' . $shortText);

                $data = [
                    'type'            => 'notice',
                    'notice_id'       => (string) $notice->id,
                    'from_date'       => (string) $notice->from_date,
                    'to_date'         => (string) $notice->to_date,
                    'recipient_group' => (string) $group,
                    'bhagari_filter'  => (string) $bhagariFilter,
                    'baristha_filter' => (string) $baristhaFilter,
                ];

                $imageUrl = null;
                if (!empty($notice->notice_photo)) {
                    $imageUrl = Storage::disk('public')->url($notice->notice_photo);
                }

                // -------------------------------
                // Create FCM row FIRST (so it always saves)
                // -------------------------------
                $fcmRow = FCMNotification::create([
                    'notice_id' => $notice->id,
                    'title'         => $title,
                    'description'   => $dbDescription,           // ✅ FIX: clean DB value
                    'image'         => $notice->notice_photo ?? null,
                    'audience'      => $group,
                    'pratihari_ids' => $finalIds,
                    'platforms'     => [],                       // avoid null json issues
                    'status'        => 'sending',
                    'success_count' => 0,
                    'failure_count' => 0,
                ]);

                // -------------------------------
                // Send and collect results
                // -------------------------------
                $notifier = new NotificationService('pratihari');
                $summary  = $notifier->sendBulkNotificationsDetailed(
                    $deviceTokens,
                    $title,
                    $pushBody,   // ✅ prefix only for push
                    $data,
                    $imageUrl
                );

                $success = (int) ($summary['success'] ?? 0);
                $failure = (int) ($summary['failure'] ?? 0);

                $status = 'sent';
                if ($success > 0 && $failure > 0) $status = 'partial';
                if ($success === 0 && $failure > 0) $status = 'failed';

                $fcmRow->update([
                    'status'        => $status,
                    'success_count' => $success,
                    'failure_count' => $failure,
                ]);

                // -------------------------------
                // Device-wise logs
                // -------------------------------
                $tokenToUser = PratihariDevice::query()
                    ->whereIn('device_id', $deviceTokens)
                    ->pluck('pratihari_id', 'device_id')
                    ->toArray();

                $logRows = [];
                foreach (($summary['results'] ?? []) as $r) {
                    $token = $r['token'] ?? null;
                    if (!$token) continue;

                    $logRows[] = [
                        'fcm_notification_id' => $fcmRow->id,
                        'pratihari_id'        => $tokenToUser[$token] ?? null,
                        'device_token'        => $token,
                        'platform'            => null,
                        'status'              => $r['status'] ?? 'failure',
                        'error_code'          => $r['error_code'] ?? null,
                        'error_message'       => $r['error_message'] ?? null,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ];
                }

                if (!empty($logRows)) {
                    foreach (array_chunk($logRows, 1000) as $chunk) {
                        FCMNotificationLog::insert($chunk);
                    }
                }

                if ($failure > 0) {
                    $flashWarning = 'Notice saved. Notification sent with some failures. Open notification logs for details.';
                }

            } catch (\Throwable $e) {
                Log::error('Notice notification failed', [
                    'notice_id' => $notice->id ?? null,
                    'fcm_notification_id' => $fcmRow->id ?? null,
                    'error' => $e->getMessage(),
                ]);

                if ($fcmRow) {
                    $fcmRow->update(['status' => 'failed']);
                }

                $flashWarning = 'Notice saved, but notification failed. Please check the error below.';
                $flashErrorDetails = $e->getMessage();
            }
        }

        done:

        if ($flashErrorDetails) {
            return redirect()->back()
                ->with('success', 'Notice saved successfully!')
                ->with('warning', $flashWarning)
                ->with('notify_error', $flashErrorDetails);
        }

        if ($flashWarning) {
            return redirect()->back()
                ->with('success', 'Notice saved successfully!')
                ->with('warning', $flashWarning);
        }

        return redirect()->back()->with('success', 'Notice saved successfully!');
    }

    public function manageNotice()
    {
        $notices = PratihariNotice::where('status', 'active')
            ->with(['latestFcmNotification'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Collect all recipient pratihari_ids from latest FCM rows
        $allRecipientIds = [];
        foreach ($notices as $n) {
            $ids = $n->latestFcmNotification?->pratihari_ids ?? [];
            if (is_array($ids)) {
                $allRecipientIds = array_merge($allRecipientIds, $ids);
            }
        }
        $allRecipientIds = array_values(array_unique(array_filter($allRecipientIds)));

        // Map pratihari_id => full name
        $nameMap = [];
        if (!empty($allRecipientIds)) {
            $profiles = PratihariProfile::whereIn('pratihari_id', $allRecipientIds)
                ->select('pratihari_id', 'first_name', 'middle_name', 'last_name')
                ->get();

            foreach ($profiles as $p) {
                $nameMap[$p->pratihari_id] = trim(implode(' ', array_filter([
                    $p->first_name, $p->middle_name, $p->last_name
                ])));
            }
        }

        // Attach computed fields for blade (no DB write)
        foreach ($notices as $n) {
            $fcm = $n->latestFcmNotification;
            $ids = $fcm?->pratihari_ids ?? [];
            $ids = is_array($ids) ? $ids : [];

            $names = [];
            foreach ($ids as $id) {
                $names[] = $nameMap[$id] ?? $id; // fallback to id if name missing
            }

            $n->fcm_recipient_count = count($ids);
            $n->fcm_recipient_names = $names;
        }

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

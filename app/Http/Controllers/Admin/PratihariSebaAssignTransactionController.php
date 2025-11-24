<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariSebaAssignTransaction;
use App\Models\PratihariProfile;
use App\Models\PratihariSebaMaster;
use App\Models\PratihariBeddhaMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PratihariSebaAssignTransactionController extends Controller
{
    /**
     * Build a safe COALESCE expression for admin display name depending on available columns.
     */
    protected function detectAdminDisplayExpr(string $adminTable): string
    {
        // preferred admin display columns in order
        $candidates = ['name', 'admin_name', 'username', 'email', 'admin_id'];
        $found = [];

        foreach ($candidates as $col) {
            if (Schema::hasColumn($adminTable, $col)) {
                $found[] = "a.{$col}";
            }
        }

        if (empty($found)) {
            $found[] = 'a.admin_id';
        }

        return 'COALESCE(' . implode(', ', $found) . ')';
    }

    /**
     * Index - list transactions with filters and optional CSV export.
     */
    public function index(Request $request)
    {
        // derive table names from models
        $txModel = new PratihariSebaAssignTransaction();
        $txTable = $txModel->getTable(); // e.g. 'pratihari__seba_assign_transaction'

        $profileModel = new PratihariProfile();
        $profileTable = $profileModel->getTable(); // e.g. 'pratihari__profile_details'

        $sebaModel = new PratihariSebaMaster();
        $sebaTable = $sebaModel->getTable(); // e.g. 'pratihari__seba_master' or 'master__seba'

        $adminTable = 'admins'; // change if your admin table name differs

        // admin display expression
        $adminCoalesce = $this->detectAdminDisplayExpr($adminTable);

        // base query (select t.* so 'year' is included)
        $query = DB::table("{$txTable} as t")
            ->leftJoin("{$profileTable} as p", "t.pratihari_id", '=', "p.pratihari_id")
            ->leftJoin("{$sebaTable} as s", "t.seba_id", '=', "s.id")
            ->leftJoin("{$adminTable} as a", "t.assigned_by", '=', "a.admin_id")
            ->selectRaw(
                "t.*, 
                 CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name) as pratihari_name, 
                 s.seba_name as seba_name, 
                 {$adminCoalesce} as admin_name"
            );

        // filters
        $pratihariId = $request->query('pratihari_id');
        $sebaId      = $request->query('seba_id');
        $dateFrom    = $request->query('date_from');
        $dateTo      = $request->query('date_to');
        $search      = $request->query('search');

        if ($pratihariId) {
            $query->where('t.pratihari_id', $pratihariId);
        }

        if ($sebaId) {
            $query->where('t.seba_id', $sebaId);
        }

        if ($dateFrom) {
            try {
                $df = Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay();
                $query->where('t.date_time', '>=', $df);
            } catch (\Exception $e) { /* ignore parse error */ }
        }

        if ($dateTo) {
            try {
                $dt = Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();
                $query->where('t.date_time', '<=', $dt);
            } catch (\Exception $e) { /* ignore parse error */ }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('t.beddha_id', 'like', "%{$search}%")
                    ->orWhere('s.seba_name', 'like', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name)"), 'like', "%{$search}%")
                    ->orWhere('t.year', 'like', "%{$search}%");
            });
        }

        // DESC order wise data (by date and id)
        $query->orderBy('t.date_time', 'desc')
              ->orderBy('t.id', 'desc');

        // CSV export
        if ($request->query('export') === 'csv') {
            $rows = $query->get();
            return $this->exportCsv($rows);
        }

        $perPage = 15;
        $rows = $query->paginate($perPage)->withQueryString();

        // --------- Preload Beddha Names for list (colorful UI) ----------
        $allBeddhaIds = [];
        foreach ($rows as $row) {
            $ids = array_filter(array_map('trim', explode(',', (string) $row->beddha_id)));
            $allBeddhaIds = array_merge($allBeddhaIds, $ids);
        }
        $allBeddhaIds = array_unique($allBeddhaIds);

        $beddhaNames = [];
        if (!empty($allBeddhaIds)) {
            $beddhaModel = new PratihariBeddhaMaster();
            $beddhaKey = $beddhaModel->getKeyName(); // supports id or beddha_id

            $beddhaNames = $beddhaModel->newQuery()
                ->whereIn($beddhaKey, $allBeddhaIds)
                ->pluck('beddha_name', $beddhaKey)
                ->toArray();
        }
        // ---------------------------------------------------------------

        // filter dropdowns
        $pratiharis = DB::table($profileTable)
            ->select('pratihari_id', DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name) AS name"))
            ->orderBy('first_name')
            ->pluck('name', 'pratihari_id');

        $sebas = DB::table($sebaTable)
            ->orderBy('seba_name')
            ->pluck('seba_name', 'id');

        return view('admin.pratihari-seba-transactions', compact('rows', 'pratiharis', 'sebas', 'beddhaNames'));
    }

    /**
     * Show single transaction details (JSON).
     */
    public function show($id)
    {
        $txModel = new PratihariSebaAssignTransaction();
        $txTable = $txModel->getTable();

        $profileModel = new PratihariProfile();
        $profileTable = $profileModel->getTable();

        $sebaModel = new PratihariSebaMaster();
        $sebaTable = $sebaModel->getTable();

        $tx = DB::table("{$txTable} as t")
            ->leftJoin("{$profileTable} as p", "t.pratihari_id", '=', "p.pratihari_id")
            ->leftJoin("{$sebaTable} as s", "t.seba_id", '=', "s.id")
            ->where('t.id', $id)
            ->selectRaw(
                "t.*, 
                 CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name) as pratihari_name, 
                 s.seba_name as seba_name"
            )
            ->first();

        if (!$tx) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Resolve beddha ids => names (support id or beddha_id as PK)
        $beddhaIds = array_filter(array_map('trim', explode(',', (string) $tx->beddha_id)));
        $beddhaNames = [];
        if (!empty($beddhaIds)) {
            $beddhaModel = new PratihariBeddhaMaster();
            $beddhaKey = $beddhaModel->getKeyName();

            $masters = $beddhaModel->newQuery()
                ->whereIn($beddhaKey, $beddhaIds)
                ->pluck('beddha_name', $beddhaKey)
                ->toArray();

            foreach ($beddhaIds as $bid) {
                $beddhaNames[] = $masters[$bid] ?? ("#{$bid}");
            }
        }

        return response()->json([
            'transaction'   => $tx,
            'beddha_names'  => $beddhaNames,
        ]);
    }

    /**
     * CSV export helper
     */
    protected function exportCsv($rows)
    {
        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Pratihari ID', 'Pratihari Name', 'Seba ID', 'Seba Name',
                'Beddha IDs (CSV)', 'Year', 'Assigned By', 'Date Time', 'Status'
            ]);

            foreach ($rows as $r) {
                $pratName   = property_exists($r, 'pratihari_name') ? $r->pratihari_name : '';
                $sebaName   = property_exists($r, 'seba_name') ? $r->seba_name : '';
                $assignedBy = property_exists($r, 'admin_name') ? $r->admin_name : ($r->assigned_by ?? '');

                fputcsv($handle, [
                    $r->id ?? '',
                    $r->pratihari_id ?? '',
                    $pratName,
                    $r->seba_id ?? '',
                    $sebaName,
                    $r->beddha_id ?? '',
                    $r->year ?? '',
                    $assignedBy,
                    $r->date_time ?? '',
                    $r->status ?? '',
                ]);
            }

            fclose($handle);
        });

        $filename = 'pratihari_seba_transactions_' . now()->format('Ymd_His') . '.csv';

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }
}

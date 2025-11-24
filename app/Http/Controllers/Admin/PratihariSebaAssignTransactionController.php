<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariSebaAssignTransaction;
use App\Models\PratihariProfile;
use App\Models\PratihariSebaMaster;
use App\Models\PratihariBeddhaMaster;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PratihariSebaAssignTransactionController extends Controller
{
    /**
     * Display a listing of admin assign transactions with filters and CSV export.
     */
    public function index(Request $request)
    {
        $query = PratihariSebaAssignTransaction::query();

        // eager load related data where possible (we will join for names)
        // Filters
        $pratihariId = $request->query('pratihari_id');
        $sebaId      = $request->query('seba_id');
        $dateFrom    = $request->query('date_from');
        $dateTo      = $request->query('date_to');
        $search      = $request->query('search');

        // join to get pratihari name and seba name
        // Using leftJoin so records still appear if FK missing
        $query = $query->leftJoin('pratihari_profiles AS p', 'pratihari__seba_assign_transaction.pratihari_id', '=', 'p.pratihari_id')
                       ->leftJoin('pratihari__seba_master AS s', 'pratihari__seba_assign_transaction.seba_id', '=', 's.id')
                       ->leftJoin('admins AS a', 'pratihari__seba_assign_transaction.assigned_by', '=', 'a.admin_id') // if your admins table is named differently, change
                       ->select('pratihari__seba_assign_transaction.*', 
                           DB::raw("CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name) as pratihari_name"),
                           's.seba_name as seba_name',
                           DB::raw("COALESCE(a.name, a.email, a.admin_id) as admin_name")
                       );

        if ($pratihariId) {
            $query->where('pratihari__seba_assign_transaction.pratihari_id', $pratihariId);
        }

        if ($sebaId) {
            $query->where('pratihari__seba_assign_transaction.seba_id', $sebaId);
        }

        if ($dateFrom) {
            try {
                $df = Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay();
                $query->where('pratihari__seba_assign_transaction.date_time', '>=', $df);
            } catch (\Exception $e) { /* ignore parse error */ }
        }

        if ($dateTo) {
            try {
                $dt = Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();
                $query->where('pratihari__seba_assign_transaction.date_time', '<=', $dt);
            } catch (\Exception $e) { /* ignore parse error */ }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pratihari__seba_assign_transaction.beddha_id', 'like', "%{$search}%")
                  ->orWhere('s.seba_name', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name)"), 'like', "%{$search}%")
                  ->orWhere('pratihari__seba_assign_transaction.year', 'like', "%{$search}%");
            });
        }

        $query = $query->orderBy('pratihari__seba_assign_transaction.date_time', 'desc');

        // CSV export if requested
        if ($request->query('export') === 'csv') {
            $rows = $query->get();
            return $this->exportCsv($rows);
        }

        $perPage = 15;
        $rows = $query->paginate($perPage)->withQueryString();

        // filter dropdowns data
        $pratiharis = PratihariProfile::query()
            ->select('pratihari_id', DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name) AS name"))
            ->orderBy('first_name')
            ->pluck('name', 'pratihari_id');

        $sebas = PratihariSebaMaster::query()
            ->orderBy('seba_name')
            ->pluck('seba_name', 'id');

        return view('admin.pratihari-seba-transactions', compact('rows', 'pratiharis', 'sebas'));
    }

    /**
     * Show single transaction details (used by modal / detail page)
     */
    public function show($id)
    {
        $tx = PratihariSebaAssignTransaction::where('id', $id)
            ->leftJoin('pratihari_profiles AS p', 'pratihari__seba_assign_transaction.pratihari_id', '=', 'p.pratihari_id')
            ->leftJoin('pratihari__seba_master AS s', 'pratihari__seba_assign_transaction.seba_id', '=', 's.id')
            ->select('pratihari__seba_assign_transaction.*',
                DB::raw("CONCAT_WS(' ', p.first_name, p.middle_name, p.last_name) as pratihari_name"),
                's.seba_name as seba_name'
            )
            ->firstOrFail();

        // Resolve beddha ids to names where possible
        $beddhaIds = array_filter(array_map('trim', explode(',', (string)$tx->beddha_id)));
        $beddhaNames = [];
        if (!empty($beddhaIds)) {
            $masters = \App\Models\PratihariBeddhaMaster::whereIn('id', $beddhaIds)->pluck('beddha_name', 'id')->toArray();
            foreach ($beddhaIds as $bid) {
                $beddhaNames[] = $masters[$bid] ?? ("#{$bid}");
            }
        }

        return response()->json([
            'transaction' => $tx,
            'beddha_names' => $beddhaNames,
        ]);
    }

    /**
     * Export CSV helper
     */
    protected function exportCsv($rows)
    {
        $response = new StreamedResponse(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            // header row
            fputcsv($handle, [
                'ID', 'Pratihari ID', 'Pratihari Name', 'Seba ID', 'Seba Name',
                'Beddha IDs (CSV)', 'Year', 'Assigned By', 'Date Time', 'Status'
            ]);

            foreach ($rows as $r) {
                // get pratihari name and seba name if present in joined columns
                $pratName = $r->pratihari_name ?? '';
                $sebaName = $r->seba_name ?? '';
                $assignedBy = $r->assigned_by ?? '';

                fputcsv($handle, [
                    $r->id,
                    $r->pratihari_id,
                    $pratName,
                    $r->seba_id,
                    $sebaName,
                    $r->beddha_id,
                    $r->year,
                    $assignedBy,
                    $r->date_time,
                    $r->status,
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

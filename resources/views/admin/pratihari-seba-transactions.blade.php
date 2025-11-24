@extends('layouts.app')

@section('styles')
    <!-- Select2 + Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-a:#7c3aed;  /* violet  */
            --brand-b:#06b6d4;  /* cyan    */
            --accent:#f5c12e;  /* amber   */
            --ink:#0b1220;
            --muted:#64748b;
            --border:rgba(2,6,23,.10);
            --soft:#f8fafc;
            --chip-bg:#f1f5f9;
        }

        body {
            background:
                radial-gradient(1100px 540px at -10% -10%, rgba(124,58,237,.06), transparent 60%),
                radial-gradient(1100px 540px at 110% -10%, rgba(6,182,212,.06), transparent 60%),
                #f9fafb;
        }

        .page-header {
            border-radius: 18px;
            padding: 18px 20px;
            background: linear-gradient(120deg,var(--brand-a),var(--brand-b));
            color:#fff;
            box-shadow: 0 18px 40px rgba(15,23,42,.18);
            display:flex;
            flex-wrap:wrap;
            gap:16px;
            align-items:flex-start;
            justify-content:space-between;
            position:relative;
            overflow:hidden;
        }

        .page-header::before,
        .page-header::after {
            content:'';
            position:absolute;
            border-radius:999px;
            background:rgba(255,255,255,.12);
            filter:blur(2px);
        }

        .page-header::before {
            width:180px; height:180px;
            top:-60px; right:-40px;
        }

        .page-header::after {
            width:130px; height:130px;
            bottom:-40px; left:-30px;
        }

        .page-header-main {
            position:relative;
            z-index:1;
            display:flex;
            gap:14px;
            align-items:flex-start;
        }

        .page-header-icon {
            width:46px;
            height:46px;
            border-radius:16px;
            background:rgba(15,23,42,.25);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            border:1px solid rgba(255,255,255,.32);
        }

        .page-title {
            font-weight:800;
            font-size:1.1rem;
            letter-spacing:.04em;
            text-transform:uppercase;
        }

        .page-subtitle {
            font-size:.85rem;
            opacity:.9;
        }

        .page-header-meta {
            position:relative;
            z-index:1;
            display:flex;
            flex-direction:column;
            gap:10px;
            align-items:flex-end;
        }

        .mini-pill {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:6px 11px;
            border-radius:999px;
            font-size:.8rem;
            background:rgba(15,23,42,.22);
            border:1px solid rgba(148,163,184,.7);
            backdrop-filter:blur(8px);
        }

        .mini-pill i {
            font-size:.9rem;
        }

        .btn-soft-light {
            border-radius:999px;
            background:#fff;
            color:#0f172a;
            border:none;
            box-shadow:0 10px 30px rgba(15,23,42,.35);
            font-size:.82rem;
            font-weight:600;
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:7px 14px;
        }

        .btn-soft-light:hover {
            color:#0f172a;
        }

        .btn-soft-light i {
            font-size:.95rem;
        }

        /* Card + table section */

        .card {
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 12px 32px rgba(15,23,42,.08);
            background:#fff;
        }

        .card-filter-head {
            padding: 14px 18px 0;
        }

        .card-filter-title {
            font-weight:700;
            font-size:.95rem;
            color:var(--ink);
        }

        .card-filter-sub {
            font-size:.8rem;
            color:var(--muted);
        }

        .filter-bar {
            padding: 12px 18px 18px;
            border-radius: 0 0 14px 14px;
            border-top:1px solid rgba(148,163,184,.35);
            background:#f9fafb;
        }

        .table-shell {
            padding: 14px 18px 18px;
        }

        .table-responsive {
            max-height: 62vh;
            overflow: auto;
            border-radius: 12px;
            border:1px solid #e5e7eb;
            background:#fff;
        }

        .small-muted {
            font-size: .85rem;
            color:#65748b;
        }

        .badge-beddha {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color:#fff;
            font-weight:600;
            padding:.25rem .65rem;
            border-radius:999px;
            font-size: .75rem;
            box-shadow: 0 1px 2px rgba(15,23,42,.2);
        }

        .badge-beddha-id {
            background: var(--chip-bg);
            color: var(--muted);
            border-radius: 999px;
            padding: .2rem .55rem;
            font-size: .7rem;
            border: 1px solid #cbd5f5;
        }

        thead.table-light th {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .transaction-header {
            background: linear-gradient(90deg, var(--brand-a), var(--brand-b));
            color: #fff;
            border-radius: 10px;
            padding: .75rem 1rem;
        }

        @media (max-width: 768px) {
            .page-header {
                padding:14px 14px;
            }
            .page-header-main {
                flex-direction:row;
                align-items:center;
            }
            .page-header-meta {
                align-items:flex-start;
            }
        }
    </style>
@endsection

@section('content')
<div class="row mt-3">
    <div class="col-12 mb-3">
        {{-- PAGE HEADER / HERO --}}
        <div class="page-header">
            <div class="page-header-main">
                <div class="page-header-icon">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div>
                    <div class="page-title">Pratihari Seba Assign Transactions</div>
                    <div class="page-subtitle">
                        Review, filter and export all Pratihari Seba assignment history with Beddha mapping.
                    </div>
                </div>
            </div>

            <div class="page-header-meta">
                <div class="mini-pill">
                    <i class="fa-regular fa-calendar"></i>
                    <span>Today: {{ \Carbon\Carbon::now()->timezone('Asia/Kolkata')->format('d M Y') }}</span>
                </div>
                <a href="{{ route('pratihariSebaTransactions.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
                   class="btn-soft-light">
                    <i class="fa-regular fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            {{-- FILTER HEADER --}}
            <div class="card-filter-head">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="card-filter-title">Filter & Search</div>
                        <div class="card-filter-sub">Refine by Pratihari, Seba, date range or year / beddha ID.</div>
                    </div>
                    <div class="small text-muted d-none d-md-block">
                        Showing {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }} records
                    </div>
                </div>
            </div>

            {{-- FILTER BAR --}}
            <form id="filterForm" method="GET" action="{{ route('pratihariSebaTransactions.index') }}">
                <div class="filter-bar">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small">Pratihari</label>
                            <select name="pratihari_id" id="pratihari_id" class="form-select select2">
                                <option value="">All Pratihar</option>
                                @foreach($pratiharis as $id => $name)
                                    <option value="{{ $id }}" {{ request('pratihari_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Seba</label>
                            <select name="seba_id" id="seba_id" class="form-select select2">
                                <option value="">All Seba</option>
                                @foreach($sebas as $id => $name)
                                    <option value="{{ $id }}" {{ request('seba_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small">Date Range (from - to)</label>
                            <div class="d-flex gap-2">
                                <input type="text" name="date_from" id="date_from" class="form-control flatpickr" placeholder="YYYY-MM-DD" value="{{ request('date_from') }}">
                                <input type="text" name="date_to" id="date_to" class="form-control flatpickr" placeholder="YYYY-MM-DD" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small">Search</label>
                            <input type="text" class="form-control" name="search" placeholder="Pratihari / Beddha IDs / year" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-1 text-end">
                            <button type="submit" class="btn btn-primary w-100">Apply</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE + PAGINATION --}}
            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pratihari</th>
                                <th>Seba</th>
                                <th>Beddha(s)</th>
                                <th>Year</th>
                                <th>Assigned By</th>
                                <th>Date / Time (Asia/Kolkata)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
                                        <div class="small fw-bold">{{ $row->pratihari_name ?? ('#'.$row->pratihari_id) }}</div>
                                        <div class="small-muted">ID: {{ $row->pratihari_id }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $row->seba_name ?? ('#'.$row->seba_id) }}</div>
                                        <div class="small-muted">Seba ID: {{ $row->seba_id }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $bids = array_filter(array_map('trim', explode(',', (string)$row->beddha_id)));
                                        @endphp

                                        @if(empty($bids))
                                            <span class="small-muted">—</span>
                                        @else
                                            <div class="d-flex flex-column gap-1">
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($bids as $bid)
                                                        <span class="badge-beddha-id">#{{ $bid }}</span>
                                                    @endforeach
                                                </div>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($bids as $bid)
                                                        @php
                                                            $label = $beddhaNames[$bid] ?? $bid;
                                                        @endphp
                                                        <span class="badge badge-beddha" title="ID {{ $bid }}">
                                                            {{ $label }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $row->year ?? '—' }}</td>
                                    <td>{{ $row->admin_name ?? $row->assigned_by }}</td>
                                    <td>
                                        @if(!empty($row->date_time))
                                            {{ \Carbon\Carbon::parse($row->date_time)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                        @else
                                            <span class="small-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary show-transaction" data-id="{{ $row->id }}">
                                            <i class="fa-regular fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center small-muted py-3">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="small-muted">
                        Showing {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }} records
                    </div>
                    <div>
                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for details -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="transaction-header w-100 d-flex justify-content-between align-items-center">
            <h5 class="modal-title mb-0">Transaction Details</h5>
            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close">
                Close
            </button>
        </div>
      </div>
      <div class="modal-body" id="transactionModalBody">
        <div class="text-center small-muted py-3">Loading…</div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('.select2').select2({ width: '100%' });
            $('.flatpickr').flatpickr({ dateFormat: 'Y-m-d' });

            // View transaction details
            $(document).on('click', '.show-transaction', function () {
                const id = $(this).data('id');
                $('#transactionModalBody').html('<div class="text-center small-muted py-3">Loading…</div>');
                $('#transactionModal').modal('show');

                $.ajax({
                    url: "{{ url('admin/pratihari-seba-transactions') }}/" + id,
                    method: 'GET',
                    success: function (res) {
                        if (!res.transaction) {
                            $('#transactionModalBody').html('<div class="text-danger py-3 text-center">Not found</div>');
                            return;
                        }

                        const t = res.transaction;

                        // Prepare beddha IDs badges
                        let idsHtml = '—';
                        if (t.beddha_id) {
                            const ids = t.beddha_id.split(',')
                                .map(s => s.trim())
                                .filter(Boolean);

                            if (ids.length) {
                                idsHtml = ids.map(id =>
                                    `<span class="badge-beddha-id me-1 mb-1">#${id}</span>`
                                ).join(' ');
                            }
                        }

                        // Prepare beddha Names badges (resolved)
                        let namesHtml = '—';
                        if (res.beddha_names && res.beddha_names.length) {
                            namesHtml = res.beddha_names.map(name =>
                                `<span class="badge badge-beddha me-1 mb-1">${name}</span>`
                            ).join(' ');
                        }

                        let html = '<dl class="row mt-3">';
                        html += `<dt class="col-sm-4">Transaction ID</dt><dd class="col-sm-8">${t.id}</dd>`;
                        html += `<dt class="col-sm-4">Pratihari</dt><dd class="col-sm-8">${t.pratihari_name ?? ('#' + t.pratihari_id)}</dd>`;
                        html += `<dt class="col-sm-4">Seba</dt><dd class="col-sm-8">${t.seba_name ?? ('#' + t.seba_id)}</dd>`;
                        html += `<dt class="col-sm-4">Beddha IDs</dt><dd class="col-sm-8">${idsHtml}</dd>`;
                        html += `<dt class="col-sm-4">Beddha Names</dt><dd class="col-sm-8">${namesHtml}</dd>`;
                        html += `<dt class="col-sm-4">Year</dt><dd class="col-sm-8">${t.year ?? '—'}</dd>`;
                        html += `<dt class="col-sm-4">Assigned By (Admin)</dt><dd class="col-sm-8">${t.assigned_by ?? '—'}</dd>`;
                        html += `<dt class="col-sm-4">Date / Time</dt><dd class="col-sm-8">${t.date_time ?? '—'}</dd>`;
                        html += `</dl>`;

                        $('#transactionModalBody').html(html);
                    },
                    error: function () {
                        $('#transactionModalBody').html('<div class="text-danger py-3 text-center">Error loading details.</div>');
                    }
                });
            });
        });
    </script>
@endsection

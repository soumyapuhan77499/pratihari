@extends('layouts.app')

@section('styles')
    <!-- Select2 + Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        :root {
            --brand-a: #7c3aed;
            --brand-b: #06b6d4;
            --ink: #0b1220;
            --muted: #64748b;
            --chip-bg: #f1f5f9;
        }

        .card { border-radius: 12px; border: 1px solid #e5e7eb; }
        .filter-bar { padding: 12px; border-radius: 10px; border:1px solid #e6edf3; background:#fff; }
        .table-responsive { max-height: 68vh; overflow: auto; }
        .small-muted { font-size: .85rem; color:#65748b; }

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
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mt-3">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0">Admin — Pratihari Seba Assign Transactions</h5>

                <div>
                    <a href="{{ route('pratihariSebaTransactions.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fa-regular fa-file-csv me-1"></i> Export CSV
                    </a>
                </div>
            </div>

            <form id="filterForm" method="GET" action="{{ route('pratihariSebaTransactions.index') }}">
                <div class="filter-bar mb-3">
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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
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
                            <tr><td colspan="8" class="text-center small-muted">No transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="small-muted">
                    Showing {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }} records
                </div>
                <div>{{ $rows->links() }}</div>
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

@extends('layouts.app')

@section('styles')
    <!-- Select2 + Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .card { border-radius: 12px; }
        .filter-bar { padding: 12px; border-radius: 10px; border:1px solid #e6edf3; background:#fff; }
        .table-responsive { max-height: 68vh; overflow: auto; }
        .small-muted { font-size: .85rem; color:#65748b; }
        .badge-beddha { background: linear-gradient(90deg,#7c3aed,#06b6d4); color:#fff; font-weight:700; padding:.25rem .5rem; border-radius:999px; }
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
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($bids as $bid)
                                                <span class="badge badge-beddha">{{ $bid }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $row->year ?? '—' }}</td>
                                <td>{{ $row->admin_name ?? $row->assigned_by }}</td>
                                <td>
                                    @if(!empty($row->date_time))
                                        {{ \Carbon\Carbon::parse($row->date_time)->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}
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
                <div class="small-muted">Showing {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }} records</div>
                <div>{{ $rows->links() }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for details -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transaction Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="transactionModalBody">
        <div class="text-center small-muted">Loading…</div>
      </div>
      <div class="modal-footer">
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
                $('#transactionModalBody').html('<div class="text-center small-muted">Loading…</div>');
                $('#transactionModal').modal('show');

                $.ajax({
                    url: "{{ url('admin/pratihari-seba-transactions') }}/" + id,
                    method: 'GET',
                    success: function (res) {
                        if (!res.transaction) {
                            $('#transactionModalBody').html('<div class="text-danger">Not found</div>');
                            return;
                        }

                        let html = '<dl class="row">';
                        html += `<dt class="col-sm-4">Transaction ID</dt><dd class="col-sm-8">${res.transaction.id}</dd>`;
                        html += `<dt class="col-sm-4">Pratihari</dt><dd class="col-sm-8">${res.transaction.pratihari_name ?? ('#' + res.transaction.pratihari_id)}</dd>`;
                        html += `<dt class="col-sm-4">Seba</dt><dd class="col-sm-8">${res.transaction.seba_name ?? ('#' + res.transaction.seba_id)}</dd>`;
                        html += `<dt class="col-sm-4">Beddha IDs</dt><dd class="col-sm-8">${res.transaction.beddha_id || '—'}</dd>`;
                        html += `<dt class="col-sm-4">Beddha Names (resolved)</dt><dd class="col-sm-8">${(res.beddha_names && res.beddha_names.length) ? res.beddha_names.join(', ') : '—'}</dd>`;
                        html += `<dt class="col-sm-4">Year</dt><dd class="col-sm-8">${res.transaction.year ?? '—'}</dd>`;
                        html += `<dt class="col-sm-4">Assigned By (Admin)</dt><dd class="col-sm-8">${res.transaction.assigned_by ?? '—'}</dd>`;
                        html += `<dt class="col-sm-4">Date / Time</dt><dd class="col-sm-8">${res.transaction.date_time ?? '—'}</dd>`;
                        html += `</dl>`;

                        $('#transactionModalBody').html(html);
                    },
                    error: function () {
                        $('#transactionModalBody').html('<div class="text-danger">Error loading details.</div>');
                    }
                });
            });
        });
    </script>
@endsection

@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-group {
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }

        .form-control {
            padding-left: 35px;
        }

        .beddha-section {
            margin-top: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .beddha-group-row {
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }

        .beddha-items {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header {
            background: linear-gradient(90deg, #007bff 0%, #6a11cb 100%);
            color: rgb(240, 242, 248);
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .custom-gradient-btn {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            color: white;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .custom-gradient-btn:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            transform: translateY(-2px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }

        .beddha-items .form-check-label {
            margin-left: 14px;
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Resize checkboxes */
        .beddha-items .form-check-input {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }

        /* Optional: space between checkbox and label */
        .beddha-items .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="errorMessage">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-header">Pratihari seba assign</div>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.savePratihariAssignSeba') }}" id="assignSebaForm">
                        @csrf

                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label for="pratihari_id">Select Pratihari</label>
                                <select name="pratihari_id" id="pratihari_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">-- Select Pratihari --</option>
                                    @foreach ($pratiharis as $pratihari_id_option => $name)
                                        <option value="{{ $pratihari_id_option }}"
                                            {{ request('pratihari_id') == $pratihari_id_option ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> <!-- ✅ FIX: Closing div tag added -->
                            <div class="col-md-6">
                                <label for="year" class="form-label">Year</label>
                                <select class="form-select @error('year') is-invalid @enderror" id="year"
                                    name="year" required>
                                    <option value="">Select Year</option>
                                    @for ($y = date('Y'); $y >= 2000; $y--)
                                        @php
                                            $display = $y . '-' . ($y + 1);
                                        @endphp
                                        <option value="{{ $display }}"
                                            {{ request('year') == $display ? 'selected' : '' }}>
                                            {{ $display }}
                                        </option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if (request('pratihari_id') && request('year'))
                            <input type="hidden" name="pratihari_id" value="{{ request('pratihari_id') }}">
                            <input type="hidden" name="year" value="{{ request('year') }}">

                            <div class="beddha-section mt-4">
                                <label class="section-title">📜 Assign Beddha to Seba</label>
                                <div class="checkbox-list" id="beddha_list">
                                    @foreach ($sebas as $seba)
                                        <input type="hidden" name="seba_id[]" value="{{ $seba->id }}">

                                        <div class="beddha-group-row mb-4" id="beddha_group_{{ $seba->id }}">
                                            <strong class="d-block mb-2">{{ $seba->seba_name }}:</strong>
                                            <div class="beddha-items d-flex flex-wrap gap-3">
                                                @foreach ($beddhas[$seba->id] ?? [] as $beddha)
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="beddha_id[{{ $seba->id }}][]"
                                                            value="{{ $beddha->id }}"
                                                            id="beddha_{{ $seba->id }}_{{ $beddha->id }}"
                                                            {{ in_array($beddha->id, $assignedBeddhas[$seba->id] ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="beddha_{{ $seba->id }}_{{ $beddha->id }}">
                                                            {{ $beddha->beddha_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-lg mt-4 w-50 custom-gradient-btn text-white">
                                    <i class="fa fa-save"></i> Submit
                                </button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Flash message auto hide
            setTimeout(() => $('#successMessage')?.addClass('d-none'), 5000);
            setTimeout(() => $('#errorMessage')?.addClass('d-none'), 5000);

            // Initialize Select2
            $('#pratihari_id').select2({
                placeholder: '-- Select Pratihari --',
                allowClear: true,
                width: '100%'
            });

            // Redirect on change
            $('#pratihari_id, #year').change(function() {
                let pratihari_id = $('#pratihari_id').val();
                let year = $('#year').val();

                if (pratihari_id && year) {
                    window.location.href =
                        `{{ route('admin.PratihariSebaAssign') }}?pratihari_id=${pratihari_id}&year=${year}`;
                }
            });
        });
    </script>
@endsection

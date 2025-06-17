@extends('layouts.app')

@section('styles')
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add Designation</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.designation.store') }}" method="POST">
                            @csrf

                            {{-- Year --}}
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <select class="form-select @error('year') is-invalid @enderror" id="year"
                                    name="year" required>
                                    <option value="">Select Year</option>
                                    @for ($y = date('Y'); $y >= 2000; $y--)
                                        <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Pratihari --}}
                            <div class="mb-3">
                                <label for="pratihari_id" class="form-label">Pratihari</label>
                                <select class="form-select select2 @error('pratihari_id') is-invalid @enderror"
                                    id="pratihari_id" name="pratihari_id" required>
                                    <option value="">Select Pratihari</option>
                                    @foreach ($profiles as $pratihari)
                                        <option value="{{ $pratihari->id }}"
                                            {{ old('pratihari_id') == $pratihari->id ? 'selected' : '' }}>
                                            {{ $pratihari->first_name . ' ' . ($pratihari->middle_name ?? '') . ' ' . $pratihari->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pratihari_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Designation --}}
                            <div class="mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                    id="designation" name="designation" value="{{ old('designation') }}"
                                    placeholder="Enter Designation" required>
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" class="btn btn-primary">Add Designation</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select Pratihari',
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endsection

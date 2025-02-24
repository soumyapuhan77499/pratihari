@extends('layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

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

        .card-header {
            background: linear-gradient(135deg, #f8f19e, #dcf809);
            color: rgb(51, 101, 251);
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
                <div class="card-header">🛕 Nijoga Seba Assign</div>
                <div class="card-body">
                    <form action="{{ route('admin.saveNijogaSeba') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-2" style="margin-top: 27px">
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#nijogaModal">
                                    <i class="fas fa-plus-circle"></i> Add Nijoga
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nijoga_type"><i class="fas fa-list"></i> Nijoga Category</label>
                                    <select class="form-control" id="nijoga_type" name="nijoga_type" required>
                                        <option value="">Select Nijoga</option>
                                        @foreach($nijogas as $nijoga)
                                            <option value="{{ $nijoga->id }}">{{ $nijoga->nijoga_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2"  style="margin-top: 27px">
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#sebaModal">
                                    <i class="fas fa-plus-circle"></i> Add Seba
                                </button>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="seba_name"><i class="fas fa-hand-holding-heart"></i> Seba Name</label>
                                    <select class="form-control select2" id="seba_name" name="seba_name[]"  multiple="multiple" required>
                                        @foreach($sebas as $seba)
                                            <option value="{{ $seba->id }}">{{ $seba->seba_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-lg mt-3 w-50 custom-gradient-btn" style="color: white">
                                    <i class="fa fa-save"></i> Submit
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sebaModal" tabindex="-1" aria-labelledby="sebaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-hand-holding-heart"></i> Add Seba</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.storeSeba') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="seba_name"><i class="fas fa-tag"></i> Seba Name</label>
                            <input type="text" class="form-control" id="seba_name" name="seba_name" required placeholder="Enter Seba Name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save Seba</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Nijoga Modal -->
<div class="modal fade" id="nijogaModal" tabindex="-1" aria-labelledby="nijogaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nijogaModalLabel">
                    <i class="fas fa-list"></i> Add Nijoga
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.storeNijoga') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nijoga_name"><i class="fas fa-tag"></i> Nijoga Name</label>
                        <input type="text" class="form-control" id="nijoga_name" name="nijoga_name" required placeholder="Enter Nijoga Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Nijoga</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => document.getElementById("successMessage")?.classList.add("d-none"), 5000);
            setTimeout(() => document.getElementById("errorMessage")?.classList.add("d-none"), 5000);
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>

    <!-- Internal Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <!--Internal  Form-elements js-->
    <script src="{{ asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>

@endsection

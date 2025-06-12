@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.2rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            font-weight: bold;
            font-size: 1rem;
            text-transform: uppercase;
            background-color: rgba(0, 0, 0, 0.05);
            border-bottom: 2px solid #dee2e6;
        }

        .card-title {
            font-size: 25px;
            font-weight: bold;
            color: white;
        }

        .card-body p {
            margin: 0.2rem 0;
            color: white;
        }

        /* Specific background colors with contrast text */
        .bg-primary {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
            color: #fff !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        @media (max-width: 768px) {
            .card-title {
                font-size: 1.5rem;
            }

            .card-header {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2 class="mb-4">Pratihari Admin Dashboard</h2>
        <div class="row">
            <!-- Today's Registrations -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'today') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-primary mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-plus-fill me-2"></i>Today's Registrations
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $todayCount }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Incomplete Profiles -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'incomplete') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-warning mb-3">
                        <div class="card-header">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>Incomplete Profiles
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $incompleteProfiles }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Active Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'approved') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-success mb-3">
                        <div class="card-header">
                            <i class="bi bi-people-fill me-2"></i>Active Users
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalActiveUsers }}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Rejected Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.pratihari.filterUsers', 'rejected') }}" style="text-decoration:none;">
                    <div class="card text-dark bg-danger mb-3">
                        <div class="card-header">
                            <i class="bi bi-person-x-fill me-2"></i>Rejected Users
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $rejectedUsers }}</h5>
                        </div>
                    </div>
                </a>
            </div>


        </div>

    </div>
@endsection

@section('scripts')
@endsection

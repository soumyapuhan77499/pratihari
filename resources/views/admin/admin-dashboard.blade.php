@extends('layouts.app')

@section('styles')
    
@endsection

@section('content')
    <div class="container">
    <h2 class="mb-4">Pratihari Admin Dashboard</h2>
    <div class="row">
        <!-- Today Registrations -->
        <div class="col-md-3">
            <div class="card text-black bg-primary mb-3">
                <div class="card-header">Today's Registrations</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $todayCount }}</h5>
                </div>
            </div>
        </div>

        <!-- Incomplete Profiles -->
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Incomplete Profiles</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $incompleteProfiles }}</h5>
                </div>
            </div>
        </div>

        <!-- Total Active Users -->
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalActiveUsers }}</h5>
                </div>
            </div>
        </div>

        <!-- My Profile Completion -->
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">My Profile Completion</div>
                <div class="card-body">
                    @if(!empty($profileStatus))
                        <p><strong>Filled:</strong> {{ implode(', ', $profileStatus['filled']) }}</p>
                        <p><strong>Missing:</strong> {{ implode(', ', $profileStatus['empty']) }}</p>
                    @else
                        <p>Please log in to view.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
   
@endsection

@extends('layouts.app')

@section('styles')
<style>
    body {
        background-color: #f0f8ff;
        font-family: 'Arial', sans-serif;
    }
    .dashboard-card {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: #0d0c0c;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .dashboard-card h3 {
        font-size: 28px;
        font-weight: bold;
    }
    .dashboard-card h4 {
        font-size: 24px;
        font-weight: bold;
        margin-top: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    .dashboard-card p {
        font-size: 18px;
        margin-bottom: 10px;
    }
    .welcome-icon {
        font-size: 50px;
        color: #ffeb3b;
        margin-bottom: 10px;
    }
    .card-header {
        background-color: rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid #ffeb3b;
    }
    .card-body {
        padding: 40px;
    }
</style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="card dashboard-card shadow-lg">
        <div class="card-header text-center">
            <h3>🌟 JAY JAGANNATH 🌟</h3>
        </div>
        <div class="card-body text-center">
            <div class="welcome-icon">👋</div>
            <h4>Welcome to the Super Admin Panel!</h4>
            <p class="lead">Namaskar, <strong>{{ Auth::guard('super_admin')->user()->name ?? 'Super Admin' }}</strong></p>
            <hr class="bg-light">
            <p class="small">Tip: Always logout after completing your work for better security.</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Add any custom scripts if needed -->
@endsection

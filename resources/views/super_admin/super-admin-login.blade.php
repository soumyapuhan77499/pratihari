@extends('layouts.custom-app')

@section('styles')
<title>Admin Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.9.0/sweetalert2.min.css">
<style>
    .bg-primary {
        background-image: linear-gradient(170deg, #FBAB7E 0%, #F7CE68 100%);
        }

    .card-sigin-main {
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        padding: 30px;
    }

    .form-group input {
        height: 50px;
        border-radius: 8px;
    }
    .btn-primary {
        background: linear-gradient(to right, #4e54c8, #8f94fb);
        border: none;
        height: 50px;
        border-radius: 10px;
        font-weight: 500;
        margin-top: 20px;
        width: 100%;
    }


    .edit-btn {
        cursor: pointer;
        color: #2575fc;
        text-decoration: underline;
        font-size: 14px;
    }
</style>
@endsection

@section('class')
<div class="bg-primary">
@endsection
@section('content')

    <div class="page-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-sm-10 mx-auto my-auto">
                    <div class="card-sigin-main">
                        <div class="text-center">
                            <a href="#"><img src="{{ asset('assets/img/brand/logo.jpg') }}" class=""
                                    style = "height: 170px;width: 200px" alt="logo"></a>
                        </div>
                        <div class="main-signup-header">
                            <h3 class="text-center mb-3">Super admin login</h3>

                            <!-- OTP Verification Form -->
                            <form action="{{ route('superadmin.login.submit') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <div class="d-flex align-items-center">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter your email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="d-flex align-items-center">

                                    <input type="password" name="password" class="form-control" required
                                    placeholder="Enter your password">
                                </div>
                            </div>

                                <button type="submit" class="btn" style="background-image: linear-gradient(170deg, #FBAB7E 0%, #F7CE68 100%);width: 100%;color: white;font-weight: bold">Verify OTP</button>
                            </form>

                            
                            <div class="main-signup-footer mt-3 text-center">
                                <p>Admin Login<a href="{{ route('admin.AdminLogin') }}">  Login</a></p>
                            </div>

                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif
</script>
@endsection


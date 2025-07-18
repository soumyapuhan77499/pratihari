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
            width: 100%;
            height: 50px;
            border-radius: 8px;
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
                                <div class="text-center">
                                    <a href="#"><img src="{{ asset('assets/img/brand/nijoga-logo.jpg') }}"
                                            style = "height: 170px;width: 280px" alt="logo"></a>
                                </div>
                            </div>
                            <div class="main-signup-header">
                                <h3 class="text-center mb-3">Login to continue</h3>

                                @if (session('otp_sent'))
                                    <!-- OTP Verification Form -->
                                    <form action="{{ route('admin.verifyOtp') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" name="mobile_no" class="form-control me-2"
                                                value="{{ session('otp_phone') }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Enter OTP</label>
                                            <input type="text" class="form-control" name="otp"
                                                placeholder="Enter OTP" required>
                                        </div>

                                        <button type="submit" class="btn btn-block btn-warning font-weight-bold">
                                            Verify OTP
                                        </button>
                                    </form>
                                @else
                                    <!-- Phone Input Form -->
                                    <form action="{{ route('admin.sendOtp') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Enter Your Phone Number</label>
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="Enter your phone number" required>
                                        </div>
                                        <button type="submit" class="btn btn-block btn-warning font-weight-bold">
                                            Send OTP
                                        </button>
                                    </form>
                                @endif

                                <div class="main-signup-footer mt-3 text-center">
                                    <p>Super Admin <a href="{{ route('superadmin.login') }}">Login</a></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
       <!-- Include SweetAlert2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.9.0/sweetalert2.all.min.js"></script>

<script>
    // Show SweetAlert for flash messages
    @if (session('message'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('message')),
            timer: 3000,
            showConfirmButton: false
        });
    @elseif (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Optional: enable phone editing in OTP screen
    function enablePhoneEdit() {
        let phoneInput = document.getElementById("phone");
        phoneInput.removeAttribute("readonly");
        phoneInput.focus();
    }
</script>


        <!-- OneSignal Push Notification -->
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                OneSignal.push(function() {
                    OneSignal.getUserId().then(function(playerId) {
                        if (playerId) {
                            document.getElementById('onesignal_player_id').value = playerId;
                        }
                    }).catch(function(error) {
                        console.error("OneSignal Player ID Error:", error);
                    });
                });
            });
        </script>
    @endsection

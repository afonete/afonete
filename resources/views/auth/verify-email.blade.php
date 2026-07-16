<?php

use Illuminate\Support\Facades\Auth;

use App\Models\User;

$user = Auth::user();
$email = $user->email;
$requested = $user->has_request;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Bifonex Template">
    <meta name="keywords" content="Bifonex, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bifonex </title>
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('assets/front/css/jquery-ui.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/front/css/booststrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/font-awesome.min.css')}}">
    <link rel="icon" href="{{asset('assets/front/img/log1.png')}}">

</head>

<body class="fd-flex justify-content-center align-items-center">

    @if($requested=='requested')

    <div class="fd-flex justify-content-around align-self-center">

        <div class="text-center" style="width: 80%">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    REQUEST TO JOIN STATUS

                </h5>

                <p class="card-text"> Your request to join Bifonex on <u>{{$email}}</u> has been accepted and it is on waiting. You will receive acceptance email once you will be accepted. This action takes 2hr-48hr normal.
            </div>


            <div class="mt-4 d-flex justify-content-center">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="ml-3" type="submit" class="border border-1 text-md text-gray-600 hover:text-blue-900">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>

    </div>


    @else


    <div class="fd-flex justify-content-around align-self-center">

        <div class="text-center" style="width: 80%; max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <div class="card-body">
                <h3 class="card-title mb-4 font-weight-bold text-primary" style="font-size: 1.4rem; color: #4f46e5;">
                    <i class="fas fa-envelope-open-text mr-1"></i> Email Verification PIN
                </h3>

                @if(session('success'))
                    <div class="alert alert-success text-sm mb-3">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger text-sm mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-sm mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <p class="card-text text-sm text-gray-600 mb-4 leading-relaxed">
                    Thanks for signing up! We have sent a **6-digit Activation PIN** to your email address <u>{{ $email }}</u>. 
                    Please enter the code below to instantly verify and activate your account.
                </p>

                {{-- Custom PIN Verification Form --}}
                <form method="POST" action="{{ route('verification.verify-pin') }}" class="mb-4">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="text" name="pin" max-length="6" required 
                               class="form-control text-center font-weight-bold font-mono text-lg py-2.5 mx-auto w-48" 
                               placeholder="******" 
                               style="letter-spacing: 6px; font-size: 1.5rem; border-radius: 8px; border: 1px solid #cbd5e1;"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                    </div>
                    <button class="btn btn-primary btn-block font-weight-bold py-2" type="submit" style="border-radius: 8px;">
                        <i class="fas fa-check-double mr-1"></i> Verify &amp; Activate Account
                    </button>
                </form>

            </div>

            <div class="mt-4 d-flex justify-content-center align-items-center gap-3 border-top pt-3" style="border-color: #f1f5f9;">
                <form method="POST" action="{{ route('verification.resend-pin') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary font-weight-bold" type="submit" style="border-radius: 6px;">
                        <i class="fas fa-redo-alt mr-1"></i> Resend PIN
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-danger font-weight-bold" type="submit" style="border-radius: 6px;">
                        <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                    </button>
                </form>
            </div>
        </div>

    </div>
    @endif

    <!-- Js Plugins -->
    <script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/front/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/front/js/main.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery-migrate-3.0.1.min.js')}}"></script>
    <script src="{{asset('assets/front/js/booststrap.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery.stellar.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/front/js/jquery.animateNumber.min.js')}}"></script>
    <script src="{{asset('assets/front/js/aos.js')}}"></script>
    <script src="{{asset('assets/front/js/mainj.js')}}"></script>
    <script src="{{asset('assets/front/js/jquerry.min.js')}}"></script>
    <script src="{{asset('assets/front/js/slick.min.js')}}"></script>
    <script src="{{asset('assets/front/js/mainn.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>

</body>

</html>
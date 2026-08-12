<div class="wrapper">
    @include('user.user-dashboard-base')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

   <style>
   body {
     font-family: "Poppins", sans-serif;
   }
   </style>

    <div class="content-wrapper">
       <div class="container-fluid py-5">
           <div class="row justify-content-center">
               <div class="col-md-8 col-lg-6">

                   {{-- Alerts --}}
                   @if(session('message'))
                       <div class="alert alert-danger text-center font-weight-bold mb-4 shadow-sm" role="alert">
                           <i class="fa fa-exclamation-circle me-2"></i>{{ session('message') }}
                       </div>
                   @endif

                   @if(session('success'))
                       <div class="alert alert-success text-center font-weight-bold mb-4 shadow-sm" role="alert">
                           <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                       </div>
                   @endif

                   {{-- Restriction & Activation Card --}}
                   <div class="card shadow-lg border-0" style="background:#1F2937; color:#fff; border-radius:12px;">
                       <div class="card-header bg-dark text-white text-center py-3 border-bottom border-secondary">
                           <h3 class="text-warning font-weight-bold mb-1"><i class="fa fa-lock me-2"></i>Account Activation Required</h3>
                           <span class="text-muted small">Enter your activation code below to unlock full dashboard access.</span>
                       </div>
                       
                       <div class="card-body p-4 text-center">
                           <p class="text-light mb-4">
                               Your account currently requires an active package or leader code. Enter your activation code below to activate your account. Both <strong>Package Activation Codes</strong> and <strong>Team Leader Codes</strong> are accepted.
                           </p>

                           {{-- Activation Form --}}
                           <form action="{{ route('user.dashboard.validate') }}" method="POST" class="mb-4">
                               @csrf
                               <div class="form-group mb-3">
                                   <label for="activation_code" class="form-label text-warning font-weight-bold">Enter 17-Character Activation Code:</label>
                                   <input type="text" id="activation_code" name="code" class="form-control form-control-lg text-center font-weight-bold text-warning bg-dark border-secondary" 
                                          placeholder="e.g. FOM-A8B9C3D2E4F5G or Team Leader Code" required style="letter-spacing: 1px;">
                               </div>

                               <button type="submit" class="btn btn-warning btn-lg font-weight-bold px-5 py-2.5 text-dark shadow-sm w-100">
                                   <i class="fa fa-bolt me-2"></i>Activate Account Now
                               </button>
                           </form>

                           <hr class="border-secondary my-4">

                           <div class="d-flex justify-content-center gap-2 flex-wrap">
                               <a href="{{ route('investment-package') }}" class="btn btn-primary font-weight-bold px-4">
                                   <i class="fa fa-shopping-cart me-1"></i>Buy Package Codes
                               </a>
                               <a href="javascript:history.back()" class="btn btn-outline-light font-weight-bold px-4">
                                   <i class="fa fa-arrow-left me-1"></i>Go Back
                               </a>
                           </div>
                       </div>
                   </div>

               </div>
           </div>
       </div>
    </div>
</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

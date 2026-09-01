<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>Activate Account</title>

    {{-- §94: dedicated activation-code page — the ONLY thing here is the
         code input, so leaders coming from /team-leader/pending-approval
         land directly on the field instead of scrolling past packages
         on /user/venture-package. Posts to the SAME route('validate')
         endpoint (ActivationController::g_upgrade) as the venture page. --}}
    <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="w-100" style="max-width: 480px;">

                @if(session('message'))
                    <div class="alert alert-info shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-info-circle mr-1"></i> {{ session('message') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header text-center border-0 py-4" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(251, 191, 36, 0.15); border: 2px solid rgba(251, 191, 36, 0.4);">
                            <i class="fas fa-key fa-2x text-warning"></i>
                        </div>
                        <h4 class="text-white font-weight-bold mb-1">Activate Your Account</h4>
                        <p class="text-white-50 mb-0" style="font-size: 0.85rem;">Paste your activation code below and submit.</p>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-white">
                        <form action="{{ route('validate') }}" method="POST">
                            @csrf
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-key text-warning mr-1"></i> Activation Code
                                </label>
                                <input type="text" name="code" required autofocus autocomplete="off"
                                       placeholder="Paste your activation code here"
                                       class="form-control text-center font-weight-bold"
                                       style="border-radius: 10px; font-family: monospace; font-size: 1.05rem; letter-spacing: 1.5px; padding: 14px;">
                            </div>
                            <button type="submit" class="btn btn-block text-white font-weight-bold py-3"
                                    style="border-radius: 10px; background: linear-gradient(135deg, #16a34a, #15803d); border: none; font-size: 0.95rem;">
                                <i class="fas fa-check-circle mr-2"></i> Activate My Account
                            </button>
                        </form>
                        <p class="text-muted text-center mt-4 mb-0" style="font-size: 0.78rem; line-height: 1.5;">
                            <i class="fas fa-shield-alt text-success mr-1"></i>
                            Works for Team Leader and package activation codes.
                            Your code was shown on your approval page — copy it there, then paste it here.
                        </p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ url('/user/venture-package') }}" class="text-muted" style="font-size: 0.82rem;">
                        <i class="fas fa-arrow-left mr-1"></i> Looking for packages instead? Go to Venture Packages
                    </a>
                </div>
            </div>
        </div>
        @include('user.footer')
    </div>
</div>

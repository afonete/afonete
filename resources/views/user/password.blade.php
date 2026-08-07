<div class="wrapper">
@include('user.user-dashboard-base')

<div class="content-wrapper">
    <div class="container-fluid py-4">

        {{-- Unified Profile Navigation Tabs --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light p-3">
                <ul class="nav nav-pills gap-2">
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-address-card mr-1"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('password.show') }}" class="nav-link active font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-lock mr-1"></i> Password &amp; Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('wallet') }}" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-wallet mr-1"></i> Wallet Address
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.kyc') }}" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-id-card mr-1"></i> KYC Verification
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        @if(session('status'))
            <div class="alert alert-success shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3" style="border-radius: 8px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3" style="border-radius: 8px;">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3" style="border-radius: 8px;">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <div class="row">
            {{-- Card 1: Login Password Change --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-slate-900 text-white font-weight-bold py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <i class="fas fa-key text-warning mr-2"></i> Change Login Password
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Current Login Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" required placeholder="Enter current login password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">New Login Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" required placeholder="Enter new password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Confirm New Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" required placeholder="Re-enter new password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Email Verification PIN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="pin" required placeholder="Enter 6-digit PIN" class="form-control" style="border-radius: 8px 0 0 8px;">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary font-weight-bold text-xs send-pw-pin-btn" style="border-radius: 0 8px 8px 0;">
                                            Send PIN
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">Click 'Send PIN' to receive a verification code in your email.</small>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-primary font-weight-bold btn-block py-2" style="border-radius: 8px;">
                                    <i class="fas fa-save mr-1"></i> Update Login Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 2: Second Transaction Password --}}
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-slate-900 text-white font-weight-bold py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <i class="fas fa-user-shield text-info mr-2"></i> Second Transaction Password
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">This second password is required when performing withdrawals, transfers, token swaps, and internal exchanges.</p>

                        <form action="{{ route('transaction-password.update') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Current Login Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" required placeholder="Enter current login password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">New Second Transaction Password <span class="text-danger">*</span></label>
                                <input type="password" name="transaction_password" required placeholder="Enter new transaction password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Confirm Transaction Password <span class="text-danger">*</span></label>
                                <input type="password" name="transaction_password_confirmation" required placeholder="Re-enter transaction password" class="form-control" style="border-radius: 8px;">
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark small">Email Verification PIN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="pin" required placeholder="Enter 6-digit PIN" class="form-control" style="border-radius: 8px 0 0 8px;">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary font-weight-bold text-xs send-pw-pin-btn" style="border-radius: 0 8px 8px 0;">
                                            Send PIN
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">Click 'Send PIN' to receive a verification code in your email.</small>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-info font-weight-bold btn-block py-2 text-white" style="border-radius: 8px; background-color: #0284c7; border: none;">
                                    <i class="fas fa-shield-alt mr-1"></i> Update Second Transaction Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('user.footer')
</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('.send-pw-pin-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.prop('disabled', true).text('Sending...');
        
        $.ajax({
            url: "{{ route('password.send-pin') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.ok) {
                    alert(response.message);
                    $btn.text('Sent!');
                    var countdown = 60;
                    var interval = setInterval(function() {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            $btn.prop('disabled', false).text('Send PIN');
                        } else {
                            $btn.text('Resend (' + countdown + 's)');
                        }
                    }, 1000);
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).text('Send PIN');
                }
            },
            error: function(xhr) {
                alert('Could not send PIN. Please try again later.');
                $btn.prop('disabled', false).text('Send PIN');
            }
        });
    });
});
</script>

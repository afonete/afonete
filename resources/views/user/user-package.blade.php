@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;

    $user = Auth::user();
    $name = $user->name ?? $user->user;
    $userr = $user->user;
    $email = $user->email;

    $highestUvpPackageAmount = $user ? $user->highestUvpPackageAmount() : 0.0;

    $smartBackUrl = url('/');
    if ($user) {
        $isSignedContract = ($user->contract === 'Signed');
        $isVerified       = ($user->email_verified_at !== null || $user->activation_status === 'verified' || $user->has_request === 'approved');
        $isFreeUser       = ($user->has_free_package === 'yes');
        $hasPaidPackage   = !empty($user->has_paid_package) && !in_array(strtolower(trim($user->has_paid_package)), ['no', 'standard', '']);

        if ($isSignedContract || $isVerified || $isFreeUser || $hasPaidPackage) {
            $smartBackUrl = route('user.dashboard');
        }
    }
@endphp

<div class="wrapper">
    @include('user.user-dashboard-base')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .pkg-container {
            width: 100%;
            padding: 1.5rem 1rem;
        }
        .dash-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.25s ease !important;
            background: #ffffff;
            color: #1e293b;
            overflow: hidden;
        }
        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
        .dash-header-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 16px;
        }
    </style>

    <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-fluid pkg-container max-w-7xl mx-auto">

            {{-- Top Navigation Header --}}
            <div class="dash-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ $smartBackUrl }}" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <div>
                        <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.25rem;">
                            <i class="fas fa-gem text-warning mr-2"></i> FC Packages &amp; Activation
                        </h4>
                        <small class="text-light opacity-90">Manage membership activations, FC packages, and enter activation codes.</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('user.venture') }}" class="btn btn-warning font-weight-bold text-dark px-3 py-2" style="border-radius: 8px;">
                        UVP Packages <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()" class="btn btn-danger font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        Logout <i class="fas fa-sign-out-alt ml-1"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- Session Flash Messages --}}
            @if(session('message'))
                <div class="alert alert-info text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    {{ session('message') }}
                </div>
            @endif

            {{-- FC Packages Grid --}}
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-box-open text-primary mr-2"></i> FC VIP Packages
                    </h5>
                </div>

                <div class="row">
                    @forelse($packages as $package)
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="dash-card h-100 p-4 d-flex flex-column justify-content-between text-center border">
                                <div>
                                    <div class="p-2 mb-3 rounded bg-gradient-dark text-white font-weight-bold" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 8px;">
                                        <span class="text-warning font-weight-bold text-uppercase" style="font-size: 0.95rem;">FC VIP Tier</span>
                                    </div>

                                    <h2 class="font-weight-bold text-primary mb-2" style="font-size: 1.8rem;">
                                        ${{ number_format($package->price, 2) }}
                                    </h2>

                                    <p class="text-muted small mb-4">
                                        Full FC VIP membership access with dedicated token rewards and ecosystem privileges.
                                    </p>
                                </div>

                                <div>
                                    <form action="{{ route('payment.directPackage') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="package_type" value="FC">
                                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                                        <input type="hidden" name="network" value="TRC-20">
                                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 13px;">
                                            <i class="fas fa-shopping-cart mr-1"></i> BUY NOW (${{ number_format($package->price, 0) }})
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2 text-slate-300"></i>
                            <p class="font-weight-bold mb-0">No FC packages found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Free Tier, Activation Code & Deposit Cards --}}
            <div class="row">
                {{-- Free / Standard --}}
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-light text-dark font-weight-bold border" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;">Free Tier</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Standard Account</h4>
                            <p class="text-muted small mb-3">Activate free dashboard access for regular exploration.</p>
                        </div>
                        <div>
                            <form action="{{ route('free') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px;">
                                    <i class="fas fa-user-check mr-1"></i> Activate Free Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Activation Code --}}
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-warning text-dark font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-key mr-1"></i> Activation Code</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Have a Code?</h4>
                            <p class="text-muted small mb-3">Redeem your package or Team Leader activation code.</p>
                        </div>
                        <div>
                            <form action="{{ route('validate') }}" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="code" placeholder="Enter Activation Code" required class="form-control text-center font-mono font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                                </div>
                                <button type="submit" class="btn btn-dark btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;">
                                    <i class="fas fa-bolt text-warning mr-1"></i> Submit Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Deposit Funds --}}
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-success text-white font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-wallet mr-1"></i> Deposit Funds</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Add Balance</h4>
                            <p class="text-muted small mb-3">Deposit USDT TRC-20 to purchase packages anytime.</p>
                        </div>
                        <div>
                            <a href="{{ route('user.manual-deposit') }}" class="btn btn-success btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px;">
                                <i class="fas fa-plus-circle mr-1"></i> Make Deposit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

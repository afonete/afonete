@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use App\Models\Adventures;
    use App\Models\FCpackage;

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
        .venture-container {
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
        <div class="container-fluid venture-container max-w-7xl mx-auto">

            {{-- Top Navigation Header --}}
            <div class="dash-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ $smartBackUrl }}" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <div>
                        <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.25rem;">
                            <i class="fas fa-microchip text-warning mr-2"></i> UVP AI License Packages
                        </h4>
                        <small class="text-light opacity-90">Select a UVP AI License package tier to activate daily yield ROI earnings.</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                        Deposit Balance: ${{ number_format($balance ?? 0, 2) }}
                    </span>
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

            {{-- UVP Package Selection Grid --}}
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-cubes text-primary mr-2"></i> Available UVP AI License Tiers
                    </h5>
                    @if($highestUvpPackageAmount > 0)
                        <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.8rem; border-radius: 6px;">
                            <i class="fas fa-lock mr-1"></i> Minimum Required Tier: ${{ number_format($highestUvpPackageAmount, 0) }}
                        </span>
                    @endif
                </div>

                <div class="row">
                    @forelse($Adventures as $venture)
                        @php
                            $minUvpTier   = $highestUvpPackageAmount ?? 0;
                            $effectiveMin = max($venture->min_amount, $minUvpTier);
                            $isBelowTier  = $minUvpTier > 0 && $venture->max_amount < $minUvpTier;
                        @endphp

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="dash-card h-100 p-3 d-flex flex-column justify-content-between text-center border">
                                <div>
                                    <div class="p-2 mb-2 rounded bg-dark text-white font-weight-bold" style="font-size: 0.85rem; border-radius: 8px;">
                                        <span class="text-warning d-block">{{ $venture->name ?: $venture->plan }}</span>
                                        <small class="text-light" style="font-size: 0.72rem;">Duration: {{ $venture->duration }} days</small>
                                    </div>

                                    <div class="my-3">
                                        <img src="{{ asset('image/ai-package.png') }}" style="width: 55px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" alt="AI Package">
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.8rem;">
                                            @if(!empty($venture->percentage_range))
                                                {{ $venture->percentage_range }}
                                            @else
                                                {{ $venture->percentage }}% Daily ROI
                                            @endif
                                        </span>
                                    </div>

                                    <div class="text-muted small mb-3">
                                        Range: <strong>${{ number_format($venture->min_amount, 0) }}</strong> – <strong>${{ number_format($venture->max_amount, 0) }}</strong><br>
                                        <span class="text-success font-weight-bold">Total Return: {{ $venture->total_return ?? '200' }}%</span>
                                    </div>
                                </div>

                                <div>
                                    @if($isBelowTier)
                                        <div class="alert alert-warning text-center p-2 mb-2" style="font-size: 11px; border-radius: 6px;">
                                            <i class="fas fa-lock mr-1"></i> Below Previous Tier (${{ number_format($minUvpTier, 0) }} Min)
                                        </div>
                                        <button class="btn btn-secondary btn-block font-weight-bold" disabled style="font-size: 11px; border-radius: 6px;">
                                            TIER RESTRICTED
                                        </button>
                                    @else
                                        <form action="{{ route('ventures') }}" method="POST" class="mt-2">
                                            @csrf
                                            <input type="hidden" name="venture" value="{{ $venture->id }}"/>
                                            <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                            <div class="form-group mb-2">
                                                <input type="number" name="amount_invest"
                                                       placeholder="Amount (${{ number_format($effectiveMin, 0) }} - ${{ number_format($venture->max_amount, 0) }})"
                                                       class="form-control form-control-sm text-center font-weight-bold"
                                                       min="{{ $effectiveMin }}"
                                                       max="{{ $venture->max_amount }}"
                                                       step="0.01"
                                                       required
                                                       style="border-radius: 6px; font-size: 12px;"/>
                                            </div>
                                            <button class="btn btn-dark btn-block font-weight-bold py-2" style="font-size: 12px; border-radius: 6px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;" type="submit">
                                                <i class="fas fa-bolt text-warning mr-1"></i> INVEST NOW
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-cubes fa-3x mb-3 text-slate-300"></i>
                            <p class="font-weight-bold">No UVP AI License packages available at this moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- FC Packages Grid --}}
            @if(isset($fc) && $fc->isNotEmpty())
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-box-open text-primary mr-2"></i> FC VIP Packages
                    </h5>
                </div>

                <div class="row">
                    @foreach($fc as $f)
                        <div class="col-12 col-sm-6 col-md-4 mb-3">
                            <div class="dash-card p-3 text-center border">
                                <span class="badge badge-danger text-white font-weight-bold uppercase mb-2" style="font-size: 0.75rem;">{{ $f->name }}</span>
                                <h4 class="font-weight-bold text-dark mb-3">${{ number_format($f->price, 2) }}</h4>
                                <form action="{{ route('ventures') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="venture" value="FC"/>
                                    <input type="hidden" name="package" value="{{ $f->id }}"/>
                                    <input type="hidden" name="routes" value="{{ $f->name }}"/>
                                    <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                    <input type="hidden" name="amount_invest" value="{{ $f->price }}"/>
                                    <button class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 6px; font-size: 12px;" type="submit">
                                        BUY NOW (${{ number_format($f->price, 0) }})
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Action Cards --}}
            <div class="row">
                <div class="col-12 col-md-4 mb-3">
                    <div class="dash-card p-3 text-center">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-code text-primary mr-1"></i> Have Activation Code?</h6>
                        <p class="text-muted small mb-2">Redeem an activation code to activate immediately.</p>
                        <a href="{{ route('user.dashboard.activate') }}" class="btn btn-sm btn-outline-primary font-weight-bold btn-block" style="border-radius: 6px;">
                            Enter Code →
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <div class="dash-card p-3 text-center">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-wallet text-success mr-1"></i> Deposit Funds</h6>
                        <p class="text-muted small mb-2">Add USDT TRC-20 deposit balance to purchase packages.</p>
                        <a href="{{ route('user.manual-deposit') }}" class="btn btn-sm btn-outline-success font-weight-bold btn-block" style="border-radius: 6px;">
                            Deposit Now →
                        </a>
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <div class="dash-card p-3 text-center">
                        <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-gem text-warning mr-1"></i> FC Packages</h6>
                        <p class="text-muted small mb-2">Explore FC VIP packages and membership tiers.</p>
                        <a href="{{ route('user.package') }}" class="btn btn-sm btn-outline-warning font-weight-bold btn-block text-dark" style="border-radius: 6px;">
                            View FC Packages →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

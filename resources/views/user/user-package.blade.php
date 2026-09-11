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
        .pkg-card-title {
            background: linear-gradient(135deg, #f8fafc, #eef2ff);
            padding: 14px 18px;
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }
        .pkg-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            line-height: 1;
        }
        .pkg-desc {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0;
        }
    </style>

    <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-fluid pkg-container max-w-7xl mx-auto">

            {{-- Top Navigation Header --}}
            <div class="dash-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
                <a href="javascript:history.back()" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>

                <h4 class="font-weight-bold text-white mb-0 text-center flex-grow-1" style="font-size: 1.25rem;">
                    <i class="fas fa-gem text-warning mr-2"></i> FC VIP Packages &amp; Account Activation
                </h4>

                <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                    <i class="fas fa-wallet mr-1"></i> Deposit: ${{ number_format(auth()->user() ? auth()->user()->deposits->where('status','approved')->sum('amount_deposited') - auth()->user()->deposits->where('status','used')->sum('amount_removed') : 0, 2) }}
                </span>
            </div>

            {{-- Session Flash Messages --}}
            @if(session('message'))
                <div class="alert alert-info text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-info-circle mr-2"></i>{{ session('message') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Activation Code Form & Free Tier Row --}}
            @php
                $isLeader = in_array(strtoupper((string) $user->has_paid_package), ['TEAM_LEADER', 'SUPER_LEADER']);
                $matchingLeader = \App\Models\TeamLeader::where('User_name', $user->user)->orWhere('Email', $user->email)->first();
                $leaderCredit = $matchingLeader ? $matchingLeader->superLeaderCredit : null;
                $isCreditDisabled = $leaderCredit ? in_array(strtolower($leaderCredit->status), ['disabled', 'deactivated', 'rejected']) : false;

                $latestLeaderPayment = \App\Models\Payment::where('user', $user->id)
                    ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                $isLeaderExpired = false;
                if ($latestLeaderPayment) {
                    if ($latestLeaderPayment->is_expired) {
                        $isLeaderExpired = true;
                    } elseif ($latestLeaderPayment->expiration_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($latestLeaderPayment->expiration_date))) {
                        $isLeaderExpired = true;
                    }
                }

                $isActiveLeader = $isLeader && $matchingLeader && $matchingLeader->status === 'confirmed' && !$isCreditDisabled && !$isLeaderExpired;
            @endphp

            <div class="row mb-4">
                {{-- Activation Code Input Form --}}
                <div class="col-12 col-md-6 mb-3">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-between border">
                        <div>
                            <div class="p-2 mb-3 rounded bg-warning text-dark font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-key mr-1"></i> Account Activation Code</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Have an Activation Code?</h4>
                            <p class="text-muted small mb-3">Redeem your package or Team Leader activation code to activate or upgrade immediately.</p>
                        </div>
                        <div>
                            <form action="{{ route('user.dashboard.validate') }}" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="text" name="code" placeholder="Enter 17-Char Code (e.g. FOM-A8B9C3D2E4F5G or Leader Code)" required class="form-control text-center font-mono font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                                </div>
                                <button type="submit" class="btn btn-dark btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;">
                                    <i class="fas fa-bolt text-warning mr-1"></i> Submit Activation Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Free Tier --}}
                @php
                    $isAlreadyFree = ($user->has_free_package === 'yes') || (strtolower(trim((string)$user->has_paid_package)) === 'standard');
                    $userHasActiveUvp = ($highestUvpPackageAmount > 0) || (isset($hasPaidPackage) && $hasPaidPackage);
                    $canSeeFreeTier = !$isAlreadyFree && !$userHasActiveUvp && !$isActiveLeader;
                @endphp
                @if($canSeeFreeTier)
                <div class="col-12 col-md-6 mb-3">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-between border">
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
                @endif
            </div>

            {{-- FC VIP Packages Grid — restored to the simple white dash-card style used elsewhere on the page --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-gem text-warning mr-2"></i> FC VIP Packages
                </h5>

                <div class="row">
                    @forelse($packages as $package)
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="dash-card h-100 d-flex flex-column">
                                <div class="pkg-card-title text-center">
                                    <i class="fas fa-coins text-warning mr-1"></i>
                                    {{ $package->name }}
                                    <span class="ml-1 badge badge-pill badge-warning text-dark" style="font-size: 0.7rem;">DMaster Coin Card</span>
                                </div>
                                <div class="p-4 d-flex flex-column text-center flex-grow-1">
                                    <div class="pkg-price my-2">${{ number_format($package->price, 0) }}</div>
                                    <ul class="list-unstyled text-left text-sm text-slate-600 mx-auto mb-3" style="max-width: 260px; font-size: 0.86rem;">
                                        <li class="mb-1"><i class="fas fa-hand-holding-usd text-success mr-2"></i>Allowed cryptofoneloan from <strong>{{ $package->loanRangeLabel() }}</strong></li>
                                        <li class="mb-1"><i class="fas fa-coins text-warning mr-2"></i>Get coin/token <strong>{{ $package->tokensLabel() }}</strong> <span class="text-xs text-slate-500">(locked 12 months, 1/12 released monthly)</span></li>
                                        <li class="mb-1"><i class="fas fa-bullhorn text-info mr-2"></i>Get for <strong>{{ $package->adsCreditsLabel() }}</strong></li>
                                        @if($package->free_shop_room)
                                            <li class="mb-1"><i class="fas fa-store text-purple-500 mr-2"></i>Free Shop Room online</li>
                                        @endif
                                    </ul>
                                    <p class="pkg-desc mb-3" style="font-size:0.78rem;">
                                        Permanent FC VIP lifetime membership — unlocks FC Leadership &amp; FC Streamline Ranks eligibility. No renewals.
                                    </p>
                                    <div class="mt-auto">
                                        <form action="{{ route('ventures') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="package_type" value="FC">
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                            <input type="hidden" name="amount_invest" value="{{ $package->price }}">
                                            <input type="hidden" name="network" value="TRC-20">
                                            <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2 text-dark" style="border-radius: 8px; font-size: 13px;">
                                                <i class="fas fa-coins mr-1"></i> Activate FC VIP ${{ number_format($package->price, 0) }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-gem fa-2x mb-2 text-warning"></i>
                            <p class="font-weight-bold mb-0">No FC VIP packages available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

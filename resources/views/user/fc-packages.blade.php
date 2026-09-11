@php
    $user = Auth::user();
    $depositBalance = $user ? ($user->deposits->where('status','approved')->sum('amount_deposited') - $user->deposits->where('status','used')->sum('amount_removed')) : 0;
@endphp
<div class="wrapper">
    @include('user.user-dashboard-base')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .pkg-container { width: 100%; padding: 1.5rem 1rem; }
        .dash-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
            transition: all 0.25s ease !important;
            background: #ffffff; color: #1e293b; overflow: hidden;
        }
        .dash-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
        .fc-header-bg {
            background: linear-gradient(135deg, #1a1f35 0%, #0b0f1a 100%);
            color: #ffffff; border-radius: 16px;
            border: 1px solid rgba(212,175,55,0.35);
        }
        .pkg-card-title {
            background: linear-gradient(135deg, #f8fafc, #fff8e1);
            padding: 14px 18px; font-weight: 700; font-size: 1.05rem; color: #1e293b;
            border-bottom: 1px solid #fde68a;
        }
        .owned-card-title {
            background: linear-gradient(135deg, #064e3b, #065f46);
            padding: 14px 18px; font-weight: 700; font-size: 1rem; color: #ecfdf5;
            border-bottom: 1px solid #047857;
        }
        .pkg-price { font-size: 2.2rem; font-weight: 800; color: #0f172a; letter-spacing: 0.5px; line-height: 1; }
        .owned-tag {
            display:inline-block; background:#dcfce7; color:#166534;
            padding:3px 10px; border-radius:999px; font-size:0.72rem; font-weight:700;
            letter-spacing:0.08em; text-transform:uppercase;
        }
        .info-card {
            background: #fffbeb; border:1px solid #fcd34d; color:#78350f;
            border-radius:12px; padding:14px 18px; font-size:0.85rem;
        }
        .token-progress {
            height: 10px; background:#fef3c7; border-radius:999px; overflow:hidden;
        }
        .token-progress > div {
            height:100%;
            background: linear-gradient(90deg, #f59e0b, #d97706);
            border-radius:999px;
            transition: width .3s ease;
        }
        .benefit-row {
            display:flex; align-items:flex-start; gap:8px; padding:4px 0;
            font-size: 0.85rem; color:#475569;
        }
        .benefit-row i {
            width:18px; text-align:center; margin-top:3px; flex-shrink:0;
        }
        .kv {
            display:flex; justify-content:space-between; align-items:center;
            padding:6px 0; font-size:0.84rem; border-bottom:1px dashed #e2e8f0;
        }
        .kv:last-child { border-bottom: none; }
        .kv .lbl { color:#64748b; }
        .kv .val { font-weight:700; color:#0f172a; }
    </style>

    <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-fluid pkg-container max-w-7xl mx-auto">

            {{-- Header --}}
            <div class="fc-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-1"></i> Dashboard
                </a>

                <h4 class="font-weight-bold text-white mb-0 text-center flex-grow-1" style="font-size: 1.25rem;">
                    <i class="fas fa-coins text-warning mr-2"></i> FC VIP Packages — DMaster Coin Card
                </h4>

                <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                    <i class="fas fa-wallet mr-1"></i> Deposit: ${{ number_format($depositBalance, 2) }}
                </span>
            </div>

            {{-- Flash Messages --}}
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

            {{-- Info banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-card">
                        <i class="fas fa-crown text-warning mr-1"></i>
                        @if($highestFcAmount > 0)
                            You currently hold an FC VIP membership at
                            <strong>${{ number_format($highestFcAmount, 0) }}</strong>.
                            FC VIP is a <strong>lifetime</strong> membership — you can upgrade to a higher FC tier
                            at any time, but you cannot repurchase or downgrade. Every FC tier you buy grants you
                            the DMaster coin card, the cryptofoneloan allowance, ad credits, free shop room, and
                            tokens that lock for 12 months and release 1/12 each month to your Available Token balance.
                        @else
                            Choose your <strong>DMaster Coin Card</strong> tier below. Each FC VIP package is a
                            <strong>lifetime</strong> membership and unlocks the crypto loan, ad credits, free shop
                            room, FC Leadership bonuses and FC Streamline Ranks. Tokens are locked for 12 months and
                            released to Available Token in 12 equal monthly installments.
                        @endif
                    </div>
                </div>
            </div>

            {{-- FC summary strip (only when user owns FC) --}}
            @if($ownedFcPayments->isNotEmpty())
            <div class="row mb-3">
                <div class="col-md-3 col-6 mb-2">
                    <div class="dash-card p-3 text-center">
                        <small class="text-muted d-block">Total FC Paid</small>
                        <strong class="text-success" style="font-size:1.2rem;">${{ number_format($fcTotals['paid'], 0) }}</strong>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="dash-card p-3 text-center">
                        <small class="text-muted d-block">Total Tokens Granted</small>
                        <strong class="text-amber-600" style="font-size:1.2rem;">{{ number_format($fcTotals['total_tokens'], 0) }}</strong>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="dash-card p-3 text-center">
                        <small class="text-muted d-block">Released to Available</small>
                        <strong class="text-emerald-600" style="font-size:1.2rem;">{{ number_format($fcTotals['released'], 0) }}</strong>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="dash-card p-3 text-center">
                        <small class="text-muted d-block">Still Locked (12-mo)</small>
                        <strong class="text-warning" style="font-size:1.2rem;">{{ number_format($fcTotals['locked'], 0) }}</strong>
                    </div>
                </div>
            </div>
            @endif

            @if($ownedFcPayments->isNotEmpty())
            <div class="mb-4">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-id-badge text-warning mr-2"></i> My FC VIP Memberships
                </h5>
                <div class="row">
                    @foreach($ownedFcPayments as $owned)
                        @php
                            $pkg = $owned->payable;
                            if (!$pkg && $owned->category === 'FC') {
                                $pkg = \App\Models\FCpackage::where('name', $owned->package)->first();
                            }
                            // Fallback: if the payable_type was saved as a fully-qualified
                            // class string (e.g. App\\Models\\FCpackage) but the relation
                            // didn't resolve, try resolving by payable_id.
                            if (!$pkg && $owned->payable_id) {
                                $pkg = \App\Models\FCpackage::find($owned->payable_id);
                            }
                            $sch = $fcTokenSchedules->get($owned->id);
                            $totalT   = $sch ? (float) $sch->total_tokens   : 0;
                            $released = $sch ? (float) $sch->released_tokens : 0;
                            $locked   = max(0, $totalT - $released);
                            $pct      = $totalT > 0 ? min(100, ($released / $totalT) * 100) : 0;
                            $nextDate = $sch && $sch->next_release_date ? \Carbon\Carbon::parse($sch->next_release_date) : null;
                            $mDone    = $sch ? (int) $sch->months_released : 0;
                            $mTotal   = $sch ? (int) $sch->total_months : 12;
                        @endphp
                        <div class="col-12 col-lg-6 mb-3">
                            <div class="dash-card h-100">
                                <div class="owned-card-title d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-crown mr-2"></i>{{ $owned->package }}</span>
                                    <span class="owned-tag" style="background:#10b981;color:#fff;"><i class="fas fa-check mr-1"></i>Active · Lifetime</span>
                                </div>

                                <div class="p-4">
                                    <div class="row">
                                        {{-- Left column: package details + benefits --}}
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-emerald-700 mb-2" style="font-size:0.82rem;letter-spacing:.08em;text-transform:uppercase;">
                                                <i class="fas fa-gem mr-1"></i> Package Details
                                            </h6>
                                            <div class="kv"><span class="lbl">Price paid</span><span class="val">${{ number_format((float)($owned->paid ?? $owned->amount ?? 0), 2) }}</span></div>
                                            <div class="kv"><span class="lbl">Activated on</span><span class="val">{{ optional($owned->created_at)->format('d M Y') }}</span></div>
                                            <div class="kv"><span class="lbl">Status</span><span class="val text-success">Active</span></div>
                                            <div class="kv"><span class="lbl">Expires</span><span class="val text-emerald-700">Never (lifetime)</span></div>
                                            @if($pkg)
                                            <h6 class="font-weight-bold text-amber-700 mt-3 mb-2" style="font-size:0.82rem;letter-spacing:.08em;text-transform:uppercase;">
                                                <i class="fas fa-gift mr-1"></i> Benefits
                                            </h6>
                                            <div class="benefit-row"><i class="fas fa-id-card text-warning"></i><span><strong>DMaster Coin Card</strong></span></div>
                                            <div class="benefit-row"><i class="fas fa-hand-holding-usd text-success"></i><span>Allowed cryptofoneloan from <strong>{{ $pkg->loanRangeLabel() }}</strong></span></div>
                                            <div class="benefit-row"><i class="fas fa-bullhorn text-info"></i><span>Ad credits: <strong>{{ $pkg->adsCreditsLabel() }}</strong></span></div>
                                            @if($pkg->free_shop_room)
                                                <div class="benefit-row"><i class="fas fa-store text-purple-500"></i><span>Free Shop Room online</span></div>
                                            @endif
                                            <div class="benefit-row"><i class="fas fa-users text-primary"></i><span>FC Leadership &amp; Streamline Ranks eligible</span></div>
                                            @endif
                                        </div>

                                        {{-- Right column: token release details --}}
                                        <div class="col-md-6">
                                            <h6 class="font-weight-bold text-amber-700 mb-2" style="font-size:0.82rem;letter-spacing:.08em;text-transform:uppercase;">
                                                <i class="fas fa-coins mr-1"></i> Token Details (12-month vesting)
                                            </h6>
                                            @if($sch && $totalT > 0)
                                                <div class="kv"><span class="lbl">Total tokens granted</span><span class="val">{{ number_format($totalT) }}</span></div>
                                                <div class="kv"><span class="lbl">Monthly release</span><span class="val">{{ number_format((float) $sch->monthly_amount) }}/mo</span></div>
                                                <div class="kv"><span class="lbl">Released so far</span><span class="val text-emerald-600">{{ number_format($released) }}</span></div>
                                                <div class="kv"><span class="lbl">Still locked</span><span class="val text-warning">{{ number_format($locked) }}</span></div>
                                                <div class="kv"><span class="lbl">Progress</span><span class="val">{{ $mDone }} / {{ $mTotal }} months</span></div>

                                                <div class="mt-2 mb-2">
                                                    <div class="token-progress"><div style="width: {{ $pct }}%;"></div></div>
                                                    <small class="text-muted">{{ number_format($pct, 1) }}% released</small>
                                                </div>

                                                <div class="kv">
                                                    <span class="lbl">Next release</span>
                                                    <span class="val">
                                                        @if($sch->status === 'completed')
                                                            <span class="text-emerald-600">Fully released</span>
                                                        @elseif($nextDate)
                                                            {{ $nextDate->format('d M Y') }}
                                                            @if($nextDate->isFuture())
                                                                <small class="text-muted">({{ $nextDate->diffForHumans() }})</small>
                                                            @endif
                                                        @else
                                                            —
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="kv">
                                                    <span class="lbl">Vesting ends</span>
                                                    <span class="val">{{ $sch->end_date ? \Carbon\Carbon::parse($sch->end_date)->format('d M Y') : '—' }}</span>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        {{ number_format((float) $sch->monthly_amount) }} tokens are automatically moved from Locked Token → Available Token every month for 12 months.
                                                    </small>
                                                </div>
                                            @else
                                                <div class="alert alert-sm alert-warning mb-0" style="font-size:0.82rem;">
                                                    <i class="fas fa-clock mr-1"></i> This membership was activated before the 12-month token vesting rule was enabled, so no token release schedule was created for it.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- FC Package cards (available tiers) --}}
            <div class="mb-4">
                <h5 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-coins text-warning mr-2"></i> Available FC VIP Tiers
                </h5>

                <div class="row">
                    @forelse($packages as $package)
                        @php
                            $alreadyOwned = $highestFcAmount > 0 && abs((float)$package->price - $highestFcAmount) < 0.01;
                            $isDowngrade  = $highestFcAmount > 0 && (float)$package->price < $highestFcAmount;
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="dash-card h-100 d-flex flex-column {{ $alreadyOwned ? 'border-success' : '' }}">
                                <div class="pkg-card-title text-center">
                                    <i class="fas fa-coins text-warning mr-1"></i>
                                    {{ $package->name }}
                                    <span class="ml-1 badge badge-pill badge-warning text-dark" style="font-size:0.7rem;">DMaster Coin Card</span>
                                    @if($alreadyOwned)
                                        <div class="mt-1"><span class="owned-tag"><i class="fas fa-check mr-1"></i>You own this</span></div>
                                    @elseif($isDowngrade)
                                        <div class="mt-1"><span class="badge badge-pill badge-secondary">Upgrade only</span></div>
                                    @endif
                                </div>
                                <div class="p-4 d-flex flex-column text-center flex-grow-1">
                                    <div class="pkg-price my-2">${{ number_format($package->price, 0) }}</div>

                                    <ul class="list-unstyled text-left text-slate-600 mx-auto mb-3" style="max-width: 280px; font-size: 0.86rem;">
                                        <li class="mb-1"><i class="fas fa-id-card text-warning mr-2"></i><strong>DMaster Coin Card</strong></li>
                                        <li class="mb-1"><i class="fas fa-hand-holding-usd text-success mr-2"></i>Allowed cryptofoneloan from <strong>{{ $package->loanRangeLabel() }}</strong></li>
                                        <li class="mb-1"><i class="fas fa-coins text-warning mr-2"></i>Get coin/token <strong>{{ $package->tokensLabel() }}</strong>
                                            <span class="text-xs text-slate-500 d-block ml-5">(locked 12 months, 1/12 released monthly to Available Token)</span>
                                        </li>
                                        <li class="mb-1"><i class="fas fa-bullhorn text-info mr-2"></i>Get for <strong>{{ $package->adsCreditsLabel() }}</strong></li>
                                        @if($package->free_shop_room)
                                            <li class="mb-1"><i class="fas fa-store text-purple-500 mr-2"></i>Free Shop Room online</li>
                                        @endif
                                        <li class="mb-1"><i class="fas fa-infinity text-warning mr-2"></i>Permanent lifetime VIP — never expires, no renewals</li>
                                        <li class="mb-1"><i class="fas fa-users text-primary mr-2"></i>Eligible for FC Leadership bonuses &amp; FC Streamline Ranks</li>
                                    </ul>

                                    <div class="mt-auto">
                                        @if($alreadyOwned)
                                            <button class="btn btn-outline-success btn-block font-weight-bold py-2" style="border-radius:8px;font-size:13px;" disabled>
                                                <i class="fas fa-check-circle mr-1"></i> Already Owned
                                            </button>
                                        @elseif($isDowngrade)
                                            <button class="btn btn-outline-secondary btn-block font-weight-bold py-2" style="border-radius:8px;font-size:13px;" disabled>
                                                <i class="fas fa-lock mr-1"></i> Upgrade required
                                            </button>
                                        @else
                                            <form action="{{ route('ventures') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="package_type" value="FC">
                                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                                <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                                <input type="hidden" name="amount_invest" value="{{ $package->price }}">
                                                <input type="hidden" name="network" value="TRC-20">
                                                <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2 text-dark" style="border-radius:8px;font-size:13px;">
                                                    <i class="fas fa-coins mr-1"></i>
                                                    {{ $highestFcAmount > 0 ? 'Upgrade to' : 'Activate' }} FC VIP ${{ number_format($package->price, 0) }}
                                                </button>
                                            </form>
                                            <small class="text-muted d-block mt-1" style="font-size:0.72rem;">
                                                Charged from deposit balance first; falls back to USDT TRC-20 if insufficient.
                                            </small>
                                        @endif
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

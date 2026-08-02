<?php
use App\Models\Deposits;
use Illuminate\Support\Facades\Auth;
use App\Models\DepositWallet;

$user     = Auth::user();
$deposits = $user->deposits()->latest()->take(10)->get();

// Three separate collections so each tab renders its own list.
$cryptoWallets      = DepositWallet::activeOfType('crypto');
$advcashWallets     = DepositWallet::activeOfType('advcash');
$perfectMoneyWallets = DepositWallet::activeOfType('perfect_money');

$minDeposit = $minDeposit ?? (float) (\App\Models\WithdrawalSetting::current()->min_deposit_amount ?? 10);
$directDepositAddress = $directDepositAddress ?? null;
?>
@include('user.user-dashboard-base')

<div class="content-wrapper text-white" style="background:#1c1d20;">

    <div class="tabs tab_links my-2" style="border-bottom: 1px solid white">
        <span class="links_tabs d-flex">
            <a href="{{ route('user.dashboard.deposit') }}" class="fomoLink text-white" id="tabs">
                <i class="fa-regular fa-address-card"></i> Deposit
            </a>
            <a href="{{ route('user.dashboard.withdraw') }}" class="fomoLink text-white">
                <i class="fa-solid fa-money-bill-transfer"></i> Withdraw
            </a>
        </span>
    </div>

        <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="main_head mb-0 text-white font-weight-bold" style="color: #ffffff !important; font-size: 1.75rem;">Deposit to Your Account</h1>
            <a href="{{ route('user.deposits.history') }}" class="btn btn-sm btn-outline-light font-weight-bold">
                <i class="fas fa-list mr-1"></i> Full Deposit History
            </a>
        </div>

        @if(session('message'))<div class="alert alert-success mx-3">{{ session('message') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger mx-3">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger mx-3">{{ $errors->first() }}</div>@endif

        {{-- ═══════════════════════════════════════════════════
             15-MINUTE REVIEW COUNTDOWN FOR PENDING DEPOSITS
        ═══════════════════════════════════════════════════ --}}
        @php
            $pendingDeposit = $deposits->where('status', 'pending')->first();
            $pendingExpiresAtIso = ($pendingDeposit && $pendingDeposit->expires_at) ? $pendingDeposit->expires_at->toIso8601String() : null;
            $pendingSecs = ($pendingDeposit && $pendingDeposit->expires_at) ? max(0, now()->diffInSeconds($pendingDeposit->expires_at, false)) : null;
            $pendingIsExpired = $pendingDeposit && $pendingDeposit->expires_at && now()->greaterThan($pendingDeposit->expires_at);
        @endphp

        @if($pendingDeposit)
        <div class="px-3 mb-4">
            <div class="card text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid #f59e0b; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.4);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3 border-bottom pb-3" style="border-color: rgba(255,255,255,0.1) !important;">
                        <div>
                            <span class="badge badge-warning text-dark font-weight-bold px-3 py-1 text-uppercase" id="dashPendingBadge" style="font-size: 0.8rem; border-radius: 6px;">
                                {{ $pendingIsExpired ? 'EXPIRED' : 'PENDING APPROVAL' }}
                            </span>
                            <h4 class="font-weight-bold text-white mb-0 mt-2">
                                <i class="fas fa-hourglass-half text-warning mr-2"></i> Deposit Review in Progress
                            </h4>
                            <small class="text-light opacity-80">Reference: <strong>{{ $pendingDeposit->transaction_id }}</strong></small>
                        </div>
                        <div class="text-center bg-dark p-3 rounded border border-warning" style="min-width: 180px; border-radius: 10px !important;">
                            <div class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">15-Min Review Countdown</div>
                            <div id="dashCountdown" class="font-mono font-weight-bold text-warning" style="font-size: 2.2rem; line-height: 1;">
                                {{ $pendingSecs !== null ? gmdate('i:s', $pendingSecs) : '15:00' }}
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-8 mb-2 mb-md-0">
                            <div class="row text-sm">
                                <div class="col-6 col-sm-3 mb-2">
                                    <span class="text-muted d-block small">AMOUNT</span>
                                    <strong class="text-warning" style="font-size: 1.1rem;">${{ number_format($pendingDeposit->amount_deposited, 2) }}</strong>
                                </div>
                                <div class="col-6 col-sm-3 mb-2">
                                    <span class="text-muted d-block small">METHOD</span>
                                    <strong>{{ $pendingDeposit->deposit_method ?? 'MANUAL' }}</strong>
                                </div>
                                <div class="col-6 col-sm-3 mb-2">
                                    <span class="text-muted d-block small">SUBMITTED</span>
                                    <strong>{{ $pendingDeposit->created_at->format('M d, H:i') }}</strong>
                                </div>
                                <div class="col-6 col-sm-3 mb-2">
                                    <span class="text-muted d-block small">NETWORK</span>
                                    <strong>{{ $pendingDeposit->network ?? 'TRC-20' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <a href="{{ route('user.manual-deposit.waiting', $pendingDeposit->id) }}" class="btn btn-warning font-weight-bold px-3 py-2" style="border-radius: 8px;">
                                <i class="fas fa-external-link-alt mr-1"></i> Open Waiting Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function(){
            var expiresAt = @json($pendingExpiresAtIso);
            var statusUrl = @json(route('user.manual-deposit.status', $pendingDeposit->id));
            var cdEl = document.getElementById('dashCountdown');
            var badgeEl = document.getElementById('dashPendingBadge');

            function pad(n) { return String(n).padStart(2, '0'); }
            function formatSecs(s) {
                s = Math.max(0, s || 0);
                return pad(Math.floor(s / 60)) + ':' + pad(s % 60);
            }

            function tick() {
                if (!expiresAt) return;
                var rem = Math.floor((new Date(expiresAt).getTime() - Date.now()) / 1000);
                if (cdEl) cdEl.textContent = formatSecs(rem);
                if (rem <= 0 && cdEl) {
                    cdEl.textContent = '00:00';
                    cdEl.style.color = '#ef4444';
                }
            }

            function checkStatus() {
                fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function(r){ return r.json(); })
                    .then(function(data){
                        if (typeof data.seconds_remaining === 'number' && cdEl) {
                            cdEl.textContent = formatSecs(data.seconds_remaining);
                        }
                        if (data.status === 'approved') {
                            if (badgeEl) { badgeEl.textContent = 'APPROVED'; badgeEl.className = 'badge badge-success text-white font-weight-bold px-3 py-1'; }
                            setTimeout(function(){ window.location.reload(); }, 1200);
                        } else if (data.status === 'rejected' || data.status === 'cancelled') {
                            if (badgeEl) { badgeEl.textContent = String(data.status).toUpperCase(); badgeEl.className = 'badge badge-danger text-white font-weight-bold px-3 py-1'; }
                        }
                    }).catch(function(){});
            }

            tick();
            setInterval(tick, 1000);
            setInterval(checkStatus, 15000);
        })();
        </script>
        @endif

        {{-- ═══════════════════════════════════════════════════
             DEPOSIT METHOD TABS (Manual Crypto / Advcash / Perfect Money)
        ═══════════════════════════════════════════════════ --}}
        <div class="px-3">
            <ul class="nav nav-pills mb-3" role="tablist" id="depositTabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="pill" href="#tab-crypto">
                        <i class="fab fa-bitcoin mr-1"></i>Manual Crypto <small class="text-muted">({{ $cryptoWallets->count() }})</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-advcash">
                        <i class="fas fa-money-bill-wave mr-1"></i>Advcash <small class="text-muted">({{ $advcashWallets->count() }})</small>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-perfect-money">
                        <i class="fas fa-coins mr-1"></i>Perfect Money <small class="text-muted">({{ $perfectMoneyWallets->count() }})</small>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- ───────────────── CRYPTO TAB ───────────────── --}}
                <div class="tab-pane fade show active" id="tab-crypto">
                    @if($cryptoWallets->isEmpty())
                        <div class="alert alert-warning">No crypto deposit wallets configured yet. Contact admin.</div>
                    @else
                        <div class="row">
                            {{-- 1) Crypto picker + network picker --}}
                            <div class="col-md-5 mb-3">
                                <label class="text-white font-weight-bold">1. Pick your crypto &amp; network</label>
                                <select id="cryptoPicker" class="form-control" style="background:#222; color:white; border-color:#555;">
                                    @foreach($cryptoWallets->groupBy('currency') as $currency => $rows)
                                        <optgroup label="{{ $currency }} ({{ $rows->count() }} network{{ $rows->count() > 1 ? 's' : '' }})">
                                            @foreach($rows as $w)
                                                <option value="{{ $w->id }}"
                                                        data-address="{{ $w->wallet_address }}"
                                                        data-network="{{ $w->network }}"
                                                        data-currency="{{ $w->currency }}"
                                                        data-qr="{{ $w->qrImageUrl() }}"
                                                        data-min="{{ $w->min_amount }}">
                                                    {{ $currency }} — {{ $w->network }} @if(!$w->is_active) (inactive) @endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Each crypto supports one or more networks. Pick the one your wallet supports.
                                </small>
                            </div>

                            {{-- 2) Address + QR --}}
                            <div class="col-md-7 mb-3">
                                <label class="text-white font-weight-bold">2. Send to this address</label>
                                <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                    <div class="d-flex flex-wrap align-items-center">
                                        {{-- QR code --}}
                                        <div class="mr-3 mb-2 text-center">
                                            <img id="cryptoQrImg"
                                                 src="{{ $cryptoWallets->first()?->qrImageUrl() }}"
                                                 alt="QR code"
                                                 style="width:160px; height:160px; background:#fff; padding:6px; border-radius:6px;">
                                            <div class="small text-muted mt-1" id="cryptoQrCaption">
                                                Scan with your wallet app
                                            </div>
                                        </div>
                                        {{-- Address + copy button --}}
                                        <div class="flex-grow-1" style="min-width:240px;">
                                            <div class="small text-muted" id="cryptoAddressLabel">Address ({{ $cryptoWallets->first()?->currency }} · {{ $cryptoWallets->first()?->network }})</div>
                                            <div class="d-flex align-items-center mt-1">
                                                <code id="cryptoAddressText"
                                                      class="text-warning flex-grow-1"
                                                      style="word-break:break-all; font-size:13px; background:#000; padding:6px 8px; border-radius:4px;">
                                                    {{ $cryptoWallets->first()?->wallet_address }}
                                                </code>
                                                <button type="button" id="copyCryptoAddressBtn"
                                                        class="btn btn-sm btn-outline-warning ml-2"
                                                        data-copy-target="#cryptoAddressText">
                                                    <i class="fas fa-copy"></i> Copy
                                                </button>
                                            </div>
                                            <div class="small text-muted mt-2">
                                                Min: $<span id="cryptoMinAmount">{{ number_format($cryptoWallets->first()?->min_amount ?? 10, 2) }}</span>
                                                <span id="cryptoMaxWrap" style="display:none"> · Max: $<span id="cryptoMaxAmount"></span></span>
                                            </div>
                                            <div class="small text-danger mt-1" id="cryptoWarning">
                                                ⚠ Only send {{ $cryptoWallets->first()?->currency ?? 'USDT' }} on
                                                <strong id="cryptoNetworkName">{{ $cryptoWallets->first()?->network ?? 'TRC-20' }}</strong>.
                                                Wrong network = lost funds.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3) Submit deposit request --}}
                        <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                            <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                <h5 class="text-white mb-0">3. Submit your deposit request</h5>
                                <small class="text-muted">After sending, fill this form so admin can verify and credit your account.</small>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('user.payment.savedeposits') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="paymentMethod" value="MANUAL_CRYPTO">
                                    <input type="hidden" name="wallet_id"   id="walletIdInput"   value="{{ $cryptoWallets->first()?->id }}">
                                    <input type="hidden" name="network"     id="networkInput"   value="{{ $cryptoWallets->first()?->network }}">
                                    <input type="hidden" name="currency"     id="currencyInput"  value="{{ $cryptoWallets->first()?->currency }}">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" min="{{ $minDeposit }}" step="0.01" required
                                                   class="form-control" style="background:#222; color:white; border-color:#555;"
                                                   placeholder="Min ${{ number_format($minDeposit, 0) }}">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="text-white">Your Sender Wallet Address <span class="text-danger">*</span></label>
                                            <input type="text" name="paymentaccount" required
                                                   class="form-control" style="background:#222; color:white; border-color:#555;"
                                                   placeholder="The wallet address you sent FROM">
                                        </div>

                                        <div class="col-md-12 mt-2">
                                            <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                            <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                   class="form-control" style="background:#222; color:white; border-color:#555;">
                                            <small class="text-muted">Speeds up admin verification.</small>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                <i class="fas fa-paper-plane mr-1"></i> Submit Deposit Request
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ───────────────── ADVCASH TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-advcash">
                    @if($advcashWallets->isEmpty())
                        <div class="alert alert-warning">Advcash deposits are not configured yet.</div>
                    @else
                        @foreach($advcashWallets as $w)
                            <div class="card mb-3" style="background:#111; border:1px solid #333; border-radius:8px;">
                                <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-money-bill-wave mr-2 text-warning"></i>
                                        Advcash — {{ $w->currency }}
                                        @if(!$w->is_active)
                                            <span class="badge badge-secondary ml-2">Inactive</span>
                                        @endif
                                    </h5>
                                    <small class="text-muted">{{ $w->label }}</small>
                                </div>
                                <div class="card-body">
                                    {{-- Account number + copy --}}
                                    <div class="p-3 mb-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                        <div class="small text-muted">Advcash Account Number ({{ $w->currency }})</div>
                                        <div class="d-flex align-items-center mt-1">
                                            <code id="advcashAddr_{{ $w->id }}"
                                                  class="text-warning flex-grow-1"
                                                  style="word-break:break-all; font-size:16px; background:#000; padding:8px 10px; border-radius:4px;">
                                                {{ $w->wallet_address ?: '(not set yet)' }}
                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2"
                                                    data-copy-target="#advcashAddr_{{ $w->id }}"
                                                    {{ $w->wallet_address ? '' : 'disabled' }}>
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Step-by-step instructions --}}
                                    @if($w->instructions)
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                            <strong class="text-white">Instructions:</strong>
                                            <ol class="text-light mt-2 mb-0 pl-3" style="font-size:14px;">
                                                @foreach(preg_split('/\r?\n/', $w->instructions) as $line)
                                                    @if(trim($line) !== '')
                                                        <li class="mb-1">{!! trim($line) !!}</li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        </div>
                                    @endif

                                    @if($w->is_active && $w->wallet_address)
                                        {{-- Submit request --}}
                                        <form method="POST" action="{{ route('user.payment.savedeposits') }}"
                                              enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="paymentMethod" value="MANUAL_ADVCASH">
                                            <input type="hidden" name="wallet_id"   value="{{ $w->id }}">
                                            <input type="hidden" name="currency"    value="{{ $w->currency }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" min="{{ $minDeposit }}" step="0.01" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="text-white">Your Advcash Account Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="paymentaccount" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                                           placeholder="Your Advcash account (so admin can verify)">
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                                    <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                        <i class="fas fa-paper-plane mr-1"></i> Submit Advcash Deposit Request
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ───────────────── PERFECT MONEY TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-perfect-money">
                    @if($perfectMoneyWallets->isEmpty())
                        <div class="alert alert-warning">Perfect Money deposits are not configured yet.</div>
                    @else
                        @foreach($perfectMoneyWallets as $w)
                            <div class="card mb-3" style="background:#111; border:1px solid #333; border-radius:8px;">
                                <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-coins mr-2 text-warning"></i>
                                        Perfect Money — {{ $w->currency }}
                                        @if(!$w->is_active)
                                            <span class="badge badge-secondary ml-2">Inactive</span>
                                        @endif
                                    </h5>
                                    <small class="text-muted">{{ $w->label }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 mb-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                        <div class="small text-muted">Perfect Money Account Number ({{ $w->currency }})</div>
                                        <div class="d-flex align-items-center mt-1">
                                            <code id="pmAddr_{{ $w->id }}"
                                                  class="text-warning flex-grow-1"
                                                  style="word-break:break-all; font-size:16px; background:#000; padding:8px 10px; border-radius:4px;">
                                                {{ $w->wallet_address ?: '(not set yet)' }}
                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-warning ml-2"
                                                    data-copy-target="#pmAddr_{{ $w->id }}"
                                                    {{ $w->wallet_address ? '' : 'disabled' }}>
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>

                                    @if($w->instructions)
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                            <strong class="text-white">Instructions:</strong>
                                            <ol class="text-light mt-2 mb-0 pl-3" style="font-size:14px;">
                                                @foreach(preg_split('/\r?\n/', $w->instructions) as $line)
                                                    @if(trim($line) !== '')
                                                        <li class="mb-1">{!! trim($line) !!}</li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        </div>
                                    @endif

                                    @if($w->is_active && $w->wallet_address)
                                        <form method="POST" action="{{ route('user.payment.savedeposits') }}"
                                              enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="paymentMethod" value="MANUAL_PERFECT_MONEY">
                                            <input type="hidden" name="wallet_id"   value="{{ $w->id }}">
                                            <input type="hidden" name="currency"    value="{{ $w->currency }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                                    <input type="number" name="amount" min="{{ $minDeposit }}" step="0.01" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="text-white">Your Perfect Money Account <span class="text-danger">*</span></label>
                                                    <input type="text" name="paymentaccount" required
                                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                                           placeholder="Your Perfect Money account number">
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <label class="text-white">Proof of Payment <small class="text-muted">(screenshot / PDF, max 4MB)</small></label>
                                                    <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                                           class="form-control" style="background:#222; color:white; border-color:#555;">
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                                        <i class="fas fa-paper-plane mr-1"></i> Submit Perfect Money Deposit Request
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ───────────────── AUTO (Direct Blockchain) TAB ───────────────── --}}
                {{-- <div class="tab-pane fade" id="tab-auto">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-link mr-2 text-success"></i>Automatic Deposit (Direct TRON)
                            </h4>
                            <small class="text-muted">Send USDT TRC-20 directly to our wallet — confirmed on-chain automatically.</small>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <strong>No third-party gateway.</strong> 100% direct blockchain.
                            </div>

                            @php
                                $hotWallet = env('TRON_HOT_WALLET_ADDRESS', 'TYourCompanyHotWalletAddressHere');
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="p-3 mb-3" style="background:#0d0d0d; border:1px solid #444; border-radius:8px;">
                                        <div class="small text-muted mb-1">Send USDT (TRC-20) to this address</div>
                                        <div class="d-flex align-items-center">
                                            <code id="hotWalletAddr" class="text-success flex-grow-1" style="word-break:break-all; font-size:15px; background:#000; padding:8px; border-radius:4px;">
                                                {{ $hotWallet }}
                                            </code>
                                            <button type="button" class="btn btn-sm btn-outline-success ml-2"
                                                    data-copy-target="#hotWalletAddr">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <div class="small text-muted mt-2">Network: <strong>TRON (TRC-20)</strong></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=tron:{{ $hotWallet }}" 
                                             alt="QR Code" style="width:160px;height:160px;background:#fff;padding:6px;border-radius:6px;">
                                        <div class="small text-muted mt-1">Scan with your wallet</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h6 class="text-white">How it works:</h6>
                                <ol class="text-light small mb-3">
                                    <li>Send any amount of <strong>USDT TRC-20</strong> to the address above.</li>
                                    <li>Our system polls the blockchain every 2 minutes.</li>
                                    <li>Once confirmed on-chain, your deposit is automatically marked <strong>approved</strong>.</li>
                                    <li>No manual submission or proof needed for automatic deposits.</li>
                                </ol>

                                <div class="alert alert-warning py-2 small">
                                    <strong>Important:</strong> Only send USDT on the <strong>TRON (TRC-20)</strong> network. 
                                    Wrong network = funds lost.
                                </div>

                                <div class="small text-muted">
                                    Minimum deposit: <strong>${{ number_format($minDeposit, 2) }}</strong><br>
                                    Deposits are credited after blockchain confirmation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             DEPOSIT HISTORY
        ═══════════════════════════════════════════════════ --}}
        <div class="mt-4 px-3">
            <h4 class="text-white mb-3"><i class="fas fa-history mr-2"></i>Your Deposit History</h4>
            @if($deposits->isEmpty())
                <p class="text-muted">No deposits yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Network</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $i => $dep)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><small>{{ $dep->transaction_id ?? '—' }}</small></td>
                            <td>${{ number_format($dep->amount_deposited, 2) }}</td>
                            <td>{{ $dep->deposit_method ?? '—' }}</td>
                            <td>{{ $dep->network ?? '—' }}</td>
                            <td>
                                @php
                                    $sc = ['pending'=>'warning','approved'=>'success','rejected'=>'danger','under-review'=>'info','cancelled'=>'secondary'];
                                    $badge = $sc[$dep->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($dep->status) }}</span>
                                @if($dep->comment)
                                    <small class="text-muted d-block">{{ $dep->comment }}</small>
                                @endif
                            </td>
                            <td><small>{{ $dep->created_at->format('d M Y') }}</small></td>
                            <td>
                                @if($dep->proof_of_payment)
                                    <a href="{{ asset('storage/'.$dep->proof_of_payment) }}" target="_blank" class="btn btn-xs btn-outline-light">View</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Copy-to-clipboard + crypto/network switcher JS (no deps) --}}
<script>
(function () {
    // ── Copy to clipboard ──
    document.querySelectorAll('[data-copy-target]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const sel = btn.getAttribute('data-copy-target');
            const el = document.querySelector(sel);
            if (!el) return;
            const text = el.textContent.trim();
            const fallback = function () {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(ta);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).catch(fallback);
            } else {
                fallback();
            }
            // Visual feedback
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-warning');
            setTimeout(function () {
                btn.innerHTML = original;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-warning');
            }, 1400);
        });
    });

    // ── Crypto picker — switch address + QR + network ──
    const picker = document.getElementById('cryptoPicker');
    if (picker) {
        const update = function () {
            const opt = picker.options[picker.selectedIndex];
            if (!opt) return;
            const address = opt.getAttribute('data-address') || '';
            const network = opt.getAttribute('data-network') || '';
            const currency = opt.getAttribute('data-currency') || '';
            const qr      = opt.getAttribute('data-qr') || '';
            const min     = opt.getAttribute('data-min') || '10';

            document.getElementById('cryptoAddressText').textContent = address;
            document.getElementById('cryptoQrImg').src = qr;
            document.getElementById('cryptoQrImg').alt = currency + ' ' + network + ' QR';
            document.getElementById('cryptoQrCaption').textContent =
                'Scan with your ' + currency + ' wallet';
            document.getElementById('cryptoMinAmount').textContent = parseFloat(min).toFixed(2);
            document.getElementById('cryptoNetworkName').textContent = network;
            
            // FIX ADDRESS ISSUE OF NOT SHOWING ONLY : Address (USDT · BEP-20)
            document.getElementById('cryptoAddressLabel').textContent =
                `Address (${currency} · ${network})`;

            // Sync hidden inputs in the deposit form
            const wId = document.getElementById('walletIdInput'); if (wId) wId.value = opt.value;
            const nIn = document.getElementById('networkInput');  if (nIn) nIn.value = network;
            const cIn = document.getElementById('currencyInput'); if (cIn) cIn.value = currency;
        };
        picker.addEventListener('change', update);
        update();
    }
})();
</script>

@include('user.footer')

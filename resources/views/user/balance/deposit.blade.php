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
            <h1 class="main_head mb-0">Deposit to Your Account</h1>
            <a href="{{ route('user.deposits.history') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-list mr-1"></i> Full Deposit History
            </a>
        </div>

        @if(session('message'))<div class="alert alert-success mx-3">{{ session('message') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger mx-3">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger mx-3">{{ $errors->first() }}</div>@endif

        {{-- ═══════════════════════════════════════════════════
             DEPOSIT METHOD TABS (Crypto / Advcash / Perfect Money)
        ═══════════════════════════════════════════════════ --}}
        <div class="px-3">
            <ul class="nav nav-pills mb-3" role="tablist" id="depositTabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="pill" href="#tab-crypto">
                        <i class="fab fa-bitcoin mr-1"></i>Crypto <small class="text-muted">({{ $cryptoWallets->count() }})</small>
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
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-auto">
                        <i class="fas fa-bolt mr-1"></i>Auto (Plisio)
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
                                            <div class="small text-muted">Address ({{ $cryptoWallets->first()?->currency }} · {{ $cryptoWallets->first()?->network }})</div>
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

                {{-- ───────────────── AUTO (Plisio) TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-auto">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0"><i class="fas fa-bolt mr-2 text-info"></i>Automatic Deposit (Plisio)</h4>
                            <small class="text-muted">Crypto invoice — payment confirmed automatically (USDT only).</small>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Creates a Plisio USDT invoice. You pay and it confirms automatically — no admin approval needed.</p>
                            @if(isset($ammount))<div class="alert alert-warning">{{ $ammount }}</div>@endif
                            @if(isset($p_failed))<div class="alert alert-danger">{{ $p_failed }}</div>@endif

                            <form method="POST" action="{{ route('user.deposit') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="text-white">Currency: USD ($)</label>
                                </div>
                                <div class="form-group">
                                    <label class="text-white">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" min="{{ $minDeposit }}" required
                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                           placeholder="Minimum ${{ number_format($minDeposit, 0) }}">
                                    <small class="text-muted">Minimum: ${{ number_format($minDeposit, 2) }} (set by admin)</small>
                                </div>
                                <button type="submit" class="btn btn-info btn-block font-weight-bold mt-2">
                                    <i class="fas fa-external-link-alt mr-1"></i> Generate Invoice
                                </button>
                            </form>

                            <div class="mt-3 p-2" style="border:1px solid #333; border-radius:4px; font-size:12px;">
                                <p class="text-warning mb-1">⚠ Rules:</p>
                                <p class="text-muted mb-1">Do not send USDT twice to the same invoice address.</p>
                                <p class="text-muted mb-1">Only USDT TRC-20 is supported.</p>
                                <p class="text-muted mb-0">Create a new invoice for each deposit.</p>
                            </div>
                        </div>
                    </div>
                </div>
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

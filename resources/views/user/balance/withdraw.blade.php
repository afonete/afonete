<?php
use App\Models\Wallet;
use App\Models\withdrawals as WithdrawalModel;
use Illuminate\Support\Facades\Auth;
$user    = Auth::user();
$wallet  = Wallet::where('user', $user->user)->first();
$history = WithdrawalModel::where('user_id', $user->id)->latest()->take(15)->get();
?>
@include('user.user-dashboard-base')

<div class="content-wrapper text-white" style="background:#1c1d20;">

    <div class="tabs tab_links my-2" style="border-bottom:1px solid white">
        <span class="links_tabs d-flex">
            <a href="{{ route('user.dashboard.deposit') }}" class="fomoLink text-white">
                <i class="fa-regular fa-address-card"></i> Deposit
            </a>
            <a href="{{ route('user.dashboard.withdraw') }}" class="fomoLink text-white" id="tabs">
                <i class="fa-solid fa-money-bill-transfer"></i> Withdraw
            </a>
        </span>
    </div>

    <div class="container-fluid py-3">
        <h1 class="main_head">Withdraw from Your Account</h1>

        @if(session('success'))<div class="alert alert-success mx-3">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger mx-3">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger mx-3">{{ $errors->first() }}</div>@endif

        {{-- ─── Available balance strip (always shown) ─── --}}
        <div class="d-flex justify-content-between mb-3 p-3" style="background:#0d0d0d; border-radius:8px;">
            <span class="text-muted">Available Balance (Payout Wallet - Withdrawable):</span>
        <div class="alert alert-info mx-3" style="font-size: 0.9rem;">
            <strong>Important:</strong> Only money in your <strong>Payout Wallet</strong> can be withdrawn. 
            Deposits are never withdrawable. Your daily 25% income is automatically moved here.
        </div>
            <span class="font-weight-bold text-success" style="font-size:1.2rem;">${{ number_format($availlableBalance, 2) }}</span>
        </div>

        @if($availlableBalance < $settings->min_amount)
            <div class="alert alert-warning mx-3">
                <i class="fas fa-info-circle"></i>
                Minimum withdrawal is ${{ number_format($settings->min_amount, 2) }}.
                Per-transaction max ${{ number_format($settings->max_per_transaction, 2) }}.
                @if($settings->daily_limit)   · Daily limit ${{ number_format($settings->daily_limit, 2) }} @endif
                @if($settings->monthly_limit) · Monthly limit ${{ number_format($settings->monthly_limit, 2) }} @endif
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════
             WITHDRAWAL METHOD TABS (Crypto / Advcash / Perfect Money / Instant)
        ═══════════════════════════════════════════════════ --}}
        <div class="px-3">
            <ul class="nav nav-pills mb-3" role="tablist" id="withdrawTabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="pill" href="#tab-wcrypto">
                        <i class="fab fa-bitcoin mr-1"></i>Crypto
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-wadvcash">
                        <i class="fas fa-money-bill-wave mr-1"></i>Advcash
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-wperfectmoney">
                        <i class="fas fa-coins mr-1"></i>Perfect Money
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="pill" href="#tab-winstant">
                        <i class="fas fa-link mr-1"></i>Instant (Direct Blockchain)
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ───────────────── CRYPTO TAB ───────────────── --}}
                <div class="tab-pane fade show active" id="tab-wcrypto">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fab fa-bitcoin mr-2 text-warning"></i>Crypto Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.withdraw.manual') }}">
                                @csrf
                                <input type="hidden" name="method" value="crypto">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white font-weight-bold">1. Pick your crypto &amp; network</label>
                                        <select id="wCryptoPicker" name="currency" class="form-control" required
                                                style="background:#222; color:white; border-color:#555;">
                                            @foreach($cryptoByCurrency as $currency => $rows)
                                                <optgroup label="{{ $currency }} ({{ $rows->count() }} network{{ $rows->count() > 1 ? 's' : '' }})">
                                                    @foreach($rows as $w)
                                                        <option value="{{ $currency }}" data-network="{{ $w->network }}">
                                                            {{ $currency }} — {{ $w->network }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="network" id="wCryptoNetworkInput" value="{{ optional($cryptoByCurrency->first())->first()?->network }}">
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle"></i>
                                            Pick the network your receiving wallet supports.
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white font-weight-bold">2. Destination wallet address</label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               value="{{ $wallet->wallet ?? '' }}"
                                               placeholder="Your wallet address on the chosen network">
                                        <small class="text-muted">Must match the network you selected above.</small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="{{ $settings->min_amount }}"
                                               max="{{ $settings->max_per_transaction }}"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Min ${{ number_format($settings->min_amount, 2) }} · Max ${{ number_format($settings->max_per_transaction, 2) }}">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="e.g. please send before Friday">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                {{ $availlableBalance < $settings->min_amount ? 'disabled' : '' }}>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Crypto Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ───────────────── ADVCASH TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-wadvcash">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-money-bill-wave mr-2 text-warning"></i>Advcash Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($advcashActive->isEmpty())
                                <div class="alert alert-warning">Advcash withdrawals are not currently configured.</div>
                            @else
                            <form method="POST" action="{{ route('user.withdraw.manual') }}">
                                @csrf
                                <input type="hidden" name="method" value="advcash">
                                <input type="hidden" name="network" value="ADVCASH">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white">Currency</label>
                                        <select name="currency" class="form-control" required style="background:#222; color:white; border-color:#555;">
                                            @foreach($advcashActive as $w)
                                                <option value="{{ $w->currency }}">{{ $w->currency }} — {{ $w->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white">Your Advcash Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Your Advcash account (so admin can send funds to you)">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            We'll send your withdrawal to this account number. Make sure it's correct.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="{{ $settings->min_amount }}"
                                               max="{{ $settings->max_per_transaction }}"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="e.g. please use USD wallet">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                {{ $availlableBalance < $settings->min_amount ? 'disabled' : '' }}>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Advcash Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ───────────────── PERFECT MONEY TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-wperfectmoney">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-coins mr-2 text-warning"></i>Perfect Money Withdrawal
                                <small class="text-muted">— processed by admin within 24–48 hrs</small>
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($perfectMoneyActive->isEmpty())
                                <div class="alert alert-warning">Perfect Money withdrawals are not currently configured.</div>
                            @else
                            <form method="POST" action="{{ route('user.withdraw.manual') }}">
                                @csrf
                                <input type="hidden" name="method" value="perfect_money">
                                <input type="hidden" name="network" value="PERFECT_MONEY">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white">Currency</label>
                                        <select name="currency" class="form-control" required style="background:#222; color:white; border-color:#555;">
                                            @foreach($perfectMoneyActive as $w)
                                                <option value="{{ $w->currency }}">{{ $w->currency }} — {{ $w->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-white">Your Perfect Money Account Number <span class="text-danger">*</span></label>
                                        <input type="text" name="address" required
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Your Perfect Money account number">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            We'll send your withdrawal to this account number. Make sure it's correct.
                                        </small>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" required step="0.01"
                                               min="{{ $settings->min_amount }}"
                                               max="{{ $settings->max_per_transaction }}"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="text-white">Notes for admin <small class="text-muted">(optional)</small></label>
                                        <input type="text" name="notes" maxlength="255"
                                               class="form-control" style="background:#222; color:white; border-color:#555;">
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-warning btn-block font-weight-bold"
                                                {{ $availlableBalance < $settings->min_amount ? 'disabled' : '' }}>
                                            <i class="fas fa-paper-plane mr-1"></i> Submit Perfect Money Withdrawal Request
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ───────────────── INSTANT (Direct Blockchain) TAB ───────────────── --}}
                <div class="tab-pane fade" id="tab-winstant">
                    <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                        <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                            <h4 class="text-white mb-0">
                                <i class="fas fa-link mr-2 text-success"></i>Instant Withdrawal (Direct Blockchain)
                            </h4>
                            <small class="text-muted">USDT TRC-20 sent directly on-chain from our hot wallet — no gateway</small>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success py-2 small mb-3">
                                <strong>Direct TRON blockchain.</strong> Funds sent immediately when you click withdraw.
                            </div>

                            <form method="POST" action="{{ route('user.withdraw.direct_blockchain') }}">
                                @csrf

                                <div class="form-group">
                                    <label class="text-white font-weight-bold">Destination USDT TRC-20 Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" required
                                           class="form-control" style="background:#222; color:white; border-color:#555;"
                                           value="{{ old('address', $wallet->wallet ?? '') }}"
                                           placeholder="T... (TRON address)">
                                    <small class="text-muted">Must be a valid TRON (TRC-20) address</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-white">Amount (USDT) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" step="0.01" min="{{ $settings->min_amount }}" required
                                               max="{{ $availlableBalance }}"
                                               class="form-control" style="background:#222; color:white; border-color:#555;"
                                               placeholder="Min ${{ number_format($settings->min_amount, 2) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-white">Network</label>
                                        <input type="text" value="TRON (TRC-20)" class="form-control" disabled
                                               style="background:#222; color:white; border-color:#555;">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success btn-block font-weight-bold"
                                            {{ $availlableBalance < $settings->min_amount ? 'disabled' : '' }}>
                                        <i class="fas fa-paper-plane mr-1"></i> Send USDT Directly on Blockchain
                                    </button>
                                </div>
                            </form>

                            <div class="mt-3 p-2" style="border:1px solid #444; border-radius:4px; font-size:12px; background:#0a0a0a;">
                                <p class="text-warning mb-1">⚠ Important:</p>
                                <ul class="text-muted mb-0 pl-3 small">
                                    <li>Transaction is irreversible once broadcast.</li>
                                    <li>Only send to TRC-20 compatible addresses.</li>
                                    <li>You will receive the on-chain TX hash in the success message.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             WITHDRAWAL HISTORY
        ═══════════════════════════════════════════════════ --}}
        <div class="mt-4 px-3">
            <h4 class="text-white mb-3"><i class="fas fa-history mr-2"></i>Your Withdrawal History</h4>
            @if($history->isEmpty())
                <p class="text-muted">No withdrawals yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-dark table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Reference</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Destination</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Admin Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $i => $w)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><small>{{ $w->transaction_no }}</small></td>
                            <td>
                                <span class="badge badge-{{ $w->method === 'crypto' ? 'primary' : ($w->method === 'advcash' ? 'warning' : 'info') }}">
                                    {{ $w->methodLabel() }}
                                </span>
                            </td>
                            <td>${{ number_format($w->amount, 2) }}</td>
                            <td>
                                <small>
                                    {{ $w->currency }}{{ $w->network ? ' · ' . $w->network : '' }}<br>
                                    <code style="word-break:break-all;">{{ Str::limit($w->wallet_address, 22) }}</code>
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $w->plisio_txn_id ? 'success' : 'secondary' }}">
                                    {{ $w->typeLabel() }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $sc = ['pending'=>'warning','processing'=>'info','completed'=>'success','failed'=>'danger'];
                                    $badge = $sc[$w->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($w->status) }}</span>
                                @if($w->txn_hash)
                                    <small class="text-muted d-block" title="{{ $w->txn_hash }}">
                                        <i class="fas fa-link"></i> {{ Str::limit($w->txn_hash, 16) }}
                                    </small>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $w->admin_note ?? '—' }}</small></td>
                            <td><small>{{ $w->created_at->format('d M Y') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    // Crypto picker — sync the hidden network input when the user picks a different option
    const picker = document.getElementById('wCryptoPicker');
    if (picker) {
        picker.addEventListener('change', function () {
            const opt = picker.options[picker.selectedIndex];
            const network = opt ? opt.getAttribute('data-network') : '';
            const inp = document.getElementById('wCryptoNetworkInput');
            if (inp && network) inp.value = network;
        });
    }
})();
</script>

@include('user.footer')

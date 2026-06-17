<?php
use App\Models\Deposits;
use Illuminate\Support\Facades\Auth;
$user     = Auth::user();
$deposits = $user->deposits()->latest()->take(10)->get();
$wallets  = $wallets ?? \App\Models\DepositWallet::active()->get();
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

        @if(session('message'))
            <div class="alert alert-success mx-3">{{ session('message') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mx-3">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mx-3">{{ $errors->first() }}</div>
        @endif

        <div class="row px-3">

            {{-- ═══════════════════════════════════════════════════
                 MANUAL DEPOSIT FORM
            ═══════════════════════════════════════════════════ --}}
            <div class="col-md-6">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0"><i class="fas fa-hand-holding-usd mr-2 text-warning"></i>Manual Deposit (USDT)</h4>
                        <small class="text-muted">Send USDT to the address below, then submit proof here.</small>
                    </div>
                    <div class="card-body">

                        {{-- FIX (D1): Show admin-configured wallets per network --}}
                        @if($wallets->isEmpty())
                            <div class="alert alert-warning">No deposit wallets configured yet. Contact admin.</div>
                        @else
                        <div class="mb-3">
                            <ul class="nav nav-pills mb-2" role="tablist">
                                @foreach($wallets as $i => $w)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $i === 0 ? 'active' : '' }} text-white"
                                           data-toggle="pill" href="#wallet-{{ $w->network }}">
                                            {{ $w->network }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach($wallets as $i => $w)
                                    <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="wallet-{{ $w->network }}">
                                        <div class="p-3" style="background:#0d0d0d; border:1px solid #444; border-radius:6px;">
                                            <small class="text-muted d-block mb-1">{{ $w->label }}:</small>
                                            <code class="text-warning" style="word-break:break-all; font-size:13px;">
                                                {{ $w->wallet_address }}
                                            </code>
                                            <small class="d-block mt-1">
                                                <span class="text-muted">Min: ${{ number_format($w->min_amount, 0) }}</span>
                                                @if($w->max_amount)
                                                    <span class="text-muted">· Max: ${{ number_format($w->max_amount, 0) }}</span>
                                                @endif
                                            </small>
                                            <small class="text-danger d-block mt-1">⚠ Only send USDT on {{ $w->network }}. Wrong network = lost funds.</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('user.payment.savedeposits') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="paymentMethod" value="MANUAL_USDT">

                            <div class="form-group">
                                <label class="text-white">Amount (USD) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" min="10" step="0.01" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Enter amount you sent (min $10)">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Your Wallet Address (you sent from) <span class="text-danger">*</span></label>
                                <input type="text" name="paymentaccount" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Your USDT wallet address">
                            </div>

                            <div class="form-group">
                                <label class="text-white">Network</label>
                                <select name="network" class="form-control" style="background:#222; color:white; border-color:#555;">
                                    @foreach($wallets as $w)
                                        <option value="{{ $w->network }}">{{ $w->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Proof of Payment <small class="text-muted">(screenshot/PDF, max 4MB)</small></label>
                                <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                       class="form-control" style="background:#222; color:white; border-color:#555;">
                                <small class="text-muted">Upload a screenshot of your transaction. Speeds up approval.</small>
                            </div>

                            <button type="submit" class="btn btn-warning btn-block font-weight-bold mt-2">
                                <i class="fas fa-paper-plane mr-1"></i> Submit Deposit Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════
                 AUTOMATIC DEPOSIT (Plisio invoice)
            ═══════════════════════════════════════════════════ --}}
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0"><i class="fas fa-bolt mr-2 text-info"></i>Automatic Deposit (Plisio)</h4>
                        <small class="text-muted">Generate a crypto invoice — payment confirmed automatically.</small>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Creates a Plisio USDT invoice. You pay and it confirms automatically — no admin approval needed.</p>

                        @if(isset($ammount))
                            <div class="alert alert-warning">{{ $ammount }}</div>
                        @endif
                        @if(isset($p_failed))
                            <div class="alert alert-danger">{{ $p_failed }}</div>
                        @endif

                        <form method="POST" action="{{ route('user.deposit') }}">
                            @csrf
                            <div class="form-group">
                                <label class="text-white">Currency: USD ($)</label>
                            </div>
                            <div class="form-group">
                                <label class="text-white">Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount" min="12" required
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Minimum $12">
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

@include('user.footer')

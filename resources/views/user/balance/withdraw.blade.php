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

        @if(session('success'))
            <div class="alert alert-success mx-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mx-3">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mx-3">{{ $errors->first() }}</div>
        @endif

        <div class="row px-3">

            {{-- ═══════════════════════════════════════════════════
                 MANUAL WITHDRAWAL (pending admin approval)
            ═══════════════════════════════════════════════════ --}}
            <div class="col-md-6">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-hand-holding-usd mr-2 text-warning"></i>Manual Withdrawal Request
                        </h4>
                        <small class="text-muted">Request reviewed and processed by admin within 24–48 hrs.</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 p-2" style="background:#0d0d0d; border-radius:4px;">
                            <span class="text-muted">Available Balance (CASHOUT):</span>
                            <span class="font-weight-bold text-success">${{ number_format($availlableBalance, 2) }}</span>
                        </div>

                        <form method="POST" action="{{ route('user.withdraw.manual') }}">
                            @csrf

                            <div class="form-group">
                                <label class="text-white">Wallet Address (USDT TRC-20)
                                    @if(!$wallet)
                                        — <a href="{{ route('profile.edit') }}" style="color:#3490dc">set in Profile</a>
                                    @else
                                        <small class="text-muted">(from profile — <a href="{{ route('profile.edit') }}" style="color:#3490dc">change</a>)</small>
                                    @endif
                                </label>
                                <input type="text" name="address"
                                       value="{{ $wallet->wallet ?? '' }}"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Your USDT wallet address" required>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Amount <small class="text-muted">(min ${{ \App\Models\WithdrawalSetting::current()->min_amount }})</small></label>
                                <input type="number" name="amount" min="{{ \App\Models\WithdrawalSetting::current()->min_amount }}" step="0.01" required
                                       max="{{ $availlableBalance }}"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Amount to withdraw">
                            </div>

                            <button type="submit" class="btn btn-warning btn-block font-weight-bold mt-2"
                                {{ $availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount ? 'disabled' : '' }}>
                                <i class="fas fa-paper-plane mr-1"></i> Submit Withdrawal Request
                            </button>
                            @if($availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount)
                                <small class="text-danger d-block mt-1">Minimum withdrawal is ${{ \App\Models\WithdrawalSetting::current()->min_amount }}.</small>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════
                 INSTANT WITHDRAWAL (Plisio automatic)
            ═══════════════════════════════════════════════════ --}}
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="card" style="background:#111; border:1px solid #333; border-radius:8px;">
                    <div class="card-header" style="background:#222; border-bottom:1px solid #444;">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-bolt mr-2 text-info"></i>Instant Withdrawal (Plisio)
                        </h4>
                        <small class="text-muted">Sent directly via Plisio API — no admin review needed.</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 p-2" style="background:#0d0d0d; border-radius:4px;">
                            <span class="text-muted">Available Balance (CASHOUT):</span>
                            <span class="font-weight-bold text-success">${{ number_format($availlableBalance, 2) }}</span>
                        </div>

                        <form method="POST" action="{{ route('user.withdraw') }}">
                            @csrf
                            <div class="form-group">
                                <label class="text-white">Wallet Address
                                    @if(!$wallet)
                                        — <a href="{{ route('profile.edit') }}" style="color:#3490dc">set in Profile</a>
                                    @else
                                        <small class="text-muted">(<a href="{{ route('profile.edit') }}" style="color:#3490dc">change</a>)</small>
                                    @endif
                                </label>
                                <input type="text" name="address"
                                       value="{{ $wallet->wallet ?? '' }}"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       required>
                            </div>
                            <div class="form-group">
                                <label class="text-white">Amount <small class="text-muted">(min ${{ \App\Models\WithdrawalSetting::current()->min_amount }})</small></label>
                                <input type="number" name="amount" min="{{ \App\Models\WithdrawalSetting::current()->min_amount }}" step="0.01" required
                                       max="{{ $availlableBalance }}"
                                       class="form-control" style="background:#222; color:white; border-color:#555;"
                                       placeholder="Amount to withdraw">
                            </div>
                            <button type="submit" class="btn btn-info btn-block font-weight-bold mt-2"
                                {{ $availlableBalance < \App\Models\WithdrawalSetting::current()->min_amount ? 'disabled' : '' }}>
                                <i class="fas fa-bolt mr-1"></i> Withdraw Now
                            </button>
                        </form>
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
                            <th>Amount</th>
                            <th>Wallet</th>
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
                            <td>${{ number_format($w->amount, 2) }}</td>
                            <td><small>{{ Str::limit($w->wallet_address, 20) }}</small></td>
                            <td><small>{{ $w->plisio_txn_id ? 'Instant' : 'Manual' }}</small></td>
                            <td>
                                @php
                                    $sc = ['pending'=>'warning','processing'=>'info','completed'=>'success','failed'=>'danger'];
                                    $badge = $sc[$w->status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badge }}">{{ ucfirst($w->status) }}</span>
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

@include('user.footer')

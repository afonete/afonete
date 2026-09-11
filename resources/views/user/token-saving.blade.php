@php
    $symbol = \App\Models\TokenSetting::currentSymbol() ?: 'FOCOIN';
@endphp
<div class="wrapper">
@include('user.user-dashboard-base')

<div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid py-4 max-w-7xl mx-auto">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success text-center font-weight-bold p-3 mb-3" style="border-radius:10px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center font-weight-bold p-3 mb-3" style="border-radius:10px;">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="p-4 mb-4 shadow-sm text-white" style="border-radius:14px; background: linear-gradient(135deg,#065f46 0%,#047857 100%); border:1px solid rgba(16,185,129,.45);">
            <h3 class="font-weight-bold text-white mb-1">
                <i class="fas fa-piggy-bank mr-2" style="color:#6ee7b7;"></i> Saving Token
            </h3>
            <p class="text-light opacity-90 small mb-0">
                Move tokens from <strong>Available Token</strong> into your Saving wallet. Tokens are locked for
                <strong>6 months</strong>. Once matured you can move them back to Available Token and you become
                <strong>eligible to request Credit (Loan)</strong>.
            </p>
        </div>

        {{-- Balance Summary --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100 p-4 bg-white" style="border-radius:14px; border-left:5px solid #10b981 !important;">
                    <span class="text-muted font-weight-bold small text-uppercase"><i class="fas fa-coins text-success mr-1"></i> Saving Token Balance</span>
                    <h2 class="font-weight-bold text-dark my-1" style="font-size:1.8rem;">{{ number_format($savingToken, 2) }} <small class="text-muted font-weight-bold" style="font-size:1rem;">{{ $symbol }}</small></h2>
                    <small class="text-muted">Currently locked in savings.</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100 p-4 bg-white" style="border-radius:14px; border-left:5px solid #f59e0b !important;">
                    <span class="text-muted font-weight-bold small text-uppercase"><i class="fas fa-unlock text-warning mr-1"></i> Matured (Withdrawable)</span>
                    <h2 class="font-weight-bold text-dark my-1" style="font-size:1.8rem;">{{ number_format($totalMatured, 2) }} <small class="text-muted font-weight-bold" style="font-size:1rem;">{{ $symbol }}</small></h2>
                    <small class="text-muted">Completed 6-month lock — ready to move back or use for loan.</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100 p-4 bg-white" style="border-radius:14px; border-left:5px solid #6366f1 !important;">
                    <span class="text-muted font-weight-bold small text-uppercase"><i class="fas fa-wallet text-indigo mr-1"></i> Available Token</span>
                    <h2 class="font-weight-bold text-dark my-1" style="font-size:1.8rem;">{{ number_format($availableToken, 2) }} <small class="text-muted font-weight-bold" style="font-size:1rem;">{{ $symbol }}</small></h2>
                    <small class="text-muted">Balance you can move into savings.</small>
                </div>
            </div>
        </div>

        {{-- Loan eligibility notice (placeholder — loan feature built later) --}}
        @if($loanEligible)
        <div class="alert shadow-sm d-flex align-items-start p-3 mb-4" style="border-radius:12px; background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
            <i class="fas fa-hand-holding-usd fa-lg mr-3 mt-1" style="color:#10b981;"></i>
            <div>
                <strong>You are eligible for Credit (Loan).</strong><br>
                <small>You have matured savings in your token wallet. Credit/loan requests will be enabled here soon — you'll be able to apply against your matured token balance.</small>
            </div>
        </div>
        @else
        <div class="alert shadow-sm d-flex align-items-start p-3 mb-4" style="border-radius:12px; background:#fffbeb; border:1px solid #fcd34d; color:#78350f;">
            <i class="fas fa-clock fa-lg mr-3 mt-1" style="color:#d97706;"></i>
            <div>
                <strong>Loan eligibility unlocks after 6 months of saving.</strong><br>
                <small>Once any of your savings deposits completes the 6-month lock you will see a green Credit (Loan) eligibility notice here. (Loan functionality will be released in a later update.)</small>
            </div>
        </div>
        @endif

        {{-- Move to Savings form --}}
        <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius:14px; overflow:hidden;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-arrow-down text-success mr-1"></i> Move Tokens to Saving Wallet
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.token-saving.deposit') }}" method="POST" class="row align-items-end js-transaction-password-form">
                    @csrf
                    <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                    <div class="col-md-8 mb-2">
                        <label class="font-weight-bold small text-muted text-uppercase">Amount of {{ $symbol }} to lock for 6 months</label>
                        <div class="input-group">
                            <input type="number" name="amount" step="0.01" min="0.01" max="{{ $availableToken }}" required
                                   placeholder="Available: {{ number_format($availableToken, 2) }}" class="form-control font-weight-bold"
                                   style="border-radius:8px 0 0 8px;">
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold bg-success text-white" style="border-radius:0 8px 8px 0;">{{ $symbol }}</span>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Tokens will be locked for 6 months from today, then released to Available Token and you become loan-eligible.</small>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2 shadow-sm"
                                {{ $availableToken <= 0 ? 'disabled' : '' }}
                                style="border-radius:8px;">
                            <i class="fas fa-piggy-bank mr-1"></i> Save Tokens
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Active (locked) savings --}}
        <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius:14px; overflow:hidden;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-lock text-warning mr-1"></i> Active Savings (Locked 6 Months)</h5>
            </div>
            <div class="card-body p-0">
                @if($activeSavings->isEmpty())
                    <p class="text-muted p-4 mb-0 text-center">You don't have any active savings yet. Move tokens from Available Token above to start earning loan eligibility.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:.88rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-3">#</th>
                                <th class="py-3 px-3">Amount</th>
                                <th class="py-3 px-3">Start Date</th>
                                <th class="py-3 px-3">Matures On</th>
                                <th class="py-3 px-3">Days Left</th>
                                <th class="py-3 px-3">Progress</th>
                                <th class="py-3 px-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($activeSavings as $i => $s)
                            @php
                                $daysLeft = $s->daysUntilMature();
                                $pct = min(100, max(0, (180 - $daysLeft) / 180 * 100));
                                $bar   = $daysLeft <= 0 ? 'bg-success' : ($daysLeft <= 30 ? 'bg-warning' : 'bg-info');
                            @endphp
                            <tr>
                                <td class="py-3 px-3 text-muted font-weight-bold">{{ $i + 1 }}</td>
                                <td class="py-3 px-3 font-weight-bold text-dark">{{ number_format($s->amount, 2) }} {{ $symbol }}</td>
                                <td class="py-3 px-3">{{ optional($s->start_date)->format('d M Y') }}</td>
                                <td class="py-3 px-3">{{ optional($s->mature_date)->format('d M Y') }}</td>
                                <td class="py-3 px-3">
                                    @if($daysLeft <= 0)
                                        <span class="badge badge-success">Matured</span>
                                    @else
                                        <span class="font-weight-bold">{{ $daysLeft }}</span> <small class="text-muted">days</small>
                                    @endif
                                </td>
                                <td class="py-3 px-3" style="min-width:180px;">
                                    <div style="height:8px; background:#f1f5f9; border-radius:99px; overflow:hidden;">
                                        <div class="{{ $bar }}" style="height:100%; width:{{ $pct }}%;"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($pct, 0) }}%</small>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($daysLeft <= 0)
                                        <span class="badge badge-success">Ready to withdraw</span>
                                    @else
                                        <span class="badge badge-warning">Locked</span>
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

        {{-- Matured savings (withdraw + loan eligible) --}}
        <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius:14px; overflow:hidden;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-unlock text-success mr-1"></i> Matured Savings (Ready to Withdraw / Loan-Eligible)</h5>
            </div>
            <div class="card-body p-0">
                @if($maturedSavings->isEmpty())
                    <p class="text-muted p-4 mb-0 text-center">No matured savings yet. Active savings will appear here after their 6-month lock completes.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:.88rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-3">#</th>
                                <th class="py-3 px-3">Total Saved</th>
                                <th class="py-3 px-3">Already Withdrawn</th>
                                <th class="py-3 px-3">Available to Withdraw</th>
                                <th class="py-3 px-3">Matured On</th>
                                <th class="py-3 px-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($maturedSavings as $i => $s)
                            @php $remaining = round((float) $s->amount - (float) $s->withdrawn_amount, 4); @endphp
                            <tr>
                                <td class="py-3 px-3 text-muted font-weight-bold">{{ $i + 1 }}</td>
                                <td class="py-3 px-3 font-weight-bold text-dark">{{ number_format($s->amount, 2) }} {{ $symbol }}</td>
                                <td class="py-3 px-3">{{ number_format($s->withdrawn_amount, 2) }} {{ $symbol }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge badge-success">{{ number_format($remaining, 2) }} {{ $symbol }}</span>
                                </td>
                                <td class="py-3 px-3">{{ optional($s->mature_date)->format('d M Y') }}</td>
                                <td class="py-3 px-3 text-right">
                                    <form action="{{ route('user.token-saving.withdraw') }}" method="POST" class="d-inline js-transaction-password-form">
                                        @csrf
                                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                                        <input type="hidden" name="saving_id" value="{{ $s->id }}">
                                        <input type="hidden" name="amount" value="{{ $remaining }}">
                                        <button type="submit" class="btn btn-sm btn-outline-success font-weight-bold" style="border-radius:8px;">
                                            <i class="fas fa-arrow-up mr-1"></i> Move to Available
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- Withdrawal history --}}
        @if($withdrawnSavings->isNotEmpty())
        <div class="card shadow-sm border-0 mb-4 bg-white" style="border-radius:14px; overflow:hidden;">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-history text-muted mr-1"></i> Savings Withdrawal History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:.85rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-3">Date</th>
                                <th class="py-3 px-3">Amount Returned</th>
                                <th class="py-3 px-3">Original Saved</th>
                                <th class="py-3 px-3">Start</th>
                                <th class="py-3 px-3">Matured</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawnSavings as $h)
                            <tr>
                                <td class="py-3 px-3">{{ optional($h->withdrawn_date)->format('d M Y') }}</td>
                                <td class="py-3 px-3 font-weight-bold text-success">{{ number_format($h->withdrawn_amount, 2) }} {{ $symbol }}</td>
                                <td class="py-3 px-3">{{ number_format($h->amount, 2) }} {{ $symbol }}</td>
                                <td class="py-3 px-3">{{ optional($h->start_date)->format('d M Y') }}</td>
                                <td class="py-3 px-3">{{ optional($h->mature_date)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
</div>

@include('user.components.transaction-password-modal')

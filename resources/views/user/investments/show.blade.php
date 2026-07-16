<div class="wrapper">
@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('user.investments') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back to My Investments
    </a>

    <h3 class="font-weight-bold">
        <i class="fas fa-box text-primary mr-2"></i>
        {{ $payment->package }}
        <span class="badge badge-{{ $payment->category === 'VENTURE' ? 'primary' : 'info' }} ml-2">
            {{ strtoupper($payment->category ?? '—') }}
        </span>
    </h3>

    {{-- ── Status banner ── --}}
    @php
        $expired = $payment->is_expired;
        $paidOk  = (int) $payment->status === 1;
        $bannerClass = $expired ? 'secondary' : ($paidOk ? 'success' : 'warning');
        $bannerText  = $expired ? 'EXPIRED — Locked tokens released to Available Token' :
                       ($paidOk ? 'ACTIVE — Earning daily income' : 'PENDING PAYMENT');
    @endphp
    <div class="alert alert-{{ $bannerClass }} font-weight-bold">
        <i class="fas fa-info-circle mr-2"></i> {{ $bannerText }}
    </div>

    {{-- ── Investment summary ── --}}
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Amount</small>
                <div class="font-weight-bold text-primary" style="font-size:1.4rem;">${{ number_format($payment->amount, 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Paid</small>
                <div class="font-weight-bold text-success" style="font-size:1.4rem;">${{ number_format($payment->paid ?? 0, 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Overpaid</small>
                <div class="font-weight-bold text-info" style="font-size:1.4rem;">${{ number_format($payment->over_paid ?? 0, 2) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Duration</small>
                <div class="font-weight-bold text-secondary" style="font-size:1.4rem;">{{ $payment->duration ?? '—' }} days</div>
            </div></div>
        </div>
    </div>

    {{-- ── Token & date details ── --}}
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-lock text-secondary mr-1"></i> Locked Tokens Granted</h6>
                <div class="font-weight-bold text-secondary" style="font-size:1.3rem;">
                    {{ number_format($lockedTokens, 0) }} {{ \App\Models\TokenSetting::currentSymbol() }}
                </div>
                <small class="text-muted">${{ number_format($payment->amount, 0) }} ÷ ${{ number_format($uvpPrice, 4) }} (UVP price)</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-calendar text-info mr-1"></i> Dates</h6>
                <small class="d-block"><strong>Bought:</strong> {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</small>
                <small class="d-block"><strong>Expires:</strong> {{ $payment->expiration_date ? \Carbon\Carbon::parse($payment->expiration_date)->format('d M Y') : '—' }}</small>
                <small class="d-block"><strong>Days elapsed:</strong> {{ $daysElapsed }}</small>
                @if(!is_null($daysRemaining))
                    <small class="d-block"><strong>Days remaining:</strong> {{ $daysRemaining }}</small>
                @endif
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-sync text-warning mr-1"></i> Renewals</h6>
                <div class="font-weight-bold text-warning" style="font-size:1.3rem;">{{ $renewalsCount }} / {{ $maxRenewals }}</div>
                <small class="text-muted">Every 30 days · renewal_price = ${{ number_format($renewalPrice, 4) }}</small>
                @if($payment->status == 1 && !$expired && $renewalsCount < $maxRenewals)
                    <div class="mt-2">
                        <a href="{{ route('packageRenew', $payment->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-redo mr-1"></i> Renew Now
                        </a>
                    </div>
                @endif
            </div></div>
        </div>
    </div>

    {{-- ── Package details ── --}}
    @if($package)
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Package Configuration</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><small class="text-muted">Name:</small> <strong>{{ $package->name ?? '—' }}</strong></div>
                <div class="col-md-3"><small class="text-muted">Plan:</small> {{ $package->plan ?? '—' }}</div>
                @if(property_exists($package, 'min_amount'))
                <div class="col-md-3"><small class="text-muted">Min amount:</small> ${{ number_format($package->min_amount, 0) }}</div>
                <div class="col-md-3"><small class="text-muted">Max amount:</small> ${{ number_format($package->max_amount, 0) }}</div>
                @endif
                @if(property_exists($package, 'percentage'))
                <div class="col-md-3"><small class="text-muted">Daily %:</small> {{ $package->percentage }}%</div>
                @endif
                @if(property_exists($package, 'total_return'))
                <div class="col-md-3"><small class="text-muted">Total return:</small> {{ $package->total_return }}</div>
                @endif
                @if(property_exists($package, 'current_price'))
                <div class="col-md-3"><small class="text-muted">Token price at purchase:</small> ${{ number_format($package->current_price, 4) }}</div>
                @endif
                @if(property_exists($package, 'default_token'))
                <div class="col-md-3"><small class="text-muted">Default tokens:</small> {{ number_format($package->default_token, 0) }}</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ── Renewals ── --}}
    @if($renewals->isNotEmpty())
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Renewal History</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Renewed At</th><th>Next Due</th><th>Amount Paid</th><th>Token Price</th><th>Tokens Received</th><th>Status</th><th>Ref</th></tr>
                    </thead>
                    <tbody>
                        @foreach($renewals as $r)
                        <tr>
                            <td>{{ $r->renewal_number }} / {{ $maxRenewals }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($r->renewed_at)->format('d M Y') }}</small></td>
                            <td><small>{{ $r->next_renewal_due ? \Carbon\Carbon::parse($r->next_renewal_due)->format('d M Y') : '—' }}</small></td>
                            <td>${{ number_format($r->amount_paid, 2) }}</td>
                            <td>${{ number_format($r->token_price_at_renewal, 4) }}</td>
                            <td class="font-weight-bold text-success">{{ number_format($r->tokens_received, 4) }} {{ \App\Models\TokenSetting::currentSymbol() }}</td>
                            <td><span class="badge badge-{{ $r->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($r->status) }}</span></td>
                            <td><small>{{ $r->transaction_no }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Daily income history ── --}}
    @if($dailyIncomes->isNotEmpty())
    @php
        $runningSum = 0.0;
        $dailyIncomesAsc = $dailyIncomes->reverse();
        $cumulativeList = [];
        foreach ($dailyIncomesAsc as $d) {
            $runningSum += (float) $d->amount;
            $cumulativeList[$d->id] = $runningSum;
        }
    @endphp
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-chart-line mr-1"></i> Daily Income History</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Date</th><th>Income</th><th>Cumulative</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @foreach($dailyIncomes as $d)
                        <tr>
                            <td><small>{{ \Carbon\Carbon::parse($d->earned_at)->format('d M Y') }}</small></td>
                            <td class="font-weight-bold text-success">${{ number_format($d->amount, 4) }}</td>
                            <td class="font-weight-bold text-primary">${{ number_format($cumulativeList[$d->id] ?? 0, 2) }}</td>
                            <td><small class="text-muted">{{ $d->notes ?? '—' }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Transactions ── --}}
    @if($transactions->isNotEmpty())
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-exchange-alt mr-1"></i> Related Transactions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Date</th><th>Type</th><th>Ref</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                            @php $details = json_decode($t->transaction_details, true) ?: []; @endphp
                            <tr>
                                <td><small>{{ $t->created_at->format('d M Y H:i') }}</small></td>
                                <td>{{ $t->transaction_type }}</td>
                                <td><small>{{ $t->transaction_no }}</small></td>
                                <td>${{ number_format($details['amount'] ?? 0, 4) }}</td>
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

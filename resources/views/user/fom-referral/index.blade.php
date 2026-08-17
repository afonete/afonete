@include('user.user-dashboard-base')
<div class="content-wrapper" style="background:#0b1220; min-height:100vh;">
<div class="container-fluid py-4 px-3 px-md-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="font-weight-bold text-white mb-1"><i class="fas fa-network-wired text-warning mr-2"></i>FOM Referral Management</h3>
            <small class="text-muted">Binary volume, direct sponsor bonuses and volume points from your FOM Licence Miner referrals.</small>
        </div>
        <a href="{{ route('investment-package') }}" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius:8px;">
            <i class="fas fa-box mr-1"></i> FOM Packages
        </a>
    </div>

    {{-- Eligibility banner --}}
    @if(!$isEligible)
        <div class="alert alert-warning font-weight-bold shadow-sm mb-4" style="border-radius:10px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            You have NOT accepted the Affiliate Terms &amp; Conditions (binary status inactive).
            You can see your referrals below, but <u>no commission will be paid</u> until you accept.
            <a href="{{ route('user.affiliate.terms') }}" class="btn btn-dark btn-sm font-weight-bold ml-2">Accept Terms →</a>
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3 shadow-sm h-100 border-0" style="background:#1e293b; border-left:4px solid #3b82f6 !important; border-radius:14px;">
                <span class="text-muted d-block small font-weight-bold text-uppercase">Left Side Volume</span>
                <h4 class="font-weight-bold text-info mb-0 mt-1">{{ number_format($left, 2) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3 shadow-sm h-100 border-0" style="background:#1e293b; border-left:4px solid #8b5cf6 !important; border-radius:14px;">
                <span class="text-muted d-block small font-weight-bold text-uppercase">Right Side Volume</span>
                <h4 class="font-weight-bold text-white mb-0 mt-1">{{ number_format($right, 2) }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3 shadow-sm h-100 border-0" style="background:#1e293b; border-left:4px solid #f59e0b !important; border-radius:14px;">
                <span class="text-muted d-block small font-weight-bold text-uppercase">Accrued Direct Sponsors</span>
                <h4 class="font-weight-bold text-warning mb-0 mt-1">${{ number_format($accruedDirect, 2) }}</h4>
                <small class="text-muted">Your rate now: {{ $directRate }}%</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card p-3 shadow-sm h-100 border-0" style="background:#1e293b; border-left:4px solid #10b981 !important; border-radius:14px;">
                <span class="text-muted d-block small font-weight-bold text-uppercase">Volume Points</span>
                <h4 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($volumePoints) }}</h4>
            </div>
        </div>
    </div>

    {{-- Next payout preview --}}
    <div class="card border-0 shadow-sm mb-4 p-4" style="background:linear-gradient(135deg,#0f172a,#1e293b); border-radius:14px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="text-white font-weight-bold mb-1"><i class="fas fa-calendar-check text-warning mr-2"></i>Next Monday Payout ({{ $nextMonday->format('Y-m-d') }})</h5>
                @if($willPay && $isEligible)
                    <span class="text-slate-200 small">
                        Weaker side {{ number_format($weaker, 2) }} × 10% = <strong class="text-info">${{ number_format($binaryPreview, 2) }}</strong>
                        + Direct Sponsors <strong class="text-warning">${{ number_format($accruedDirect, 2) }}</strong>
                    </span>
                @elseif(!$isEligible)
                    <span class="text-warning small">No payout — accept the Affiliate Terms first. Your volumes and bonuses are waiting.</span>
                @else
                    <span class="text-muted small">No payout — one side has 0 volume. Side matching requires volume on BOTH legs; everything carries forward.</span>
                @endif
            </div>
            <div class="text-right">
                <span class="d-block text-muted small text-uppercase font-weight-bold">To Cashout</span>
                <h2 class="font-weight-bold {{ ($willPay && $isEligible) ? 'text-success' : 'text-muted' }} mb-0">
                    ${{ number_format(($willPay && $isEligible) ? $payoutPreview : 0, 2) }}
                </h2>
                <small class="text-muted">Paid to date: ${{ number_format($paidTotal, 2) }}</small>
            </div>
        </div>
    </div>

    {{-- Referral purchases awaiting activation --}}
    @if(isset($pendingActivations) && $pendingActivations->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4 p-4" style="background:#1e2a1e; border:1px solid #14532d !important; border-radius:14px;">
            <h5 class="text-white font-weight-bold mb-2"><i class="fas fa-hourglass-half text-warning mr-2"></i>Referral Purchases Awaiting Activation ({{ $pendingActivations->count() }})</h5>
            <p class="text-muted small mb-3">Your direct referrals bought these FOM package codes but have NOT activated them yet. Volume and bonuses accrue only when a code is <strong>activated</strong>.</p>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                    <thead class="bg-dark text-muted text-uppercase text-xs">
                        <tr><th>Bought On</th><th>Referral</th><th>Package</th><th>Price</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pendingActivations as $pa)
                            <tr class="border-bottom border-secondary">
                                <td class="text-light"><small>{{ $pa->created_at ? $pa->created_at->format('Y-m-d H:i') : '—' }}</small></td>
                                <td class="text-info font-weight-bold">{{ optional(\App\Models\User::find($pa->user_id))->user ?? '#' . $pa->user_id }}</td>
                                <td class="text-warning font-weight-bold">{{ strtoupper($pa->package) }}</td>
                                <td class="text-light">${{ number_format((float) $pa->price, 2) }}</td>
                                <td><span class="badge bg-warning text-dark px-2 py-1">Not Activated Yet</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Per-level summary --}}
    <div class="card border-0 shadow-sm mb-4 p-4" style="background:#0f172a; border-radius:14px;">
        <h5 class="text-white font-weight-bold mb-3"><i class="fas fa-layer-group text-info mr-2"></i>Referrals By Level</h5>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                <thead class="bg-dark text-muted text-uppercase text-xs">
                    <tr><th>Level</th><th>Volume Share</th><th>Referral Purchases</th><th>Direct Bonuses Earned</th></tr>
                </thead>
                <tbody>
                    @forelse($levelSummary as $lv)
                        <tr class="border-bottom border-secondary">
                            <td class="font-weight-bold text-warning">L{{ $lv->level }} {{ $lv->level == 1 ? '(Direct)' : '(Indirect)' }}</td>
                            <td class="text-light">{{ \App\Services\FomReferralService::LEVEL_SHARES[$lv->level] ?? 0 }}%</td>
                            <td class="text-info font-weight-bold">{{ $lv->cnt }}</td>
                            <td class="text-success font-weight-bold">{{ $lv->level == 1 ? '$' . number_format($lv->direct_total, 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted py-4">No FOM referrals yet. Share your referral link to start earning!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detailed history --}}
    <div class="card border-0 shadow-sm p-4" style="background:#0f172a; border-radius:14px;">
        <h5 class="text-white font-weight-bold mb-3"><i class="fas fa-history text-info mr-2"></i>Referral History</h5>
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                <thead class="bg-dark text-muted text-uppercase text-xs">
                    <tr><th>Date</th><th>From</th><th>Level</th><th>Package Price</th><th>Direct Bonus</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr class="border-bottom border-secondary">
                            <td class="text-light"><small>{{ $r->created_at->format('Y-m-d H:i') }}</small></td>
                            <td class="text-info font-weight-bold">{{ $r->sourceUser->user ?? $r->sourceUser->name ?? '#' . $r->source_user_id }}</td>
                            <td class="text-warning font-weight-bold">L{{ $r->level }}</td>
                            <td class="text-light">${{ number_format($r->source_amount, 2) }}</td>
                            <td class="text-success font-weight-bold">{{ $r->level == 1 ? '$' . number_format($r->bonus_amount, 2) : '—' }}</td>
                            <td>
                                @if($r->status === 'paid')
                                    <span class="badge bg-success text-white px-2 py-1">Paid</span>
                                @elseif($r->status === 'accrued')
                                    <span class="badge bg-warning text-dark px-2 py-1">Awaiting Monday Match</span>
                                @elseif($r->status === 'ineligible')
                                    <span class="badge bg-danger text-white px-2 py-1" title="Accept the Affiliate Terms to earn commissions">No Commission — Terms Not Accepted</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1">{{ ucfirst($r->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted py-4">No referral records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rows->hasPages())
            <div class="d-flex justify-content-center pt-3">{{ $rows->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>

</div>
</div>

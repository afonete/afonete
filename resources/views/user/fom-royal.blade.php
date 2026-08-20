<div class="wrapper">
@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-1"><i class="fas fa-crown text-warning mr-2"></i> Royal Leader Bonus</h3>
    <p class="text-muted mb-3" style="font-size: 0.9rem;">
        VIP1–VIP5 bonuses for FOM Licence Miner leaders — build Direct Sponsors and Volume Bonus earnings
        inside your Duration window to win a focoin Prize Pool plus an Auto Promotion package.
    </p>

    {{-- 14-day rule status --}}
    @if($eligibility['eligible'])
        <div class="alert alert-success shadow-sm font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-check-circle mr-1"></i> {{ $eligibility['reason'] }}
            <small class="d-block font-weight-normal mt-1">Your Duration windows count from your Starter activation: {{ \Carbon\Carbon::parse($eligibility['starter_at'])->format('d M Y') }}.</small>
        </div>
    @else
        <div class="alert alert-warning shadow-sm font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-exclamation-triangle mr-1"></i> 14 DAYS RULE: {{ $eligibility['reason'] }}
        </div>
    @endif

    {{-- Tier cards --}}
    @foreach($tiers as $tier)
        @php($award = $myAwards->get($tier->id))
        @php($prog = $progress[$tier->id] ?? null)
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px; {{ $award && $award->status === 'approved' ? 'border-left: 5px solid #28a745 !important;' : '' }}">
            <div class="card-header bg-light font-weight-bold d-flex justify-content-between align-items-center flex-wrap">
                <span><i class="fas fa-crown text-warning mr-1"></i> {{ $tier->name }}</span>
                <span>
                    @if($award)
                        @if($award->status === 'approved')
                            <span class="badge badge-success">WON — {{ number_format((float) $award->tokens_paid, 2) }} focoin paid</span>
                        @elseif($award->status === 'pending')
                            <span class="badge badge-warning text-dark">PENDING ADMIN APPROVAL</span>
                        @else
                            <span class="badge badge-secondary">REJECTED</span>
                        @endif
                    @elseif($prog && $prog['qualified'])
                        <span class="badge badge-info">QUALIFIED — awaiting detection</span>
                    @endif
                </span>
            </div>
            <div class="card-body py-3">
                <div class="row" style="font-size: 0.85rem;">
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Direct Sponsor</span>
                        <strong>{{ $tier->sponsorsLabel() }}</strong>
                    </div>
                    <div class="col-md-2 mb-2">
                        <span class="text-muted d-block">Duration</span>
                        <strong>{{ $tier->duration_days }} days</strong>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="text-muted d-block">Bonus Earn Requirement</span>
                        <strong>${{ number_format((float) $tier->vb_earn_required, 0) }} from volume bonus (VB)</strong>
                    </div>
                    <div class="col-md-2 mb-2">
                        <span class="text-muted d-block">Prize Pool</span>
                        <strong class="text-success">${{ number_format((float) $tier->prize_pool_usd, 0) }} worth of focoin</strong>
                    </div>
                    <div class="col-md-2 mb-2">
                        <span class="text-muted d-block">Auto Promotion</span>
                        <strong>{{ ucwords(strtolower($tier->promo_hold_package)) }} → {{ ucwords(strtolower($tier->promo_grant_package)) }}</strong>
                    </div>
                </div>

                @if($prog && !$award)
                    <hr class="my-2">
                    <div class="d-flex flex-wrap" style="gap: 6px;">
                        @foreach($prog['checks'] as $key => $c)
                            <span class="badge {{ $c['pass'] ? 'badge-success' : 'badge-light border' }}" style="font-size: 0.7rem;"
                                  title="need {{ is_numeric($c['need']) ? number_format((float) $c['need']) : $c['need'] }} · have {{ is_numeric($c['have']) ? number_format((float) $c['have']) : $c['have'] }}">
                                {{ str_replace(['sponsors_', '_'], ['', ' '], $key) }}:
                                {{ is_numeric($c['have']) ? number_format((float) $c['have']) : '' }}/{{ is_numeric($c['need']) ? number_format((float) $c['need']) : '' }}
                            </span>
                        @endforeach
                        @if(!empty($prog['window_end']))
                            <span class="badge badge-info" style="font-size: 0.7rem;">window ends {{ \Carbon\Carbon::parse($prog['window_end'])->format('d M Y') }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach

</div>
</div>
@include('user.footer')
</div>

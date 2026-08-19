<div class="wrapper">
@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-1"><i class="fas fa-medal text-warning mr-2"></i> My FOM Rank</h3>
    <p class="text-muted mb-3" style="font-size: 0.9rem;">
        17 ranks in 5 groups — Trainee to Royal Crown Diamond. Qualify on Volume Bonus, Team Turnover,
        Personal Turnover, criteria and FOM Licence; rewards are credited after admin approval.
    </p>

    {{-- Current status --}}
    <div class="row">
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block">Current Rank</small>
                <div class="font-weight-bold text-warning" style="font-size:1.3rem;">{{ $currentRankName ?? 'No Rank' }}</div>
                <small class="text-muted">{{ $currentGroup ?? '—' }}</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block">My Volume Bonus</small>
                <div class="font-weight-bold text-primary" style="font-size:1.3rem;">{{ number_format($myVb ?? 0) }}VB</div>
                <small class="text-muted">weaker side, all time</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block">Personal Turnover</small>
                <div class="font-weight-bold text-info" style="font-size:1.3rem;">${{ number_format($myPt ?? 0) }}</div>
                <small class="text-muted">direct referrals' FOM licences</small>
            </div></div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="card shadow-sm border-0 text-center h-100"><div class="card-body">
                <small class="text-muted d-block">Team Turnover</small>
                <div class="font-weight-bold text-success" style="font-size:1.3rem;">${{ number_format($myTt ?? 0) }}</div>
                <small class="text-muted">direct + indirect referrals' FOM</small>
            </div></div>
        </div>
    </div>

    {{-- 90-day rule banner --}}
    @if(($ninety['windows'] ?? 0) > 0 && ($ninety['missed'] ?? 0) > 0)
        <div class="alert alert-warning shadow-sm font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            90-DAY RULE: you have {{ $ninety['missed'] }} completed 90-day window{{ $ninety['missed'] > 1 ? 's' : '' }} without a new rank —
            your next rank's Volume Bonus requirement is multiplied ×{{ number_format($ninety['multiplier']) }}.
        </div>
    @elseif(($ninety['anchor'] ?? null))
        <div class="alert alert-info shadow-sm" style="border-radius: 10px; font-size: 0.85rem;">
            <i class="fas fa-info-circle mr-1"></i>
            90-DAY RULE: achieve a new rank inside every 90-day window (counted from your first FOM activation,
            {{ \Carbon\Carbon::parse($ninety['anchor'])->format('d M Y') }}) or the next rank's VB requirement doubles.
        </div>
    @endif

    {{-- Next rank progress --}}
    @if(isset($nextRank) && $nextRank)
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-header bg-light font-weight-bold">
                <i class="fas fa-arrow-up text-primary mr-1"></i> Next rank: {{ $nextRank->name }}
                <span class="badge badge-secondary ml-1">{{ $nextRank->group_name }}</span>
                <span class="float-right text-success font-weight-bold">Reward: ${{ number_format((float) $nextRank->reward, 0) }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="thead-light"><tr><th>Requirement</th><th>Needed</th><th>You have</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach(($nextChecks ?? []) as $key => $c)
                                <tr>
                                    <td class="font-weight-bold text-capitalize">{{ str_replace('_', ' ', $key) }}
                                        @if($key === 'volume_bonus' && ($ninety['missed'] ?? 0) > 0)
                                            <small class="text-danger d-block">×{{ number_format($ninety['multiplier']) }} (90-day rule)</small>
                                        @endif
                                    </td>
                                    <td>{{ is_numeric($c['need']) ? number_format((float) $c['need']) : $c['need'] }}</td>
                                    <td>{{ is_numeric($c['have']) ? number_format((float) $c['have']) : $c['have'] }}</td>
                                    <td>
                                        @if($c['pass'])
                                            <span class="badge badge-success">MET</span>
                                        @else
                                            <span class="badge badge-secondary">NOT YET</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(isset($nextRank->extra_bonuses) && $nextRank->extra_bonuses && $nextRank->extra_bonuses !== 'None')
                    <small class="text-muted d-block mt-2"><i class="fas fa-gift text-warning mr-1"></i> Extra bonuses: {{ $nextRank->extra_bonuses }}</small>
                @endif
            </div>
        </div>
    @endif

    {{-- My rank history --}}
    @if(isset($myRanks) && $myRanks->isNotEmpty())
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
            <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> My rank history</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light"><tr><th>Rank</th><th>Status</th><th>Reward</th><th>Detected</th></tr></thead>
                        <tbody>
                            @foreach($myRanks as $mr)
                                <tr>
                                    <td class="font-weight-bold">{{ $mr->rank_name }}</td>
                                    <td>
                                        @if($mr->status === 'approved')<span class="badge badge-success">APPROVED</span>
                                        @elseif($mr->status === 'pending')<span class="badge badge-warning text-dark">PENDING REVIEW</span>
                                        @else<span class="badge badge-secondary">REJECTED</span>@endif
                                    </td>
                                    <td>${{ number_format((float) $mr->reward_paid, 2) }}</td>
                                    <td><small>{{ optional($mr->detected_at)->format('d M Y') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Full ladder --}}
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-layer-group mr-1"></i> Rank ladder</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size: 0.82rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th><th>Group</th><th>Rank</th><th>Volume Bonus</th><th>Team Turnover</th>
                            <th>Qualification Criteria</th><th>Personal Turnover</th><th>Licence At Least</th><th>Reward</th><th>Extra Bonuses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($allRanks ?? collect()) as $r)
                            <tr class="{{ ($currentRankLevel ?? 0) >= $r->level ? 'table-success' : '' }}">
                                <td>{{ $r->level }}</td>
                                <td><small>{{ $r->group_name }}</small></td>
                                <td class="font-weight-bold">{{ $r->name }}</td>
                                <td>VB {{ number_format((float) $r->vb_required, 0) }}</td>
                                <td>${{ number_format((float) $r->team_turnover, 0) }}</td>
                                <td><small>{{ $r->criteria_label }}</small></td>
                                <td>${{ number_format((float) $r->personal_turnover, 0) }}</td>
                                <td><small class="font-weight-bold">{{ $r->licence_min }}</small></td>
                                <td class="text-success font-weight-bold">${{ number_format((float) $r->reward, 0) }}</td>
                                <td><small>{{ $r->extra_bonuses }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
@include('user.footer')
</div>

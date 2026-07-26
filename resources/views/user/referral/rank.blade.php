@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.referral._nav', ['active' => 'rank'])

    {{-- ── Current rank ── --}}
    <div class="card shadow-sm border-0 mt-2">
        <div class="card-body text-center py-4">
            @if($current)
                <div class="mb-2" style="font-size:2.4rem;">🏆</div>
                <h3 class="font-weight-bold text-primary mb-1">{{ $current->rank_name }}</h3>
                <small class="text-muted">Approved on {{ $current->reviewed_at->format('d M Y') }}</small>

                @if($current->congratulation_image)
                    <div class="mt-3">
                        <img src="{{ asset('storage/' . $current->congratulation_image) }}"
                             alt="Congratulations" class="img-fluid rounded shadow-sm" style="max-height:300px;">
                    </div>
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $current->congratulation_image) }}"
                           download="congratulations-{{ $current->rank_slug }}.jpg"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download mr-1"></i> Download Picture
                        </a>
                    </div>
                @endif
            @else
                <div class="mb-2" style="font-size:2.4rem;">🎯</div>
                <h3 class="font-weight-bold text-muted mb-1">No rank yet</h3>
                <small class="text-muted">Build your downline to unlock ranks and earn rewards.</small>
            @endif
        </div>
    </div>

    {{-- ── Progress to next ranks ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-chart-line mr-1"></i> Rank Progress</div>
        <div class="card-body">
            <div class="row">
                @foreach($allRanks as $rank)
                    @php $info = $progress[$rank->slug] ?? null; @endphp
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded {{ $info['met'] ? 'bg-success text-white' : 'border' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="font-weight-bold mb-0">{{ $rank->name }}</h5>
                                <span class="badge {{ $info['met'] ? 'badge-light' : 'badge-primary' }}">
                                    {{ $info['met'] ? '✓ Qualified' : $rank->rewardLabel() }}
                                </span>
                            </div>
                            @if(!$info['met'] && $info)
                                <ul class="small mb-0">
                                    @if($rank->min_active_direct_referrals > 0)
                                        <li>{{ $info['actual']['active_direct_referrals'] }} / {{ $rank->min_active_direct_referrals }} active direct referrals</li>
                                    @endif
                                    @if($rank->min_associates_from_direct > 0)
                                        <li>{{ $info['actual']['associates_from_direct'] }} / {{ $rank->min_associates_from_direct }} of your directs are Associates</li>
                                    @endif
                                    @if($rank->min_directors_from_direct > 0)
                                        <li>{{ $info['actual']['directors_from_direct'] }} / {{ $rank->min_directors_from_direct }} of your directs are Directors</li>
                                    @endif
                                    @if($rank->min_regional_supervisors_from_direct > 0)
                                        <li>{{ $info['actual']['regional_supervisors_from_direct'] }} / {{ $rank->min_regional_supervisors_from_direct }} of your directs are Regional Supervisors</li>
                                    @endif
                                    @if($rank->min_direct_referral_investment > 0)
                                        <li>${{ number_format($info['actual']['direct_referral_investment'], 2) }} / ${{ number_format($rank->min_direct_referral_investment, 0) }} direct-ref investment</li>
                                    @endif
                                    @if($rank->min_total_investment > 0)
                                        <li>${{ number_format($info['actual']['total_investment'], 2) }} / ${{ number_format($rank->min_total_investment, 0) }} total network investment</li>
                                    @endif
                                </ul>
                            @elseif($info['met'])
                                <small>Your application will be reviewed by admin. Reward: <strong>{{ $rank->rewardLabel() }}</strong></small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── History ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Rank History</div>
        <div class="card-body p-0">
            @if($history->isEmpty())
                <p class="text-muted p-3 mb-0">No rank history yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Rank</th><th>Status</th><th>Detected</th><th>Reviewed</th><th>Reward</th><th>Picture</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                            <tr>
                                <td><strong>{{ $h->rank_name }}</strong></td>
                                <td>{!! $h->statusBadge() !!}</td>
                                <td><small>{{ optional($h->detected_at)->format('d M Y') ?? '—' }}</small></td>
                                <td><small>{{ optional($h->reviewed_at)->format('d M Y') ?? '—' }}</small></td>
                                <td>${{ number_format($h->reward_amount, 2) }}</td>
                                <td>
                                    @if($h->congratulation_image)
                                        <a href="{{ asset('storage/' . $h->congratulation_image) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ asset('storage/' . $h->congratulation_image) }}" download
                                           class="btn btn-sm btn-outline-success">Download</a>
                                    @else — @endif
                                </td>
                                <td><small class="text-muted">{{ $h->admin_notes ?? '—' }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center border-top">
                {{ $history->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

</div>
</div>

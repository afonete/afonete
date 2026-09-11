@include('user.user-dashboard-base')

<style>
    .fc-rank-card {
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: 2px solid rgba(255,255,255,0.15);
    }
    .fc-rank-card.silver    { background: linear-gradient(135deg, #5a6370 0%, #2c313a 100%); }
    .fc-rank-card.gold      { background: linear-gradient(135deg, #b8860b 0%, #5a4108 100%); border-color:#d4af37; }
    .fc-rank-card.diamond   { background: linear-gradient(135deg, #4a9ab5 0%, #1a3c4d 100%); border-color:#b9f2ff; }
    .fc-rank-card.ambassador{ background: linear-gradient(135deg, #7b1fa2 0%, #3a0b4e 100%); border-color:#ce93d8; }
    .fc-rank-card.locked    { background: #2c313a; opacity: 0.7; }

    .fc-pin-badge {
        display:inline-block;
        padding: 5px 14px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 0.72rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .pin-silver     { background:#c0c0c0; color:#000; }
    .pin-gold       { background:#d4af37; color:#000; }
    .pin-diamond    { background:#b9f2ff; color:#000; }
    .pin-ambassador { background:#9c27b0; color:#fff; }

    .fc-progress {
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        height: 8px;
        overflow:hidden;
        margin-top: 4px;
    }
    .fc-progress > span {
        display:block; height:100%;
        background: linear-gradient(90deg,#ffd700,#fff8a8);
        transition: width .6s ease;
    }
    .fc-reward-row {
        display:flex; justify-content:space-between; gap: 1rem;
        padding: 6px 0; border-bottom: 1px dashed rgba(255,255,255,0.15);
        font-size: 0.88rem;
    }
    .fc-reward-row:last-child { border-bottom:none; }
    .fc-status-pill {
        display:inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .status-active    { background:#17a2b8; }
    .status-pending   { background:#ffc107; color:#000; }
    .status-completed { background:#28a745; }
    .status-expired   { background:#dc3545; }
    .status-locked    { background:#6c757d; }
</style>

<div class="content-wrapper" style="background-color:#0f172a; min-height:100vh;">
<div class="container-fluid py-4">

    <div style="background:#fff; border-radius:12px; padding:0.25rem;" class="mb-3">
        @include('user.referral._nav', ['active' => 'fc-streamline'])
    </div>

    <h3 class="text-white font-weight-bold mb-3"><i class="fas fa-crown text-warning mr-2"></i> FC VIP Streamline Ranks</h3>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Summary --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase">Direct FC Referrals</div>
                    <div class="display-4 font-weight-bold text-warning">{{ $directFc }}</div>
                    <div class="small text-muted">Your personally-sponsored FC VIP members</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase">Team Club (All-Depth FC)</div>
                    <div class="display-4 font-weight-bold text-warning">{{ number_format($teamFc) }}</div>
                    <div class="small text-muted">Total FC-paid members in your downline</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow-sm" style="border-radius:14px;">
                <div class="card-body text-center">
                    <div class="text-muted small text-uppercase">Pins Earned</div>
                    <div class="h4 font-weight-bold mt-2">
                        <span class="badge pin-silver mr-1"><i class="fas fa-medal"></i> {{ $pinCounts['silver'] }}</span>
                        <span class="badge pin-gold mr-1"><i class="fas fa-medal"></i> {{ $pinCounts['gold'] }}</span>
                        <span class="badge pin-diamond mr-1"><i class="fas fa-gem"></i> {{ $pinCounts['diamond'] }}</span>
                        <span class="badge pin-ambassador"><i class="fas fa-star"></i> {{ $pinCounts['ambassador'] }}</span>
                    </div>
                    <div class="small text-muted mt-2">Completed pins held in your team</div>
                </div>
            </div>
        </div>
    </div>

    @if($activeChallenge)
        <div class="alert alert-info border-0 shadow-sm" style="border-radius:12px;">
            <strong><i class="fas fa-stopwatch mr-1"></i> Challenge Active:</strong>
            Your <strong>{{ ucfirst($activeChallenge->rank_pin) }} ({{ \App\Models\FcStreamlineRank::RANKS[$activeChallenge->rank_level]['title'] }})</strong>
            challenge is in progress — you have <strong>{{ $activeChallenge->daysRemaining() }} days</strong> left to hit OWN FC + TEAM CLUB targets.
            Deadline: <strong>{{ $activeChallenge->deadline_at ? $activeChallenge->deadline_at->format('d M Y H:i') : '—' }}</strong>.
        </div>
    @endif

    @if($pendingReview)
        <div class="alert alert-warning border-0 shadow-sm" style="border-radius:12px;">
            <strong><i class="fas fa-hourglass-half mr-1"></i> Pending Admin Verification:</strong>
            You have met the targets for <strong>{{ ucfirst($pendingReview->rank_pin) }}</strong>.
            Your completion is queued for admin review; once verified, your reward and tokens will be credited automatically.
        </div>
    @endif

    <div class="row">
        @foreach($ranksData as $rank)
            @php $pinClass = 'pin-' . $rank['pin']; $cardClass = $rank['pin']; @endphp
            <div class="col-12 col-md-6 col-xl-3 mb-4">
                <div class="fc-rank-card {{ $cardClass }} {{ in_array($rank['status'], ['locked','expired']) ? 'locked' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="fc-pin-badge {{ $pinClass }}">
                            <i class="fas fa-medal mr-1"></i>{{ ucfirst($rank['pin']) }}
                        </span>
                        @if($rank['status'] === 'active')
                            <span class="fc-status-pill status-active">Active</span>
                        @elseif($rank['status'] === 'pending_admin')
                            <span class="fc-status-pill status-pending">Pending Review</span>
                        @elseif($rank['status'] === 'completed')
                            <span class="fc-status-pill status-completed"><i class="fas fa-check mr-1"></i>Earned</span>
                        @elseif($rank['status'] === 'expired')
                            <span class="fc-status-pill status-expired">Expired</span>
                        @else
                            <span class="fc-status-pill status-locked">Locked</span>
                        @endif
                    </div>

                    <h5 class="font-weight-bold mb-1 mt-2">{{ $rank['title'] }}</h5>
                    <div class="small opacity-75 mb-3">Level {{ $rank['level'] }} FC Streamline Rank</div>

                    {{-- Period --}}
                    @if($rank['is_current'] && $rank['days_remaining'] !== null)
                        <div class="rounded p-2 mb-3 text-center" style="background:rgba(255,255,255,0.12); border-radius:8px;">
                            <div class="small opacity-75">DAYS REMAINING</div>
                            <div class="h3 font-weight-bold mb-0 {{ $rank['days_remaining'] <= 7 ? 'text-danger' : 'text-warning' }}">
                                {{ $rank['days_remaining'] }}
                            </div>
                            <div class="small opacity-75">of 65-day period</div>
                        </div>
                    @endif

                    {{-- Activation threshold --}}
                    <div class="mb-2">
                        @if($rank['activation_direct'] > 0)
                            <div class="d-flex justify-content-between small">
                                <span><i class="fas fa-key mr-1"></i>RANK ACTIVATION</span>
                                @if($rank['activation_direct_met'] && $rank['activation_pins_met'])
                                    <span class="text-success"><i class="fas fa-check"></i> Met</span>
                                @else
                                    <span class="text-warning">{{ $rank['direct_now'] }} / {{ $rank['activation_direct'] }} direct FC</span>
                                @endif
                            </div>
                            <div class="fc-progress"><span style="width:{{ min(100, round(100 * $rank['direct_now'] / max(1,$rank['activation_direct']))) }}%"></span></div>
                            @if($rank['activation_pins'] > 0)
                                <div class="d-flex justify-content-between small mt-1">
                                    <span class="opacity-75">{{ ucfirst($rank['activation_pin_type']) }} pins in team</span>
                                    <span>{{ $rank['pins_now'] }} / {{ $rank['activation_pins'] }}</span>
                                </div>
                            @endif
                        @else
                            <div class="d-flex justify-content-between small">
                                <span><i class="fas fa-key mr-1"></i>RANK ACTIVATION</span>
                                @if($rank['activation_pins_met'])
                                    <span class="text-success"><i class="fas fa-check"></i> Met</span>
                                @else
                                    <span class="text-warning">{{ $rank['pins_now'] }} / {{ $rank['activation_pins'] }} {{ ucfirst($rank['activation_pin_type']) }} pins</span>
                                @endif
                            </div>
                            <div class="fc-progress"><span style="width:{{ min(100, round(100 * $rank['pins_now'] / max(1,$rank['activation_pins']))) }}%"></span></div>
                            <div class="small opacity-75 mt-1">Auto-starts the moment {{ $rank['activation_pins'] }} {{ ucfirst($rank['activation_pin_type']) }} pins exist anywhere in your downline.</div>
                        @endif
                    </div>

                    {{-- OWN FC target --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-user-check mr-1"></i>OWN FC / DIRECT</span>
                            <span class="{{ $rank['own_met'] ? 'text-success' : '' }}">{{ $rank['direct_now'] }} / {{ $rank['own_fc_target'] }}</span>
                        </div>
                        <div class="fc-progress"><span style="width:{{ $rank['own_fc_target'] > 0 ? min(100, round(100 * $rank['direct_now'] / $rank['own_fc_target'])) : 0 }}%"></span></div>
                    </div>

                    {{-- TEAM CLUB target --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-users mr-1"></i>TEAM CLUB (all-depth)</span>
                            <span class="{{ $rank['club_met'] ? 'text-success' : '' }}">{{ number_format($rank['team_now']) }} / {{ number_format($rank['team_club_target']) }}</span>
                        </div>
                        <div class="fc-progress"><span style="width:{{ $rank['team_club_target'] > 0 ? min(100, round(100 * $rank['team_now'] / $rank['team_club_target'])) : 0 }}%"></span></div>
                    </div>

                    {{-- Rewards --}}
                    <div class="p-2 rounded" style="background:rgba(0,0,0,0.25); border-radius:8px;">
                        <div class="fc-reward-row">
                            <span class="opacity-75"><i class="fas fa-dollar-sign mr-1"></i>Cash Reward</span>
                            <strong class="text-warning">${{ number_format($rank['reward_usd'], 0) }}</strong>
                        </div>
                        <div class="fc-reward-row">
                            <span class="opacity-75"><i class="fas fa-coins mr-1"></i>Token Reward</span>
                            <strong class="text-info">{{ number_format($rank['reward_tokens']) }}</strong>
                        </div>
                        <div class="fc-reward-row">
                            <span class="opacity-75"><i class="fas fa-clock mr-1"></i>Period</span>
                            <strong>65 days</strong>
                        </div>
                    </div>

                    @if($rank['status'] === 'expired')
                        <div class="mt-3 text-center small text-danger">
                            <i class="fas fa-ban mr-1"></i>Challenge expired — rank forfeited (sequential progression halted).
                        </div>
                    @elseif($rank['locked_out'])
                        <div class="mt-3 text-center small text-warning">
                            <i class="fas fa-lock mr-1"></i>Lower rank expired — progression locked.
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
</div>

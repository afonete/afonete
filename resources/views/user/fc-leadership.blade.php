@include('user.user-dashboard-base')

<style>
    .fc-lead-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #3b2f0a 100%);
        border-radius: 16px;
        padding: 2rem;
        color: #fff;
        border: 1px solid rgba(212,175,55,0.35);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .fc-lead-hero::before {
        content: "👑"; position:absolute; right:-20px; top:-30px;
        font-size: 12rem; opacity:0.07; pointer-events:none;
    }
    .fc-gold-stat {
        background: rgba(0,0,0,0.35);
        border: 1px solid rgba(212,175,55,0.25);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }
    .fc-tier-row {
        background: #fff;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        margin-bottom: 0.75rem;
        border-left: 5px solid #cbd5e1;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        transition: transform .2s;
    }
    .fc-tier-row.earned { border-left-color: #16a34a; background: linear-gradient(90deg, #f0fdf4, #ffffff 60%); }
    .fc-tier-row.current { border-left-color: #d4af37; background: linear-gradient(90deg, #fffbeb, #ffffff 60%); box-shadow: 0 4px 14px rgba(212,175,55,0.18); }
    .fc-tier-row.locked { opacity: 0.65; }
    .fc-pill {
        display:inline-block; padding:4px 12px; border-radius:999px;
        font-size:0.72rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase;
    }
    .fc-pill.earned { background:#16a34a; color:#fff; }
    .fc-pill.current { background:#d4af37; color:#000; }
    .fc-pill.locked { background:#94a3b8; color:#fff; }
    .fc-progress-mini {
        background:#f1f5f9; border-radius:8px; height:8px; overflow:hidden; margin-top:6px;
    }
    .fc-progress-mini > span {
        display:block; height:100%;
        background: linear-gradient(90deg, #d4af37, #fde68a);
    }
</style>

<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.referral._nav', ['active' => 'fc-leadership'])

    <h3 class="font-weight-bold mb-3 mt-3"><i class="fas fa-crown text-warning mr-2"></i> FC VIP Leadership Bonus</h3>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    {{-- Hero --}}
    <div class="fc-lead-hero">
        <h4 class="font-weight-bold mb-1" style="color:#fcd34d;">Lifetime FC Leadership Progress</h4>
        <p class="opacity-75 mb-4" style="font-size:0.9rem;">
            Earn <strong style="color:#fcd34d;">+100 VB</strong> for every direct FC VIP referral. When your lifetime referrals &amp; VB pool
            cross each milestone below, a cash reward is automatically added to your referral bonus and becomes withdrawable the next Monday.
        </p>

        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="fc-gold-stat">
                    <div class="small text-uppercase opacity-75">Direct FC Referrals</div>
                    <div class="display-4 font-weight-bold" style="color:#fcd34d;">{{ (int) $progress->direct_fc_count }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="fc-gold-stat">
                    <div class="small text-uppercase opacity-75">VB Volume Bonus Pool</div>
                    <div class="display-4 font-weight-bold" style="color:#fcd34d;">{{ number_format((int) $progress->volume_bonus_vb) }}</div>
                    <div class="small opacity-75">VB (+100 per direct FC)</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fc-gold-stat">
                    <div class="small text-uppercase opacity-75">Tiers Earned</div>
                    <div class="display-4 font-weight-bold" style="color:#fcd34d;">{{ (int) $progress->highest_tier_paid }} <span class="h5 opacity-70">/ {{ count($tiers) }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tier list --}}
    @foreach($tiers as $i => $tier)
        @php
            $earned = ((int) $progress->highest_tier_paid >= $i);
            $isCurrent = !$earned && $i === $nextTierIdx;
            $class = $earned ? 'earned' : ($isCurrent ? 'current' : 'locked');
            $directPct = min(100, round(100 * $progress->direct_fc_count / max(1, $tier['direct_required'])));
            $poolPct   = min(100, round(100 * $progress->volume_bonus_vb / max(1, $tier['pool_vb'])));
        @endphp
        <div class="fc-tier-row {{ $class }}">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="h5 font-weight-bold mb-0 mr-2">Tier {{ $i }}</span>
                        @if($earned)
                            <span class="fc-pill earned"><i class="fas fa-check mr-1"></i>Earned</span>
                        @elseif($isCurrent)
                            <span class="fc-pill current">In Progress</span>
                        @else
                            <span class="fc-pill locked">Locked</span>
                        @endif
                    </div>
                    <div class="text-muted small">
                        <strong class="text-dark">{{ $tier['direct_required'] }} direct FC referrals</strong> ·
                        <strong class="text-dark">{{ number_format($tier['pool_vb']) }} VB pool</strong> ·
                        payout rate <strong class="text-dark">{{ rtrim(rtrim(number_format($tier['pct'], 2), '0'), '.') }}%</strong>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-success font-weight-bold" style="font-size:1.4rem; line-height:1;">
                        ${{ number_format($tier['reward_vb'], 2) }}
                    </div>
                    <div class="small text-muted">cash reward</div>
                </div>
            </div>

            @if($isCurrent)
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between small">
                            <span>Direct FC</span><span><strong>{{ $progress->direct_fc_count }}</strong> / {{ $tier['direct_required'] }}</span>
                        </div>
                        <div class="fc-progress-mini"><span style="width:{{ $directPct }}%;"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between small">
                            <span>VB Pool</span><span><strong>{{ number_format($progress->volume_bonus_vb) }}</strong> / {{ number_format($tier['pool_vb']) }}</span>
                        </div>
                        <div class="fc-progress-mini"><span style="width:{{ $poolPct }}%;"></span></div>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    @if($allDone)
        <div class="alert alert-warning text-center font-weight-bold">
            🏆 Congratulations! You have earned every FC Leadership tier.
        </div>
    @endif

    {{-- How it works --}}
    <div class="card mt-4 border-0 shadow-sm" style="background:#f8fafc; border-radius:12px;">
        <div class="card-body">
            <h6 class="font-weight-bold"><i class="fas fa-info-circle text-warning mr-2"></i>How FC Leadership rewards work</h6>
            <ul class="mb-0 text-muted small" style="line-height:1.8;">
                <li>You earn <strong>+100 VB (Volume Bonus)</strong> for every personal referral who purchases an FC VIP package (on top of your standard 10% L1 cash commission).</li>
                <li>Tier rewards are <strong>lifetime, one-time, incremental</strong> — they unlock automatically the moment you hit each threshold, never to be reset.</li>
                <li>Each cash reward is added to your <strong>Referral Bonus</strong> balance and becomes available for withdrawal to your Cashout wallet on the next Monday (same pipeline as weekly referral commissions).</li>
                <li>VB itself is a non-cash qualification counter — only the tier lump sums ($3 / $15 / $40 / $150 / $750 / $2,000) are paid as USD cash.</li>
            </ul>
        </div>
    </div>

</div>
</div>

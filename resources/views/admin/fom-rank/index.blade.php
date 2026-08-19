@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900"><i class="fas fa-medal text-amber-500 mr-2"></i>FOM Ranks</h1>
            <p class="text-sm text-slate-500">
                17-rank ladder (Trainee → Royal Crown Diamond) in 5 groups. The system detects qualification
                (VB / turnovers / criteria / licence, with the 90-day VB-doubling rule); you approve to pay the reward.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.fom-rank.detect') }}">
            @csrf
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-3 py-2">
                <i class="fas fa-search mr-1"></i> Run detection sweep now
            </button>
        </form>
    </div>

    {{-- Totals --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-amber-500">{{ $totals['pending'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Pending approvals</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-emerald-600">{{ $totals['approved'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Ranks approved</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-blue-600">${{ number_format($totals['rewards_paid'], 2) }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Rewards paid</div>
        </div>
    </div>

    {{-- Pending approvals --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h2 class="text-lg font-bold text-slate-900 mb-3"><i class="fas fa-hourglass-half text-amber-500 mr-1"></i> Pending approvals</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">Reward</th>
                        <th class="px-4 py-3">Detected</th>
                        <th class="px-4 py-3">Checks</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $p)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->user->user ?? $p->user->name ?? ('#' . $p->user_id) }}</td>
                            <td class="px-4 py-3 font-bold">{{ $p->rank_name }} <span class="text-xs text-slate-400">(L{{ $p->rank_level }})</span></td>
                            <td class="px-4 py-3 text-emerald-600 font-bold">${{ number_format((float) optional($p->rank)->reward, 0) }}</td>
                            <td class="px-4 py-3"><small>{{ optional($p->detected_at)->format('Y-m-d H:i') }}</small></td>
                            <td class="px-4 py-3">
                                @foreach((array) ($p->criteria_snapshot ?? []) as $k => $c)
                                    <span class="text-xs px-1.5 py-0.5 rounded {{ !empty($c['pass']) ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ str_replace('_', ' ', $k) }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.fom-rank.approve', $p->id) }}" class="inline" onsubmit="return confirm('Approve {{ $p->rank_name }} and credit the reward to Cashout?');">
                                    @csrf
                                    <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-1.5">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.fom-rank.reject', $p->id) }}" class="inline" onsubmit="return confirm('Reject this rank detection?');">
                                    @csrf
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 font-semibold rounded-lg text-xs px-3 py-1.5">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">No pending rank detections.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $pending->appends(request()->except('pending_page'))->links() }}</div>
    </div>

    {{-- Rank ladder --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h2 class="text-lg font-bold text-slate-900 mb-3"><i class="fas fa-layer-group text-blue-500 mr-1"></i> Rank ladder (17 ranks · 5 groups)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-3 py-2">#</th>
                        <th class="px-3 py-2">Group</th>
                        <th class="px-3 py-2">Rank</th>
                        <th class="px-3 py-2">Volume Bonus</th>
                        <th class="px-3 py-2">Team Turnover</th>
                        <th class="px-3 py-2">Qualification Criteria</th>
                        <th class="px-3 py-2">Personal Turnover</th>
                        <th class="px-3 py-2">Licence At Least</th>
                        <th class="px-3 py-2">Reward</th>
                        <th class="px-3 py-2">Extra Bonuses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranks as $r)
                        <tr class="bg-white border-b">
                            <td class="px-3 py-2 font-bold text-slate-900">{{ $r->level }}</td>
                            <td class="px-3 py-2 text-xs">{{ $r->group_name }}</td>
                            <td class="px-3 py-2 font-bold text-slate-900">{{ $r->name }}</td>
                            <td class="px-3 py-2">VB {{ number_format((float) $r->vb_required, 0) }}</td>
                            <td class="px-3 py-2">${{ number_format((float) $r->team_turnover, 0) }}</td>
                            <td class="px-3 py-2 text-xs">{{ $r->criteria_label }}</td>
                            <td class="px-3 py-2">${{ number_format((float) $r->personal_turnover, 0) }}</td>
                            <td class="px-3 py-2 text-xs font-semibold">{{ $r->licence_min }}</td>
                            <td class="px-3 py-2 font-bold text-emerald-600">${{ number_format((float) $r->reward, 0) }}</td>
                            <td class="px-3 py-2 text-xs">{{ $r->extra_bonuses }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-xs text-slate-400 mt-2">
            90-DAY RULE: each completed 90-day window (from first FOM activation) without a new rank doubles that user's
            VB requirement for the next rank (×2 per missed window).
        </p>
    </div>

    {{-- Recent reviews --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
        <h2 class="text-lg font-bold text-slate-900 mb-3"><i class="fas fa-history text-slate-400 mr-1"></i> Recently reviewed</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Reward Paid</th>
                        <th class="px-4 py-3">Reviewed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $rrow)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $rrow->user->user ?? $rrow->user->name ?? ('#' . $rrow->user_id) }}</td>
                            <td class="px-4 py-3">{{ $rrow->rank_name }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $rrow->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($rrow->status) }}</span>
                            </td>
                            <td class="px-4 py-3">${{ number_format((float) $rrow->reward_paid, 2) }}</td>
                            <td class="px-4 py-3"><small>{{ optional($rrow->reviewed_at)->format('Y-m-d H:i') }}</small></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">Nothing reviewed yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $recent->appends(request()->except('recent_page'))->links() }}</div>
    </div>
</div>
@endsection

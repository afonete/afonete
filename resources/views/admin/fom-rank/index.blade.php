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

    {{-- Rank ladder — EDITABLE (spec §71) --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h2 class="text-lg font-bold text-slate-900 mb-1"><i class="fas fa-layer-group text-blue-500 mr-1"></i> Rank ladder (17 ranks · 5 groups) — editable</h2>
        <p class="text-xs text-slate-500 mb-4">
            All qualification and reward values are modifiable per rank. Advanced: the structured criteria can be
            edited as JSON (types: <code class="bg-slate-100 px-1 rounded">bonus</code>,
            <code class="bg-slate-100 px-1 rounded">active_directs</code>,
            <code class="bg-slate-100 px-1 rounded">ranks</code>); leave blank to keep the current rule.
        </p>
        @foreach($ranks as $r)
            <form method="POST" action="{{ route('admin.fom-rank.update', $r->id) }}" class="border border-slate-200 rounded-lg p-3 mb-3 {{ $r->is_active ? '' : 'opacity-60' }}">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                    <span class="font-extrabold text-slate-900">#{{ $r->level }} {{ $r->name }} <span class="text-xs font-normal text-slate-400">· {{ $r->group_name }}</span></span>
                    <span class="text-xs text-slate-400">VB {{ number_format((float) $r->vb_required, 0) }} · TT ${{ number_format((float) $r->team_turnover, 0) }} · PT ${{ number_format((float) $r->personal_turnover, 0) }} · {{ $r->licence_min }} · ${{ number_format((float) $r->reward, 0) }}</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Volume Bonus (VB)</label>
                        <input type="number" step="0.01" min="0" name="vb_required" value="{{ (float) $r->vb_required }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Team Turnover ($)</label>
                        <input type="number" step="0.01" min="0" name="team_turnover" value="{{ (float) $r->team_turnover }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Personal Turnover ($)</label>
                        <input type="number" step="0.01" min="0" name="personal_turnover" value="{{ (float) $r->personal_turnover }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Licence At Least</label>
                        <select name="licence_min" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                            @foreach(array_keys(\App\Models\FomRank::LICENCE_ORDER) as $lic)
                                <option value="{{ $lic }}" {{ strtoupper($r->licence_min) === $lic ? 'selected' : '' }}>{{ $lic }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Reward ($)</label>
                        <input type="number" step="0.01" min="0" name="reward" value="{{ (float) $r->reward }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Extra Bonuses</label>
                        <input type="text" name="extra_bonuses" value="{{ $r->extra_bonuses }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div class="col-span-2 md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Qualification Criteria (label)</label>
                        <input type="text" name="criteria_label" value="{{ $r->criteria_label }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div class="col-span-2 md:col-span-3">
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Criteria rule (JSON — blank keeps current)</label>
                        <input type="text" name="criteria_json" value="" placeholder='{{ json_encode($r->criteria_json) }}'
                               class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full font-mono">
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-4 py-2">Save {{ $r->name }}</button>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.fom-rank.toggle', $r->id) }}" class="mb-4 -mt-2 text-right">
                @csrf
                <button type="submit" class="text-xs font-semibold {{ $r->is_active ? 'text-red-600' : 'text-emerald-600' }} hover:underline">
                    {{ $r->is_active ? 'Deactivate' : 'Activate' }} {{ $r->name }}
                </button>
            </form>
        @endforeach
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

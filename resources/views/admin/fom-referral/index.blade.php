@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}</div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-lg text-xl"><i class="fas fa-network-wired"></i></div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">FOM Referral Management</h1>
                <p class="text-xs text-slate-500 mt-0.5">Binary volumes, direct sponsor accruals, volume points and the Monday payout.</p>
            </div>
        </div>
        <form action="{{ route('admin.fom-referral.run-weekly') }}" method="POST" onsubmit="return confirm('Run the FOM weekly payout now? This is idempotent — users with a match get weaker-side × 10% + accrued directs to cashout.');">
            @csrf
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-5 rounded-lg shadow-sm text-sm">
                <i class="fas fa-play mr-1"></i> Run Weekly Payout Now
            </button>
        </form>
    </div>

    {{-- Platform totals --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Left Volume</span><span class="text-lg font-extrabold text-blue-600">{{ number_format($totals['left_volume'], 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Right Volume</span><span class="text-lg font-extrabold text-violet-600">{{ number_format($totals['right_volume'], 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Accrued Directs</span><span class="text-lg font-extrabold text-amber-600">${{ number_format($totals['accrued_direct'], 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Paid Out</span><span class="text-lg font-extrabold text-emerald-600">${{ number_format($totals['paid_total'], 2) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Volume Points</span><span class="text-lg font-extrabold text-slate-700">{{ number_format($totals['volume_points']) }}</span></div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm"><span class="text-[11px] font-bold uppercase text-slate-400 block">Forfeited Rows</span><span class="text-lg font-extrabold text-red-500">{{ $totals['ineligible_cnt'] }}</span></div>
    </div>

    {{-- VOLUME CAP EXCESS (spec §70): totals of the VB clipped above each user's package cap --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h3 class="font-bold text-slate-900 mb-1"><i class="fas fa-compress-arrows-alt text-red-500 mr-1"></i> Volume Cap Excess</h3>
        <p class="text-xs text-slate-500 mb-3">
            Weekly volume-bonus payouts are clipped at each user's CURRENT FOM package cap — the excess above
            the cap is not paid and is reported here.
        </p>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-center">
                <span class="text-[11px] font-bold uppercase text-red-400 block">Total Cap Excess (all time)</span>
                <span class="text-xl font-extrabold text-red-600">${{ number_format($totals['cap_excess_total'] ?? 0, 2) }}</span>
            </div>
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-center">
                <span class="text-[11px] font-bold uppercase text-red-400 block">Cap Excess (this week)</span>
                <span class="text-xl font-extrabold text-red-600">${{ number_format($totals['cap_excess_week'] ?? 0, 2) }}</span>
            </div>
        </div>
        @if(isset($cappedRows) && $cappedRows->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                        <tr>
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Uncapped VB</th>
                            <th class="px-4 py-2">Cap</th>
                            <th class="px-4 py-2">Paid</th>
                            <th class="px-4 py-2">Excess (not paid)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cappedRows as $c)
                            <tr class="bg-white border-b">
                                <td class="px-4 py-2 font-semibold text-slate-900">{{ optional($c->user)->user ?? optional($c->user)->name ?? '—' }}</td>
                                <td class="px-4 py-2"><small>{{ \Carbon\Carbon::parse($c->date)->format('Y-m-d') }}</small></td>
                                <td class="px-4 py-2">${{ number_format($c->uncapped, 2) }}</td>
                                <td class="px-4 py-2">${{ number_format($c->cap, 2) }}</td>
                                <td class="px-4 py-2 text-emerald-600 font-bold">${{ number_format($c->paid, 2) }}</td>
                                <td class="px-4 py-2 text-red-600 font-bold">${{ number_format($c->forfeited, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400">No capped payouts yet.</p>
        @endif
    </div>

    {{-- FOM codes awaiting activation (no Payment → no referral accrual yet) --}}
    @if(isset($pendingActivations) && $pendingActivations->isNotEmpty())
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <h3 class="font-bold text-amber-800 mb-1"><i class="fas fa-hourglass-half mr-1"></i> FOM Codes Awaiting Activation ({{ $pendingActivations->count() }} recent)</h3>
            <p class="text-xs text-amber-700 mb-3">These purchases have NOT been activated — referral volume/bonuses accrue only at activation. If an upline reports "I can't see my referral's purchase", check here first.</p>
            <div class="overflow-x-auto">
                <table class="w-full text-sm bg-white rounded-lg">
                    <thead class="text-slate-500 uppercase text-xs border-b">
                        <tr><th class="px-3 py-2 text-left">Bought</th><th class="px-3 py-2 text-left">Buyer</th><th class="px-3 py-2 text-left">Code</th><th class="px-3 py-2 text-left">Package</th><th class="px-3 py-2 text-right">Price</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pendingActivations as $pa)
                            <tr class="border-b border-slate-50">
                                <td class="px-3 py-2 text-xs text-slate-500">{{ $pa->created_at ? $pa->created_at->format('Y-m-d H:i') : '—' }}</td>
                                <td class="px-3 py-2 font-semibold">{{ optional(\App\Models\User::find($pa->user_id))->user ?? '#' . $pa->user_id }}</td>
                                <td class="px-3 py-2 font-mono text-xs">{{ $pa->code }}</td>
                                <td class="px-3 py-2 font-bold text-amber-700">{{ strtoupper($pa->package) }}</td>
                                <td class="px-3 py-2 text-right">${{ number_format((float) $pa->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Per-user board --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
            <h2 class="font-bold text-slate-700">Users With FOM Referral Activity</h2>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search name / username / email"
                       class="bg-slate-50 border border-slate-300 rounded-lg px-3 py-1.5 text-sm w-64">
                <button class="bg-slate-700 text-white text-sm font-bold px-4 rounded-lg">Search</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-right">Left Vol</th>
                        <th class="px-4 py-3 text-right">Right Vol</th>
                        <th class="px-4 py-3 text-right">Next Binary (10%)</th>
                        <th class="px-4 py-3 text-right">Accrued Directs</th>
                        <th class="px-4 py-3 text-right">Vol. Points</th>
                        <th class="px-4 py-3 text-center">Eligible</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($board as $b)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <span class="font-bold text-slate-800 block">{{ $b->user->name }}</span>
                                <span class="text-xs text-slate-400">@ {{ $b->user->user }} · {{ $b->user->email }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-blue-600">{{ number_format($b->left, 2) }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-violet-600">{{ number_format($b->right, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $b->weaker > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $b->weaker > 0 ? '$' . number_format($b->next_binary, 2) : 'no match' }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-amber-600">${{ number_format($b->accrued, 2) }}</td>
                            <td class="px-4 py-3 text-right text-slate-700">{{ number_format($b->volume_points) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($b->eligible)
                                    <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-2 py-1 rounded border border-emerald-100">YES</span>
                                @else
                                    <span class="bg-red-50 text-red-600 text-xs font-bold px-2 py-1 rounded border border-red-100" title="Affiliate terms not accepted — will be skipped on Monday">NO</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.fom-referral.user', $b->user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg">Audit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">No FOM referral activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-slate-100">{{ $users->links('pagination::bootstrap-4') }}</div>
        @endif
    </div>

</div>
@endsection

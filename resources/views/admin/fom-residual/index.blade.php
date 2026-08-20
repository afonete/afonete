@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 font-semibold"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc pl-5 space-y-1 font-semibold">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900"><i class="fas fa-sitemap text-indigo-500 mr-2"></i>Residual Income Matching Bonus</h1>
            <p class="text-sm text-slate-500">
                Leaders with a FOM rank earn a weekly % of their ranked downlines' FOM Affiliate Bonus —
                L1 = first 4 ranked downlines (10%), then each member needs 2 ranked downlines per level (L2 8 → L6 128).
            </p>
        </div>
        <form method="POST" action="{{ route('admin.fom-residual.run') }}" onsubmit="return confirm('Run the weekly matching payout now? (Idempotent per leader per week.)');">
            @csrf
            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-semibold rounded-lg text-xs px-3 py-2">
                <i class="fas fa-play mr-1"></i> Run weekly matching now
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-indigo-600">${{ number_format($totalPaid, 2) }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Total matching income paid</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-slate-700">{{ $payouts->total() }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Weekly payout runs recorded</div>
        </div>
    </div>

    {{-- Pending level approvals (per-level admin gate) --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
            <h2 class="text-lg font-bold text-slate-900"><i class="fas fa-hourglass-half text-amber-500 mr-1"></i> Pending level approvals</h2>
            <form method="POST" action="{{ route('admin.fom-residual.detect') }}">
                @csrf
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-3 py-2">
                    <i class="fas fa-search mr-1"></i> Refresh queue
                </button>
            </form>
        </div>
        <p class="text-xs text-slate-500 mb-3">A leader's level only pays its weekly % AFTER you approve it here. The members listed are the ranked downlines that completed the level.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">Leader</th>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Qualified members</th>
                        <th class="px-4 py-3">Detected</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $a)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $a->user->user ?? $a->user->name ?? ('#' . $a->user_id) }}</td>
                            <td class="px-4 py-3 font-extrabold">L{{ $a->level }}</td>
                            <td class="px-4 py-3 text-xs">{{ implode(', ', array_slice((array) ($a->members_snapshot['names'] ?? []), 0, 12)) }}{{ count((array) ($a->members_snapshot['names'] ?? [])) > 12 ? '…' : '' }}</td>
                            <td class="px-4 py-3"><small>{{ optional($a->detected_at)->format('Y-m-d H:i') }}</small></td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.fom-residual.approve', $a->id) }}" class="inline"
                                      onsubmit="return confirm('Approve L{{ $a->level }} for this leader? They will earn the level % every Monday to Cashout.');">
                                    @csrf
                                    <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-1.5">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.fom-residual.reject', $a->id) }}" class="inline" onsubmit="return confirm('Reject this level?');">
                                    @csrf
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 font-semibold rounded-lg text-xs px-3 py-1.5">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No pending level approvals.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $approvals->appends(request()->except('approvals_page'))->links() }}</div>
    </div>

    {{-- Level configuration (modifiable) --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h2 class="text-lg font-bold text-slate-900 mb-1"><i class="fas fa-sliders-h text-blue-500 mr-1"></i> Level configuration</h2>
        <p class="text-xs text-slate-500 mb-4">All values modifiable: members required, ranked downlines per parent, and income %.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">Level</th>
                        <th class="px-4 py-3">Rank criteria (members required)</th>
                        <th class="px-4 py-3">Per parent</th>
                        <th class="px-4 py-3">Income (% of FOM Affiliate Bonus)</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $lv)
                        <tr class="bg-white border-b {{ $lv->is_active ? '' : 'opacity-60' }}">
                            <td class="px-4 py-3 font-extrabold text-slate-900">L{{ $lv->level }}</td>
                            <form method="POST" action="{{ route('admin.fom-residual.level.update', $lv->id) }}">
                                @csrf
                                @method('PUT')
                                <td class="px-4 py-3">
                                    <input type="number" name="required_members" min="1" value="{{ $lv->required_members }}"
                                           class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-24">
                                    <span class="text-xs text-slate-400">downlines with ranks</span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="per_parent" min="1" value="{{ $lv->per_parent }}"
                                           class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-16">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="0.001" name="income_percent" min="0" max="100" value="{{ (float) $lv->income_percent }}"
                                           class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-24"> %
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-bold px-2 py-1 rounded {{ $lv->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $lv->is_active ? 'ACTIVE' : 'OFF' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-3 py-1.5">Save</button>
                            </form>
                                    <form method="POST" action="{{ route('admin.fom-residual.level.toggle', $lv->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold {{ $lv->is_active ? 'text-red-600' : 'text-emerald-600' }} hover:underline">{{ $lv->is_active ? 'Deactivate' : 'Activate' }}</button>
                                    </form>
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payout audit --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
        <h2 class="text-lg font-bold text-slate-900 mb-3"><i class="fas fa-history text-slate-400 mr-1"></i> Weekly payouts</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">Leader</th>
                        <th class="px-4 py-3">Week</th>
                        <th class="px-4 py-3">Eligible Level</th>
                        <th class="px-4 py-3">Total Income</th>
                        <th class="px-4 py-3">Breakdown</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $p)
                        @php($d = json_decode((string) $p->transaction_details, true) ?: [])
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->user->user ?? $p->user->name ?? ('#' . $p->user_id) }}</td>
                            <td class="px-4 py-3">{{ $d['week'] ?? '—' }}</td>
                            <td class="px-4 py-3 font-bold">L{{ $d['eligible_level'] ?? '?' }}</td>
                            <td class="px-4 py-3 font-bold text-indigo-600">${{ number_format((float) ($d['total'] ?? 0), 4) }}</td>
                            <td class="px-4 py-3 text-xs">
                                @foreach((array) ($d['breakdown'] ?? []) as $b)
                                    <span class="px-1.5 py-0.5 bg-slate-100 rounded mr-1">L{{ $b['level'] }}: {{ $b['percent'] }}% of ${{ number_format((float) $b['members_bonus'], 2) }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-slate-400">No matching payouts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $payouts->links() }}</div>
    </div>
</div>
@endsection

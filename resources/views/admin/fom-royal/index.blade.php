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
            <h1 class="text-xl font-bold text-slate-900"><i class="fas fa-crown text-amber-500 mr-2"></i>Royal Leader Bonus</h1>
            <p class="text-sm text-slate-500">
                VIP1–VIP5 for FOM Licence Miner. 14-day rule: no Starter within 14 days of signup → not eligible.
                On approval you input the focoin price — the user gets Prize Pool ÷ price tokens + the Auto Promotion package.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.fom-royal.detect') }}">
            @csrf
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-3 py-2">
                <i class="fas fa-search mr-1"></i> Run detection sweep now
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-amber-500">{{ $totals['pending'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Pending approvals</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-emerald-600">{{ $totals['approved'] }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Bonuses approved</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm text-center">
            <div class="text-2xl font-extrabold text-blue-600">{{ number_format($totals['tokens_paid'], 2) }}</div>
            <div class="text-xs text-slate-500 font-semibold uppercase">Focoin paid out</div>
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
                        <th class="px-4 py-3">Tier</th>
                        <th class="px-4 py-3">Prize Pool</th>
                        <th class="px-4 py-3">Auto Promotion</th>
                        <th class="px-4 py-3">Window</th>
                        <th class="px-4 py-3 text-right">Approve (input focoin price)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pending as $p)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $p->user->user ?? $p->user->name ?? ('#' . $p->user_id) }}</td>
                            <td class="px-4 py-3 font-bold">{{ $p->tier_name }}</td>
                            <td class="px-4 py-3 text-emerald-600 font-bold">${{ number_format((float) optional($p->tier)->prize_pool_usd, 0) }} of focoin</td>
                            <td class="px-4 py-3 text-xs">{{ ucwords(strtolower((string) optional($p->tier)->promo_hold_package)) }} → {{ ucwords(strtolower((string) optional($p->tier)->promo_grant_package)) }}</td>
                            <td class="px-4 py-3"><small>{{ optional($p->window_start)->format('d M') }} – {{ optional($p->window_end)->format('d M Y') }}</small></td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.fom-royal.approve', $p->id) }}" class="inline-flex items-center gap-1"
                                      onsubmit="return confirm('Approve {{ $p->tier_name }} at this focoin price? Tokens + auto promotion will be granted.');">
                                    @csrf
                                    <input type="number" step="0.000001" min="0.000001" name="focoin_price" required placeholder="focoin price $"
                                           class="bg-slate-50 border border-slate-300 text-slate-900 text-xs rounded-lg p-1.5 w-28">
                                    <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-lg text-xs px-3 py-1.5">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.fom-royal.reject', $p->id) }}" class="inline" onsubmit="return confirm('Reject this award?');">
                                    @csrf
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 font-semibold rounded-lg text-xs px-3 py-1.5">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">No pending Royal Leader awards.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $pending->appends(request()->except('pending_page'))->links() }}</div>
    </div>

    {{-- Tier configuration (fully modifiable) --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <h2 class="text-lg font-bold text-slate-900 mb-1"><i class="fas fa-sliders-h text-blue-500 mr-1"></i> Tier configuration</h2>
        <p class="text-xs text-slate-500 mb-4">All qualification and reward values are editable. Sponsor rules format: <code class="bg-slate-100 px-1 rounded">5 ADVANCED, 3 PREMIUM, 2 MASTER</code></p>
        @foreach($tiers as $t)
            <form method="POST" action="{{ route('admin.fom-royal.tier.update', $t->id) }}" class="border border-slate-200 rounded-lg p-4 mb-3 {{ $t->is_active ? '' : 'opacity-60' }}">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                    <span class="font-extrabold text-slate-900">{{ $t->name }}</span>
                    <span class="text-xs text-slate-500">{{ $t->sponsorsLabel() }} · {{ $t->duration_days }} days · VB ${{ number_format((float) $t->vb_earn_required, 0) }} · ${{ number_format((float) $t->prize_pool_usd, 0) }} focoin · {{ ucwords(strtolower($t->promo_hold_package)) }} → {{ ucwords(strtolower($t->promo_grant_package)) }}</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Direct Sponsor rules</label>
                        <input type="text" name="sponsors" value="{{ collect($t->sponsors_json)->map(fn ($s) => $s['count'] . ' ' . $s['package'])->implode(', ') }}"
                               class="bg-slate-50 border border-slate-300 text-slate-900 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Duration (days)</label>
                        <input type="number" name="duration_days" min="1" value="{{ $t->duration_days }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">VB Earn Requirement ($)</label>
                        <input type="number" step="0.01" name="vb_earn_required" min="0" value="{{ (float) $t->vb_earn_required }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-0.5">Prize Pool ($ focoin)</label>
                        <input type="number" step="0.01" name="prize_pool_usd" min="0" value="{{ (float) $t->prize_pool_usd }}" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                    </div>
                    <div class="flex gap-1">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-600 mb-0.5">Promo: holds</label>
                            <select name="promo_hold_package" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg }}" {{ strtoupper($t->promo_hold_package) === strtoupper($pkg) ? 'selected' : '' }}>{{ $pkg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-600 mb-0.5">→ gets</label>
                            <select name="promo_grant_package" class="bg-slate-50 border border-slate-300 text-xs rounded-lg p-2 w-full">
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg }}" {{ strtoupper($t->promo_grant_package) === strtoupper($pkg) ? 'selected' : '' }}>{{ $pkg }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-lg text-xs px-4 py-2">Save {{ $t->name }}</button>
                </div>
            </form>
            <form method="POST" action="{{ route('admin.fom-royal.tier.toggle', $t->id) }}" class="mb-4 -mt-2 text-right">
                @csrf
                <button type="submit" class="text-xs font-semibold {{ $t->is_active ? 'text-red-600' : 'text-emerald-600' }} hover:underline">
                    {{ $t->is_active ? 'Deactivate' : 'Activate' }} {{ $t->name }}
                </button>
            </form>
        @endforeach
    </div>

    {{-- Recent reviews --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
        <h2 class="text-lg font-bold text-slate-900 mb-3"><i class="fas fa-history text-slate-400 mr-1"></i> Recently reviewed</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Tier</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Focoin price</th>
                        <th class="px-4 py-3">Tokens paid</th>
                        <th class="px-4 py-3">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $rrow)
                        <tr class="bg-white border-b">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $rrow->user->user ?? $rrow->user->name ?? ('#' . $rrow->user_id) }}</td>
                            <td class="px-4 py-3">{{ $rrow->tier_name }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold px-2 py-1 rounded {{ $rrow->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($rrow->status) }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $rrow->focoin_price !== null ? '$' . rtrim(rtrim(number_format((float) $rrow->focoin_price, 6), '0'), '.') : '—' }}</td>
                            <td class="px-4 py-3">{{ number_format((float) $rrow->tokens_paid, 2) }}</td>
                            <td class="px-4 py-3 text-xs">{{ $rrow->admin_notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-slate-400">Nothing reviewed yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $recent->appends(request()->except('recent_page'))->links() }}</div>
    </div>
</div>
@endsection

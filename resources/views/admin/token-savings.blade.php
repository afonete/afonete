@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Page Header --}}
    <header class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 py-6 px-6 rounded-2xl mb-6 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-piggy-bank text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight">
                        Token Saving Wallet
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Users who moved <strong class="text-emerald-700">{{ $symbol }}</strong> into the 6-month savings lock — locked, matured, and withdrawn records.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-700 hover:bg-slate-50 shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
            </a>
        </div>
    </header>

    {{-- KPI cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Savers</div>
            <div class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalSavers) }}</div>
            <div class="text-xs text-slate-400 mt-1">unique users</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Records</div>
            <div class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalRecords) }}</div>
            <div class="text-xs text-slate-400 mt-1">total deposits</div>
        </div>
        <div class="bg-white border border-emerald-200 rounded-xl p-4 shadow-sm bg-emerald-50/40">
            <div class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Deposited</div>
            <div class="text-2xl font-extrabold text-emerald-700 mt-1">{{ number_format($totalDeposited, 2) }}</div>
            <div class="text-xs text-emerald-600/80 mt-1">{{ $symbol }} moved in</div>
        </div>
        <div class="bg-white border border-amber-200 rounded-xl p-4 shadow-sm bg-amber-50/40">
            <div class="text-xs font-bold text-amber-700 uppercase tracking-wider">Locked Now</div>
            <div class="text-2xl font-extrabold text-amber-700 mt-1">{{ number_format($lockedNow, 2) }}</div>
            <div class="text-xs text-amber-600/80 mt-1">{{ $symbol }} in 6-mo lock</div>
        </div>
        <div class="bg-white border border-violet-200 rounded-xl p-4 shadow-sm bg-violet-50/40">
            <div class="text-xs font-bold text-violet-700 uppercase tracking-wider">Active Locks</div>
            <div class="text-2xl font-extrabold text-violet-700 mt-1">{{ number_format($activeCount) }}</div>
            <div class="text-xs text-violet-600/80 mt-1">still counting down</div>
        </div>
        <div class="bg-white border border-sky-200 rounded-xl p-4 shadow-sm bg-sky-50/40">
            <div class="text-xs font-bold text-sky-700 uppercase tracking-wider">Matured</div>
            <div class="text-2xl font-extrabold text-sky-700 mt-1">{{ number_format($maturedCount) }}</div>
            <div class="text-xs text-sky-600/80 mt-1">ready to withdraw</div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Withdrawn</div>
            <div class="text-2xl font-extrabold text-slate-700 mt-1">{{ number_format($totalWithdrawn, 2) }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ $withdrawnCount }} records</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('admin.token-savings.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Search user</label>
                <input type="text" name="q" value="{{ $search }}" placeholder="Name, username or email…"
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                    <option value="">All statuses</option>
                    <option value="active"    @selected($status === 'active')>Active (locked)</option>
                    <option value="matured"    @selected($status === 'matured')>Matured (ready)</option>
                    <option value="withdrawn"  @selected($status === 'withdrawn')>Withdrawn</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-bold shadow-sm">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.token-savings.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-sm font-semibold">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Records table --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-extrabold text-slate-800 uppercase text-sm tracking-wider">Saving Wallet Records</h2>
            <span class="text-xs text-slate-500">{{ $records->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">User</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4 text-left">Start</th>
                        <th class="py-3 px-4 text-left">Matures</th>
                        <th class="py-3 px-4 text-right">Days Left</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-left">Withdrawn</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $i => $s)
                        @php
                            $u = $s->user;
                            $start   = $s->start_date ? \Carbon\Carbon::parse($s->start_date) : null;
                            $mature  = $s->mature_date ? \Carbon\Carbon::parse($s->mature_date) : null;
                            $daysLeft = 0;
                            if ($s->display_status === 'active' && $mature) {
                                $daysLeft = max(0, (int) now()->startOfDay()->diffInDays($mature->copy()->startOfDay(), false));
                            }
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono text-slate-400 text-xs">#{{ $s->id }}</td>
                            <td class="py-3 px-4">
                                @if($u)
                                    <div class="font-bold text-slate-800">{{ $u->name }}</div>
                                    <div class="text-xs text-slate-500">
                                        <span class="font-mono">@{{ $u->username ?: ($u->email ?? '—') }}</span>
                                        · ID {{ $u->id }}
                                    </div>
                                @else
                                    <span class="text-rose-600 italic text-xs">User #{{ $s->user_id }} deleted</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-extrabold text-slate-800">
                                {{ number_format((float) $s->amount, 4) }}
                                <span class="text-xs font-bold text-emerald-600 ml-1">{{ $symbol }}</span>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600">
                                {{ $start ? $start->format('Y-m-d') : '—' }}
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600">
                                {{ $mature ? $mature->format('Y-m-d') : '—' }}
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-xs">
                                @if($s->display_status === 'active')
                                    <span class="text-amber-700">{{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }}</span>
                                @elseif($s->display_status === 'matured')
                                    <span class="text-sky-700">Ready</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($s->display_status === 'active')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                        Locked
                                    </span>
                                @elseif($s->display_status === 'matured')
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-sky-100 text-sky-800 border border-sky-200">
                                        Matured
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Withdrawn
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600">
                                @if($s->status === 'withdrawn')
                                    <div class="font-bold text-emerald-700">{{ number_format((float) $s->withdrawn_amount, 4) }} {{ $symbol }}</div>
                                    <div class="text-slate-400">
                                        {{ $s->withdrawn_date ? \Carbon\Carbon::parse($s->withdrawn_date)->format('Y-m-d') : '' }}
                                    </div>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-2 block text-slate-300"></i>
                                No saving-wallet records found{{ $search || $status ? ' for these filters' : '' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($records, 'hasPages') && $records->hasPages())
            <div class="px-5 py-3 border-t border-slate-100 text-xs">
                {{ $records->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <p class="text-center text-xs text-slate-400 mt-6">
        Tokens are locked for <strong>6 months</strong> from the deposit date. After maturity users can move
        savings back to their Available Token balance (requires the second transaction password).
    </p>
</div>
@endsection

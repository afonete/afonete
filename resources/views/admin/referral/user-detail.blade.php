@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Back Navigation & Header --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('admin.referral.bonuses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition duration-150 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Referral Bonuses
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
            <i class="fas fa-user-shield mr-1.5"></i> Admin Audit Mode
        </span>
    </div>

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-2xl shadow-md flex-shrink-0">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight dark:text-white">
                    {{ $user->name ?? 'User Audit' }}
                </h1>
                <p class="text-slate-600 text-sm mt-1 font-mono dark:text-gray-300">
                    {{ $user->email ?? '—' }} &bull; <span class="font-bold">User ID: #{{ $user->id ?? '—' }}</span>
                </p>
            </div>
        </div>
    </header>

    {{-- 4 Financial Stat Cards (Matching Admin Dashboard) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white border-l-[5px] border-blue-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Earned</span>
                <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-mono">${{ number_format($totals['total'] ?? 0, 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Lifetime commissions</span>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl dark:bg-blue-900/30 dark:text-blue-400"><i class="fas fa-coins text-xl"></i></div>
        </div>

        <div class="bg-white border-l-[5px] border-amber-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Pending</span>
                <div class="text-2xl font-extrabold text-amber-500 font-mono">${{ number_format($totals['pending'] ?? 0, 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Next Monday queue</span>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl dark:bg-amber-900/30 dark:text-amber-400"><i class="fas fa-clock text-xl"></i></div>
        </div>

        <div class="bg-white border-l-[5px] border-emerald-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Withdrawable</span>
                <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">${{ number_format($totals['withdrawable'] ?? 0, 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Available user balance</span>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400"><i class="fas fa-check-circle text-xl"></i></div>
        </div>

        <div class="bg-white border-l-[5px] border-purple-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Already Withdrawn</span>
                <div class="text-2xl font-extrabold text-purple-600 dark:text-purple-400 font-mono">${{ number_format($totals['lifetime_withdrawn'] ?? 0, 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Historical cashouts</span>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl dark:bg-purple-900/30 dark:text-purple-400"><i class="fas fa-hand-holding-usd text-xl"></i></div>
        </div>

    </div>

    {{-- TABLE 1: BONUS ROWS --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-list text-blue-400"></i> Commission Ledger Log</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Total Records: {{ $rows->total() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">Timestamp</th>
                        <th class="py-4 px-6">Source Type</th>
                        <th class="py-4 px-6">From User</th>
                        <th class="py-4 px-6 text-center">Referral Level</th>
                        <th class="py-4 px-6 text-right">Pkg Amount ($)</th>
                        <th class="py-4 px-6 text-right">Bonus ($)</th>
                        <th class="py-4 px-6 text-center">Withdrawable On</th>
                        <th class="py-4 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($rows as $r)
                        <tr class="bg-white hover:bg-blue-50/40 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-mono text-xs">{{ $r->created_at ? $r->created_at->format('d M Y, H:i') : '—' }}</td>
                            <td class="py-4 px-6 text-xs font-bold text-gray-700 dark:text-gray-300">{!! $r->sourceLabel() !!}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $r->sourceUser->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $r->sourceUser->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($r->level > 0)
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200">L{{ $r->level }} &bull; {{ rtrim(rtrim(number_format($r->percentage, 2), '0'), '.') }}%</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-gray-700 dark:text-gray-300">${{ number_format($r->source_amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-extrabold text-blue-600 dark:text-blue-400 font-mono text-base">${{ number_format($r->bonus_amount, 4) }}</td>
                            <td class="py-4 px-6 text-center font-mono text-xs">{{ $r->week_start ? $r->week_start->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($r->status === 'withdrawn')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200">Withdrawn</span>
                                @elseif($r->status === 'withdrawable')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200">Withdrawable</span>
                                @elseif($r->status === 'pending')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200">Pending</span>
                                @elseif($r->status === 'reversed')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border border-red-200">Reversed</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ ucfirst($r->status ?? '—') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-12 text-center text-gray-400">No bonus ledger entries found for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rows->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">{{ $rows->links() }}</div>
        @endif
    </div>

    {{-- TABLE 2: INVESTMENTS --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-10 dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-box-open text-emerald-400"></i> All User Investments</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Total Count: {{ $investments->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Package Name</th>
                        <th class="py-4 px-6 text-center">Category</th>
                        <th class="py-4 px-6 text-right">Amount ($)</th>
                        <th class="py-4 px-6 text-right">Paid ($)</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Expires On</th>
                        <th class="py-4 px-6 text-center">Bought On</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($investments as $inv)
                        @php
                            $expired = $inv->is_expired;
                            $paidOk  = (int) $inv->status === 1;
                        @endphp
                        <tr class="bg-white hover:bg-emerald-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-mono text-xs">#{{ $inv->id }}</td>
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $inv->package }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ strtoupper($inv->category ?? '') === 'VENTURE' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 border border-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200' }}">
                                    {{ strtoupper($inv->category ?? '—') }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-gray-700 dark:text-gray-300">${{ number_format($inv->amount, 2) }}</td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400">${{ number_format($inv->paid ?? 0, 2) }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($expired)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Expired</span>
                                @elseif($paidOk)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200">Active</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center font-mono text-xs">{{ $inv->expiration_date ? \Carbon\Carbon::parse($inv->expiration_date)->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-center font-mono text-xs">{{ $inv->created_at ? \Carbon\Carbon::parse($inv->created_at)->format('d M Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-8 text-center text-gray-400">No investments recorded for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TABLE 3: WEEKLY WITHDRAWALS --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-money-bill-wave text-amber-400"></i> User Weekly Withdrawals</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Total Count: {{ $weekly->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">Week Start</th>
                        <th class="py-4 px-6 text-right">Amount ($)</th>
                        <th class="py-4 px-6">Transaction Ref</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6">Processed Date</th>
                        <th class="py-4 px-6">Admin Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($weekly as $w)
                        <tr class="bg-white hover:bg-amber-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-mono text-xs">{{ $w->week_start ? $w->week_start->format('d M Y') : '—' }}</td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-base">${{ number_format($w->amount, 2) }}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-600 dark:text-gray-300">{{ $w->transaction_no ?? '—' }}</td>
                            <td class="py-4 px-6 text-center">{!! $w->statusBadge() !!}</td>
                            <td class="py-4 px-6 font-mono text-xs text-gray-500">{{ optional($w->processed_at)->format('d M Y, H:i') ?? '—' }}</td>
                            <td class="py-4 px-6 text-xs text-gray-500 italic">{{ $w->admin_notes ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-400">No weekly withdrawals recorded for this user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

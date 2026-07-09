@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Page Header --}}
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center dark:text-white">
                    <span class="p-2.5 bg-blue-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-coins text-xl"></i>
                    </span>
                    Referral Bonuses &mdash; Platform Overview
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl dark:text-gray-300">
                    Monitor platform-wide referral commissions, pending weekly payouts, rank applications, and per-user earnings distribution.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                    <i class="fas fa-chart-line mr-1.5"></i> Live Financial Engine
                </span>
            </div>
        </div>
    </header>

    {{-- Totals Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- All-Time Total --}}
        <div class="bg-white border-l-[5px] border-blue-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700 dark:border-l-blue-500">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1 dark:text-gray-400">All-Time Total</span>
                <div class="text-2xl font-extrabold text-gray-900 dark:text-white font-mono">${{ number_format($totals['all_time'], 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Cumulative bonus generated</span>
            </div>
            <div class="p-3.5 bg-blue-50 text-blue-600 rounded-xl dark:bg-blue-900/30 dark:text-blue-400 flex-shrink-0">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>

        {{-- Currently Withdrawable --}}
        <div class="bg-white border-l-[5px] border-emerald-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700 dark:border-l-emerald-500">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1 dark:text-gray-400">Withdrawable Now</span>
                <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono">${{ number_format($totals['withdrawable'], 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Available user balance</span>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400 flex-shrink-0">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>

        {{-- Pending (next Monday) --}}
        <div class="bg-white border-l-[5px] border-amber-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700 dark:border-l-amber-500">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1 dark:text-gray-400">Pending Payouts</span>
                <div class="text-2xl font-extrabold text-amber-500 font-mono">${{ number_format($totals['pending'], 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Queue for next Monday</span>
            </div>
            <div class="p-3.5 bg-amber-50 text-amber-600 rounded-xl dark:bg-amber-900/30 dark:text-amber-400 flex-shrink-0">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>

        {{-- Already Paid Out --}}
        <div class="bg-white border-l-[5px] border-purple-500 rounded-2xl shadow-sm p-5 border border-gray-100 flex items-center justify-between dark:bg-gray-800 dark:border-gray-700 dark:border-l-purple-500">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1 dark:text-gray-400">Already Paid Out</span>
                <div class="text-2xl font-extrabold text-purple-600 dark:text-purple-400 font-mono">${{ number_format($totals['paid'], 2) }}</div>
                <span class="text-[11px] text-gray-400 mt-1 block">Historical settled withdrawals</span>
            </div>
            <div class="p-3.5 bg-purple-50 text-purple-600 rounded-xl dark:bg-purple-900/30 dark:text-purple-400 flex-shrink-0">
                <i class="fas fa-hand-holding-usd text-xl"></i>
            </div>
        </div>

    </div>

    {{-- Action & Navigation Bar --}}
    <div class="flex flex-wrap items-center gap-3 mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-200 dark:bg-gray-800/50 dark:border-gray-700">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 mr-2 flex items-center gap-1.5 dark:text-gray-400">
            <i class="fas fa-location-arrow text-blue-500"></i> Quick Actions:
        </span>
        <a href="{{ route('admin.referral.withdrawals') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition duration-150 transform active:scale-95">
            <i class="fas fa-money-bill-wave"></i> Weekly Withdrawals Queue
        </a>
        <a href="{{ route('admin.rank.applications') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition duration-150 transform active:scale-95">
            <i class="fas fa-trophy"></i> Rank Applications
        </a>
        <a href="{{ route('admin.rank.eligible') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition duration-150 transform active:scale-95">
            <i class="fas fa-user-check"></i> Eligible Users Overview
        </a>
        <a href="{{ route('admin.rank.settings') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition duration-150 transform active:scale-95 dark:bg-gray-700 dark:hover:bg-gray-600">
            <i class="fas fa-cog"></i> Rank Settings
        </a>
    </div>

    {{-- Per-User Table Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between flex-wrap gap-2">
            <span class="font-bold text-base flex items-center gap-2.5">
                <i class="fas fa-users text-blue-400"></i> Per-User Bonus Breakdown <span class="text-xs font-normal text-slate-400">(Top Earners First)</span>
            </span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold uppercase tracking-wider">
                Total Records: {{ $perUser->total() }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th scope="col" class="py-4 px-6">User / Account</th>
                        <th scope="col" class="py-4 px-6 text-right">Total Earned</th>
                        <th scope="col" class="py-4 px-6 text-right">Pending</th>
                        <th scope="col" class="py-4 px-6 text-right">Withdrawable</th>
                        <th scope="col" class="py-4 px-6 text-right">Withdrawn</th>
                        <th scope="col" class="py-4 px-6 text-center">Transactions</th>
                        <th scope="col" class="py-4 px-6 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($perUser as $row)
                        @php $u = $users[$row->user_id] ?? null; @endphp
                        <tr class="bg-white hover:bg-blue-50/40 transition duration-150 dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm flex-shrink-0 dark:bg-blue-900 dark:text-blue-300">
                                        {{ strtoupper(substr($u->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $u->name ?? 'Unknown User' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $u->email ?? 'ID: ' . $row->user_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="font-extrabold text-blue-600 dark:text-blue-400 font-mono text-base">${{ number_format($row->total_bonus, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="font-bold text-amber-500 font-mono">${{ number_format($row->pending, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">${{ number_format($row->withdrawable, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <span class="font-bold text-purple-600 dark:text-purple-400 font-mono">${{ number_format($row->withdrawn, 2) }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold bg-gray-100 text-gray-700 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                    {{ $row->row_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.referral.bonuses.user', $row->user_id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 rounded-xl text-xs font-bold border border-blue-200 transition duration-150 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800 dark:hover:bg-blue-900/50 shadow-sm">
                                    <i class="fas fa-eye"></i> View Audit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <span class="text-base font-medium">No referral bonus distributions recorded yet.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($perUser->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
            {{ $perUser->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

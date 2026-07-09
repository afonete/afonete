@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Header & Back Navigation --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('admin.referral.bonuses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Referral Bonuses
        </a>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200">
            <i class="fas fa-robot mr-1.5"></i> Daily Cron: <code>ranks:check</code>
        </span>
    </div>

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center dark:text-white">
            <span class="p-2.5 bg-emerald-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center"><i class="fas fa-user-check text-xl"></i></span>
            Eligible Users &mdash; Rank Advancement Overview
        </h1>
        <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl dark:text-gray-300">
            Live programmatic eligibility check. Users listed below currently qualify for a promotion but have not yet had a formal rank application generated.
        </p>
    </header>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2"><i class="fas fa-chart-line text-emerald-400"></i> Algorithmic Qualification Roster</span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold">Qualified Count: {{ count($rows ?? []) }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 font-bold">
                    <tr>
                        <th class="py-4 px-6">User / Account</th>
                        <th class="py-4 px-6 text-center">Qualified Rank</th>
                        <th class="py-4 px-6 text-center">Reward Benefit</th>
                        <th class="py-4 px-6 text-center">Active Directs</th>
                        <th class="py-4 px-6 text-right">Direct Investment ($)</th>
                        <th class="py-4 px-6 text-right">Total Network Inv. ($)</th>
                        <th class="py-4 px-6 text-center">Audit Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($rows as $row)
                        <tr class="bg-white hover:bg-emerald-50/30 transition dark:bg-gray-800 dark:hover:bg-gray-700/50">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                <div>{{ $row['user']->name ?? 'Unknown' }}</div>
                                <div class="text-xs font-normal text-gray-500 font-mono">{{ $row['user']->email ?? '—' }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 text-xs font-extrabold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-300 inline-flex items-center gap-1">
                                    <i class="fas fa-star text-amber-500"></i> {{ $row['rank']->name ?? 'Rank' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-emerald-600 dark:text-emerald-400 font-mono text-xs">
                                {{ $row['rank']->rewardLabel() ?? '—' }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold text-blue-600 dark:text-blue-400 font-mono">
                                {{ $row['actual']['active_direct_referrals'] ?? 0 }} <span class="text-xs font-normal text-gray-400">users</span>
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-bold text-gray-700 dark:text-gray-300">
                                ${{ number_format($row['actual']['direct_referral_investment'] ?? 0, 2) }}
                            </td>
                            <td class="py-4 px-6 text-right font-mono font-extrabold text-indigo-600 dark:text-indigo-400 text-base">
                                ${{ number_format($row['actual']['total_investment'] ?? 0, 2) }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <a href="{{ route('admin.referral.bonuses.user', $row['user']->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 rounded-xl text-xs font-bold border border-blue-200 transition dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800 dark:hover:bg-blue-900/50 shadow-sm">
                                    <i class="fas fa-eye"></i> View Audit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-clock text-4xl mb-3 text-gray-300 dark:text-gray-600"></i>
                                    <span class="text-base font-medium">No users currently meet programmatic rank advancement criteria.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

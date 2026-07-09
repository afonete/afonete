@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Header & Back Navigation --}}
    <div class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <a href="{{ route('admin.referral.bonuses') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm transition dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Referral Bonuses
        </a>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200">
            <i class="fas fa-sliders-h mr-1.5"></i> Algorithmic Rank Engine
        </span>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-500 hover:bg-green-100 p-1 rounded-lg" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1">{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-500 hover:bg-red-100 p-1 rounded-lg" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm dark:from-gray-800 dark:to-gray-800 dark:border-gray-700">
        <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center dark:text-white">
            <span class="p-2.5 bg-slate-700 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center"><i class="fas fa-cog text-xl"></i></span>
            Rank Advancement Criteria &amp; Bonus Settings
        </h1>
        <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl dark:text-gray-300">
            Configure programmatic volume thresholds, direct referral requirements, and financial rewards for each platform career rank tier.
        </p>
    </header>

    <form method="POST" action="{{ route('admin.rank.settings.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @forelse($ranks as $rank)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700 transition duration-150 hover:shadow-md">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 text-white flex items-center justify-between flex-wrap gap-2">
                    <span class="font-extrabold text-lg flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-mono text-sm shadow">{{ $rank->order }}</span>
                        {{ $rank->name }}
                    </span>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="bg-slate-700 px-3 py-1 rounded-full text-slate-300 font-mono">slug: <code>{{ $rank->slug }}</code></span>
                        <span class="bg-blue-900/80 border border-blue-700 px-3 py-1 rounded-full text-blue-200 font-bold">Level {{ $rank->level }}</span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    {{-- Subsection 1: Direct Network Requirements --}}
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-gray-400 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-users text-blue-500"></i> Direct Downline Qualification Requirements
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Active Direct Referrals</label>
                                <input type="number" min="0" name="ranks[{{ $rank->id }}][min_active_direct_referrals]" value="{{ $rank->min_active_direct_referrals }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Associates from Direct</label>
                                <input type="number" min="0" name="ranks[{{ $rank->id }}][min_associates_from_direct]" value="{{ $rank->min_associates_from_direct }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Directors from Direct</label>
                                <input type="number" min="0" name="ranks[{{ $rank->id }}][min_directors_from_direct]" value="{{ $rank->min_directors_from_direct }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Reg. Supervisors from Direct</label>
                                <input type="number" min="0" name="ranks[{{ $rank->id }}][min_regional_supervisors_from_direct]" value="{{ $rank->min_regional_supervisors_from_direct }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>

                    {{-- Subsection 2: Volume Thresholds & Financial Rewards --}}
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-gray-400 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 flex items-center gap-1.5">
                            <i class="fas fa-dollar-sign text-emerald-500"></i> Volume Thresholds &amp; One-Time Rewards
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Direct Ref Investment ($)</label>
                                <div class="flex rounded-xl shadow-sm">
                                    <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                    <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][min_direct_referral_investment]" value="{{ $rank->min_direct_referral_investment }}" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 block flex-1 w-full p-2.5 font-mono font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Min Total Network Volume ($)</label>
                                <div class="flex rounded-xl shadow-sm">
                                    <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                    <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][min_total_investment]" value="{{ $rank->min_total_investment }}" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 block flex-1 w-full p-2.5 font-mono font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-emerald-700 dark:text-emerald-300">One-Time Reward Amount ($)</label>
                                <div class="flex rounded-xl shadow-sm">
                                    <span class="inline-flex items-center px-3.5 text-sm font-bold text-emerald-600 bg-emerald-50 border border-r-0 border-emerald-300 rounded-l-xl dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700">$</span>
                                    <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][reward_amount]" value="{{ $rank->reward_amount }}" class="rounded-none rounded-r-xl bg-emerald-50/30 border border-emerald-300 text-gray-900 focus:ring-emerald-500 block flex-1 w-full p-2.5 font-mono font-extrabold text-emerald-600 dark:bg-gray-700 dark:border-emerald-700 dark:text-emerald-400">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Subsection 3: Account Manager (AM) Weekly Criteria --}}
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 dark:bg-gray-700/40 dark:border-gray-600">
                        <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-gray-300 mb-4 flex items-center gap-1.5">
                            <i class="fas fa-user-tie text-purple-500"></i> Account Manager (AM) Weekly Pool Rules
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-center">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">Reward % (for AM weekly)</label>
                                <div class="flex rounded-xl shadow-sm">
                                    <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][reward_percentage]" value="{{ $rank->reward_percentage }}" class="rounded-l-xl bg-white border border-r-0 border-gray-300 text-gray-900 focus:ring-blue-500 block flex-1 w-full p-2.5 font-mono font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-gray-300 rounded-r-xl dark:bg-gray-600 dark:text-gray-300 dark:border-gray-600">%</span>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">AM: Min Direct Investment Users</label>
                                <input type="number" min="0" name="ranks[{{ $rank->id }}][am_min_active_direct_investment_users]" value="{{ $rank->am_min_active_direct_investment_users }}" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 block w-full p-2.5 font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-gray-700 dark:text-gray-300">AM: Per-Ref Min Investment ($)</label>
                                <div class="flex rounded-xl shadow-sm">
                                    <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-600 dark:text-gray-300 dark:border-gray-600">$</span>
                                    <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][am_per_user_min_investment]" value="{{ $rank->am_per_user_min_investment }}" class="rounded-none rounded-r-xl bg-white border border-gray-300 text-gray-900 focus:ring-blue-500 block flex-1 w-full p-2.5 font-mono font-bold dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>
                            <div class="pt-4 sm:pt-6">
                                <label class="relative flex items-center p-3 rounded-xl border border-gray-300 bg-white hover:bg-blue-50/50 cursor-pointer transition shadow-sm dark:bg-gray-800 dark:border-gray-600">
                                    <input type="checkbox" name="ranks[{{ $rank->id }}][is_active]" value="1" id="active_{{ $rank->id }}" {{ $rank->is_active ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded focus:ring-2 border-gray-300">
                                    <span class="ml-3 font-extrabold text-sm text-gray-900 dark:text-white">Rank Tier Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="bg-white p-12 text-center rounded-2xl border border-gray-200 text-gray-400 dark:bg-gray-800 dark:border-gray-700">
                    <i class="fas fa-trophy text-4xl mb-3 text-gray-300"></i>
                    <p class="text-base font-medium">No rank advancement criteria configured yet.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination Block --}}
        @if(method_exists($ranks, 'links') && $ranks->hasPages())
        <div class="mt-8 p-4 bg-white rounded-2xl border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700">
            {{ $ranks->links() }}
        </div>
        @endif

        <div class="mt-8 flex items-center justify-end">
            <button type="submit" class="w-full sm:w-auto min-w-[240px] text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-extrabold rounded-xl text-base px-8 py-4 text-center inline-flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-500/25 transition duration-200 ease-in-out transform active:scale-[0.99] dark:focus:ring-emerald-800">
                <i class="fas fa-save text-lg"></i>
                <span>Save All Rank Criteria</span>
            </button>
        </div>
    </form>

</div>
@endsection

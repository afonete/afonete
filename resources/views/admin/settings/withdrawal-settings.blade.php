@extends('admin.sidebar')
@section('contents')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl shadow-sm transition duration-150 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1">{{ session('success') }}</span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1">{{ $errors->first() }}</span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-emerald-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </span>
                    Withdrawal &amp; Deposit Settings
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl">
                    Configure transaction limits, automated payout rules, TRC-20 validation parameters, and hot/cold wallet management thresholds.
                </p>
            </div>
        </div>
    </header>

    {{-- Main Form Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="bg-slate-800 px-6 py-4 text-white flex items-center justify-between">
            <span class="font-bold text-base flex items-center gap-2">
                <i class="fas fa-cogs text-emerald-400"></i> Platform Financial Parameters
            </span>
            <span class="text-xs bg-slate-700 text-slate-300 px-3 py-1 rounded-full font-semibold uppercase tracking-wider">System Config</span>
        </div>

        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.settings.withdrawal-settings.update') }}" class="space-y-10">
                @csrf
                @method('PUT')

                {{-- Section 1: Transaction & Amount Limits --}}
                <div>
                    <h3 class="text-base font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-4 pb-2.5 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 inline-flex items-center justify-center text-sm font-extrabold mr-1">1</span>
                        Transaction &amp; Amount Limits
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
                        
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Min Withdrawal ($) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="min_amount" value="{{ $settings->min_amount }}" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Per specification: $10.00</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Withdrawal Fee (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <input type="number" step="0.01" min="0" max="100" name="withdrawal_fee_percent" value="{{ $settings->withdrawal_fee_percent ?? 0.00 }}" required class="rounded-l-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-l-0 border-gray-300 rounded-r-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">%</span>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Default is 0.00% fee</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Min Deposit ($) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="min_deposit_amount" value="{{ $settings->min_deposit_amount ?? 10 }}" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Used by manual &amp; Plisio deposit</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Max per Transaction ($) <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="max_per_transaction" value="{{ $settings->max_per_transaction }}" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Single transaction ceiling</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Daily Limit ($) <span class="text-xs font-normal text-gray-400">(Optional)</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="daily_limit" value="{{ $settings->daily_limit }}" placeholder="No limit" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave blank for unlimited</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Monthly Limit ($) <span class="text-xs font-normal text-gray-400">(Optional)</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="monthly_limit" value="{{ $settings->monthly_limit }}" placeholder="No limit" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave blank for unlimited</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Admin Approval Threshold ($)
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="admin_approval_threshold" value="{{ $settings->admin_approval_threshold ?? 100 }}" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium">Withdrawals &ge; this amount go to admin review.</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Max Automatic Withdrawal ($)
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3.5 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                <input type="number" step="0.01" min="0" name="max_auto_withdrawal" value="{{ $settings->max_auto_withdrawal ?? 100 }}" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Ceiling for automated instant payouts</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Manual Review Risk Score
                            </label>
                            <input type="number" min="0" max="100" name="manual_review_risk_score" value="{{ $settings->manual_review_risk_score ?? 50 }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Scale: 0 - 100 (higher = stricter trigger)</p>
                        </div>

                    </div>
                </div>

                {{-- Section 2: TRC-20 Validation & Policy Toggles --}}
                <div>
                    <h3 class="text-base font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-4 pb-2.5 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 inline-flex items-center justify-center text-sm font-extrabold mr-1">2</span>
                        Validation Rules &amp; Policy Toggles
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                        
                        {{-- Toggle Card 1 --}}
                        <label class="relative flex items-start p-4 rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-blue-50/40 hover:border-blue-200 cursor-pointer transition duration-150 shadow-sm dark:bg-gray-700/50 dark:border-gray-600 dark:hover:border-blue-500">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="require_admin_approval" value="1" id="require_approval" {{ $settings->require_admin_approval ? 'checked' : '' }} class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-3.5 text-sm">
                                <span class="font-bold text-gray-900 dark:text-white block text-base">Require Admin Approval</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block leading-relaxed">All withdrawal requests must be manually reviewed and authorized by an administrator before processing.</span>
                            </div>
                        </label>

                        {{-- Toggle Card 2 --}}
                        <label class="relative flex items-start p-4 rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-emerald-50/40 hover:border-emerald-200 cursor-pointer transition duration-150 shadow-sm dark:bg-gray-700/50 dark:border-gray-600 dark:hover:border-emerald-500">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="auto_withdrawals_enabled" value="1" id="auto_withdrawals_enabled" {{ ($settings->auto_withdrawals_enabled ?? false) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 bg-white border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-3.5 text-sm">
                                <span class="font-bold text-gray-900 dark:text-white block text-base">Enable Automatic Payouts</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block leading-relaxed">Allow automated instant USDT TRC20 processing for small withdrawals below the configured ceiling.</span>
                            </div>
                        </label>

                        {{-- Toggle Card 3 --}}
                        <label class="relative flex items-start p-4 rounded-2xl border border-gray-200 bg-gray-50/70 hover:bg-purple-50/40 hover:border-purple-200 cursor-pointer transition duration-150 shadow-sm dark:bg-gray-700/50 dark:border-gray-600 dark:hover:border-purple-500">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="validate_trc20_format" value="1" id="validate_trc20" {{ $settings->validate_trc20_format ? 'checked' : '' }} class="w-5 h-5 text-purple-600 bg-white border-gray-300 rounded focus:ring-purple-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-3.5 text-sm">
                                <span class="font-bold text-gray-900 dark:text-white block text-base">Validate TRC-20 Format</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block leading-relaxed">Enforce strict algorithmic verification on user TRON wallet addresses to prevent failed blockchain transfers.</span>
                            </div>
                        </label>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Default TRC-20 Min Length
                            </label>
                            <input type="number" min="26" max="64" name="default_trc20_min_length" value="{{ $settings->default_trc20_min_length }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Standard TRON address length (e.g. 34 chars)</p>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Hot/Cold Wallet Management --}}
                <div>
                    <h3 class="text-base font-bold uppercase tracking-wider text-slate-800 dark:text-white mb-4 pb-2.5 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 inline-flex items-center justify-center text-sm font-extrabold mr-1">3</span>
                        Hot &amp; Cold Wallet Management
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
                        
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Hot Wallet Max Balance (USDT)
                            </label>
                            <input type="number" step="0.000001" min="0" name="hot_wallet_max_balance" value="{{ $settings->hot_wallet_max_balance }}" placeholder="Optional ceiling" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <p class="mt-1.5 text-xs text-amber-600 dark:text-amber-400 font-medium">Above this limit, scheduler sweeps surplus to cold wallet.</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Hot Wallet Reserve Balance (USDT)
                            </label>
                            <input type="number" step="0.000001" min="0" name="hot_wallet_reserve_balance" value="{{ $settings->hot_wallet_reserve_balance ?? 100 }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Minimum balance retained after sweep operation.</p>
                        </div>

                        <div class="sm:col-span-2 lg:col-span-1">
                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                Cold Wallet TRON Address
                            </label>
                            <input type="text" name="cold_wallet_address" value="{{ $settings->cold_wallet_address }}" placeholder="T..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Destination address for automated sweep transfers.</p>
                        </div>

                    </div>
                </div>

                {{-- Section 4: Internal Audit Notes & Submit --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                            Internal Administrative Notes <span class="text-xs font-normal text-gray-400">(Optional audit log)</span>
                        </label>
                        <textarea name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition" placeholder="Record reason for modification of withdrawal limits or security toggles...">{{ $settings->notes }}</textarea>
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <button type="submit" class="w-full sm:w-auto min-w-[240px] text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-extrabold rounded-xl text-base px-8 py-3.5 text-center inline-flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-500/25 transition duration-200 ease-in-out transform active:scale-[0.99] dark:focus:ring-emerald-800">
                            <i class="fas fa-save text-lg"></i>
                            <span>Save All Settings</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

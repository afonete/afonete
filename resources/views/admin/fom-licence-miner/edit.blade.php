@extends('admin.sidebar')

@section('contents')
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg text-xl">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit FOM Licence Miner Package</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update package details for <span class="font-bold text-slate-700">{{ $package->name }}</span>.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.fom-licence-miner.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-left"></i> <span>Back to Packages</span>
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl mx-auto">
        
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg font-semibold">
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                <ul class="list-disc pl-5 space-y-1 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $numPrice       = \App\Models\FomLicenceMiner::cleanNum($package->price);
            $numTokens      = \App\Models\FomLicenceMiner::cleanNum($package->tokens);
            $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($package->token_bonus);
            $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($package->direct_sponsors);
            $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($package->affiliate_vbonus);
            $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($package->volume_point);
            $numVolumeBonus = \App\Models\FomLicenceMiner::cleanNum($package->volume_bonus ?? 0);
            $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($package->total_return);
        @endphp

        <form action="{{ route('admin.fom-licence-miner.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Package Name --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Package Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. BASIC, STARTER, PRO, SUPER">
                </div>

                {{-- Price (USDT) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Price (USDT) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $numPrice) }}" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 25000">
                </div>

                {{-- Display Price Label --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Display Price Label (Optional text)</label>
                    <input type="text" name="display_price" value="{{ old('display_price', $package->display_price) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 25K - 200K USDT">
                </div>

                {{-- Tokens Allocated (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Tokens Allocated (Numeric) <span class="text-red-500">*</span></label>
                    <input type="number" name="tokens" value="{{ old('tokens', $numTokens) }}" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 20833333">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: TOKEN | {{ number_format($numTokens) }} {{ $tokenSymbol }}</span>
                </div>

                {{-- Duration in Days --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Duration (Days) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="600">
                </div>

                {{-- Token Bonus % (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Token Bonus (%)</label>
                    <input type="number" step="0.01" name="token_bonus" value="{{ old('token_bonus', $numTokenBonus) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 100 for 100%">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: Token Bonus: X{{ $numTokenBonus }}%</span>
                </div>

                {{-- Direct Sponsors % (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Direct Sponsors Bonus (%)</label>
                    <input type="number" step="0.01" name="direct_sponsors" value="{{ old('direct_sponsors', $numSponsors) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 20 for 20%">
                </div>

                {{-- Affiliate V.bonus % (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Affiliate V.Bonus (%)</label>
                    <input type="number" step="0.01" name="affiliate_vbonus" value="{{ old('affiliate_vbonus', $numAffiliate) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="10">
                </div>

                {{-- Space Shop Room Limit (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Space Shop Room Limit (Text)</label>
                    <input type="text" name="space_shop_limit" value="{{ old('space_shop_limit', $package->space_shop_limit) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Space Shop Room Limit">
                </div>

                {{-- Volume Point (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Volume Point (Numeric)</label>
                    <input type="number" name="volume_point" value="{{ old('volume_point', $numVolumePoint) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 4">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: Volume Point: {{ (int) $numVolumePoint }} Point</span>
                </div>

                {{-- Volume Bonus (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Volume Bonus (Numeric)</label>
                    <input type="number" name="volume_bonus" value="{{ old('volume_bonus', $numVolumeBonus) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 6000">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: Volume Bonus: {{ number_format($numVolumeBonus) }} — separate from Volume Point</span>
                </div>

                {{-- Weekly Volume Bonus Cap (Numeric $) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Weekly Volume Bonus Cap ($)</label>
                    <input type="number" step="0.01" name="weekly_vb_cap" value="{{ old('weekly_vb_cap', (float) ($package->weekly_vb_cap ?? 0)) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 12000">
                    <span class="text-[11px] text-slate-400 block mt-1">Max VB payable per week for holders of this package (0 = no cap). Direct Sponsors bonus is never capped.</span>
                </div>

                {{-- Token Symbol (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Token Symbol (Text)</label>
                    <input type="text" name="token_symbol" value="{{ old('token_symbol', $package->token_symbol) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. {{ $tokenSymbol }}">
                    <span class="text-[11px] text-slate-400 block mt-1">Leave empty to use the global token symbol ({{ $tokenSymbol }})</span>
                </div>

                {{-- Education Access (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Education Access (Text)</label>
                    <input type="text" name="education_access" value="{{ old('education_access', $package->education_access ?? 'Access to Education Courses') }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Access to Education Courses">
                    <span class="text-[11px] text-slate-400 block mt-1">Shown as a benefit on every FOM package card</span>
                </div>

                {{-- Unlocked Per Month (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Unlocked Per Month (Text)</label>
                    <input type="text" name="unlocked_per_week" value="{{ old('unlocked_per_week', $package->unlocked_per_week) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="YES">
                </div>

                {{-- Allowed Loan (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Loan Access (Text)</label>
                    <input type="text" name="allowed_loan" value="{{ old('allowed_loan', $package->allowed_loan) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Allowed Loan or Not Allowed Loan">
                </div>

                {{-- Investment Option (Text) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Investment Option Feature (Text)</label>
                    <input type="text" name="investment_option" value="{{ old('investment_option', $package->investment_option) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Access Investment feature">
                </div>

                {{-- Total Return Tokens (Numeric) --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Total Return Tokens (Numeric)</label>
                    <input type="number" name="total_return" value="{{ old('total_return', $numTotalReturn) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 41666666">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: TOTAL RETURN: {{ number_format($numTotalReturn) }} {{ $tokenSymbol }}</span>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Display Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="0">
                </div>

                {{-- Active Checkbox --}}
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 relative"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Active Package (Visible on /investment-package)</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.fom-licence-miner.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-5 rounded-lg transition-all text-sm">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Update Package
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

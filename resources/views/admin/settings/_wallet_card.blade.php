{{-- Reusable card for one deposit_wallets row. Used by the 3 sections
     (crypto / advcash / perfect_money) on the admin deposit-wallets page. --}}
@php
    $addrInvalid = $w->wallet_address && method_exists($w, 'addressLooksValid') && ! $w->addressLooksValid();
@endphp
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6 overflow-hidden transition-all hover:shadow-md">
    <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-800 text-sm">{{ $w->kindLabel() }}</span>
            @if($showNetwork && $w->network)
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">{{ $w->network }}</span>
            @endif
            <span class="text-slate-500 text-xs">• {{ $w->label }}</span>
        </div>
        <span class="bg-slate-200 text-slate-700 text-xs font-bold px-2 py-0.5 rounded">{{ $w->currency }}</span>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            
            <div class="md:col-span-6">
                <label class="block text-xs font-semibold text-slate-600 mb-2">
                    @if($w->type === 'crypto') Wallet Address
                    @elseif($w->type === 'advcash') Advcash Account Number
                    @else Perfect Money Account Number
                    @endif
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" name="wallets[{{ $w->id }}][wallet_address]"
                       class="w-full bg-slate-50 border {{ $addrInvalid ? 'border-red-500 focus:ring-red-200 focus:border-red-500' : 'border-slate-300 focus:ring-blue-200 focus:border-blue-500' }} rounded-lg text-sm px-3 py-2.5 transition-all"
                       value="{{ $w->wallet_address }}"
                       placeholder="{{ $w->type === 'crypto' ? $w->network . ' address' : 'Account number' }}">
                @if($addrInvalid)
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        Address doesn't match the expected {{ $w->network }} format.
                    </p>
                @endif
            </div>

            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Label</label>
                <input type="text" name="wallets[{{ $w->id }}][label]" 
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all"
                       value="{{ $w->label }}">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Active Status</label>
                <div class="flex items-center h-10 mt-1">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="wallets[{{ $w->id }}][is_active]" value="1" 
                               class="sr-only peer" {{ $w->is_active ? 'checked' : '' }}>
                        <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="ms-2 text-xs font-semibold text-slate-600">Active</span>
                    </label>
                </div>
            </div>

            @if($showNetwork)
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Display Order</label>
                <input type="number" min="0" name="wallets[{{ $w->id }}][display_order]"
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all" 
                       value="{{ $w->display_order }}">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Min Amount ($)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[{{ $w->id }}][min_amount]" 
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all"
                       value="{{ $w->min_amount }}">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Max Amount ($, optional)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[{{ $w->id }}][max_amount]" 
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all"
                       value="{{ $w->max_amount }}">
            </div>
            @else
            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Display Order</label>
                <input type="number" min="0" name="wallets[{{ $w->id }}][display_order]"
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all" 
                       value="{{ $w->display_order }}">
            </div>
            @endif

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-2">Notes (Internal)</label>
                <input type="text" name="wallets[{{ $w->id }}][notes]" 
                       class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2.5 transition-all"
                       value="{{ $w->notes }}">
            </div>

            @if(!$showNetwork)
            <div class="md:col-span-12">
                <label class="block text-xs font-semibold text-slate-600 mb-2">
                    Step-by-Step Instructions Shown to User
                </label>
                <textarea name="wallets[{{ $w->id }}][instructions]" 
                          class="w-full bg-slate-50 border border-slate-300 focus:ring-blue-200 focus:border-blue-500 rounded-lg text-sm px-3 py-2 transition-all" 
                          rows="4"
                          placeholder="Each line will be numbered for the user.">{{ $w->instructions }}</textarea>
            </div>
            @endif
        </div>
    </div>
</div>

<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 max-w-7xl">

    
    <?php if(session('message')): ?>
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-gray-800 dark:text-green-400 dark:border-green-800" role="alert">
            <i class="fas fa-check-circle text-lg mr-3 text-green-600"></i>
            <span class="font-medium flex-1"><?php echo e(session('message')); ?></span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="flex items-center p-4 mb-6 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
            <i class="fas fa-exclamation-circle text-lg mr-3 text-red-600"></i>
            <span class="font-medium flex-1"><?php echo e($errors->first()); ?></span>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-100 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>

    
    <header class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 py-6 px-6 rounded-2xl mb-8 shadow-sm">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl uppercase text-slate-800 font-extrabold tracking-tight flex items-center">
                    <span class="p-2.5 bg-blue-600 text-white rounded-xl shadow-md mr-3 inline-flex items-center justify-center">
                        <i class="fas fa-coins text-xl"></i>
                    </span>
                    Token Price Settings
                </h1>
                <p class="text-slate-600 text-sm mt-2 font-medium max-w-2xl">
                    Manage all 5 token price tiers plus the display coin value. Each metric is tied to a specific system operation and calculation engine.
                </p>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-4 text-white flex items-center justify-between shadow-inner">
                    <span class="font-bold text-base flex items-center gap-2">
                        <i class="fas fa-tag"></i> Current System Values
                    </span>
                    <span class="text-xs bg-amber-700 bg-opacity-40 px-2.5 py-1 rounded-full font-semibold uppercase tracking-wider">Live</span>
                </div>
                <div class="p-5">
                    <?php if($setting): ?>
                    <?php $s = $setting; ?>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm text-gray-500 font-medium dark:text-gray-400">Token Symbol</span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300 border border-blue-200"><?php echo e($s->token_symbol); ?></span>
                        </div>

                        <div class="pt-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 block mb-3">Active Token Prices</span>
                            <div class="space-y-2.5 text-sm">
                                <div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-lg dark:bg-gray-700/50">
                                    <span class="text-gray-600 dark:text-gray-300">UVP Purchase</span>
                                    <span class="font-bold font-mono text-gray-900 dark:text-white">$<?php echo e(number_format($s->uvp_price, 6)); ?></span>
                                </div>
                                <div class="flex justify-between items-center bg-gray-50 p-2.5 rounded-lg dark:bg-gray-700/50">
                                    <span class="text-gray-600 dark:text-gray-300">Renewal <span class="text-xs text-gray-400">(30-day)</span></span>
                                    <span class="font-bold font-mono text-gray-900 dark:text-white">$<?php echo e(number_format($s->renewal_price, 6)); ?></span>
                                </div>
                                <div class="flex justify-between items-center bg-blue-50/60 p-2.5 rounded-lg border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800">
                                    <span class="text-blue-800 font-medium dark:text-blue-300">Swap Price</span>
                                    <span class="font-bold font-mono text-blue-700 dark:text-blue-400">$<?php echo e(number_format($s->swap_price, 6)); ?></span>
                                </div>
                                <div class="flex justify-between items-center p-2.5 rounded-lg text-gray-400">
                                    <span>Trading <small class="text-xs italic">(reserved)</small></span>
                                    <span class="font-mono">$<?php echo e(number_format($s->trading_price, 6)); ?></span>
                                </div>
                                <div class="flex justify-between items-center p-2.5 rounded-lg text-gray-400">
                                    <span>Package <small class="text-xs italic">(reserved)</small></span>
                                    <span class="font-mono">$<?php echo e(number_format($s->package_price, 6)); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 block mb-2">Reference Valuation</span>
                            <div class="flex justify-between items-center bg-emerald-50 p-3 rounded-xl border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800">
                                <span class="text-emerald-800 font-semibold text-sm dark:text-emerald-300">Coin Value</span>
                                <span class="font-extrabold font-mono text-emerald-600 text-base dark:text-emerald-400">$<?php echo e(number_format($s->coin_value, 6)); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-700">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs text-slate-600 dark:bg-slate-700/50 dark:border-slate-600 dark:text-slate-300 space-y-2">
                            <p class="font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5 text-sm">
                                <i class="fas fa-calculator text-blue-500"></i> Valuation Example
                            </p>
                            <?php $ex = $s->uvp_price > 0 ? 1000/$s->uvp_price : 0; ?>
                            <p class="leading-relaxed">
                                A <strong>$1,000</strong> investment at UVP price yields:<br>
                                <span class="text-blue-600 font-bold font-mono text-sm dark:text-blue-400"><?php echo e(number_format($ex, 0)); ?></span> <span class="font-semibold"><?php echo e($s->token_symbol); ?></span> locked tokens.
                            </p>
                            <p class="leading-relaxed pt-1 border-t border-slate-200 dark:border-slate-600">
                                Swapping 1,000 tokens &rarr; <span class="font-bold text-emerald-600 dark:text-emerald-400">$<?php echo e(number_format(1000*$s->swap_price, 2)); ?></span> cashout.<br>
                                <span class="text-[11px] text-slate-400">(Calculated using <code>swap_price</code>; <code>coin_value</code> is display-only)</span>
                            </p>
                        </div>
                        <p class="text-slate-400 text-[11px] mt-3 text-right flex items-center justify-end gap-1">
                            <i class="far fa-clock"></i> Last updated: <?php echo e($s->updated_at->format('d M Y, H:i')); ?>

                        </p>
                    </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm py-4 text-center">No token settings configured yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-8">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden dark:bg-gray-800 dark:border-gray-700">
                <div class="bg-blue-600 px-6 py-4 text-white flex items-center justify-between">
                    <span class="font-bold text-base flex items-center gap-2">
                        <i class="fas fa-edit"></i> Update Token Prices &amp; Symbols
                    </span>
                    <span class="text-xs bg-blue-700 px-3 py-1 rounded-full font-medium">Admin Control</span>
                </div>
                <div class="p-6 sm:p-8">
                    <form method="POST" action="<?php echo e(route('admin.token-settings.update')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                        
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                                <i class="fas fa-sliders-h text-blue-500"></i> General Configuration
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        Token Symbol <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="token_symbol" value="<?php echo e(old('token_symbol', $setting->token_symbol ?? 'FONE')); ?>" required maxlength="20" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 font-semibold dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition duration-150">
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Display symbol across the platform (e.g. FONE).</p>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        Coin Value (USD per token) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center px-4 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                        <input type="number" name="coin_value" step="0.000001" min="0.000001" value="<?php echo e(old('coin_value', $setting->coin_value ?? '0.002000')); ?>" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition duration-150">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Used for swap &amp; token withdrawal display valuation.</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                                <i class="fas fa-layer-group text-blue-500"></i> Token Purchase &amp; Usage Tiers
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        UVP Purchase Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center px-4 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                        <input type="number" name="uvp_price" step="0.000001" min="0.000001" value="<?php echo e(old('uvp_price', $setting->uvp_price ?? '0.002500')); ?>" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition duration-150">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Investment &divide; this price = LOCKED tokens credited upon purchase.</p>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        Renewal Price (every 30 days) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm">
                                        <span class="inline-flex items-center px-4 text-sm font-bold text-gray-500 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">$</span>
                                        <input type="number" name="renewal_price" step="0.000001" min="0.000001" value="<?php echo e(old('renewal_price', $setting->renewal_price ?? '0.002500')); ?>" required class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono font-semibold dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition duration-150">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Trading voucher &divide; this price = AVAILABLE tokens on renewal.</p>
                                </div>

                                <div class="sm:col-span-2 bg-blue-50/50 p-4 rounded-xl border border-blue-100 dark:bg-blue-900/10 dark:border-blue-800/50">
                                    <label class="block mb-2 text-sm font-bold text-blue-900 dark:text-blue-300">
                                        Swap Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm max-w-md">
                                        <span class="inline-flex items-center px-4 text-sm font-bold text-blue-700 bg-blue-100 border border-r-0 border-blue-300 rounded-l-xl dark:bg-blue-800 dark:text-blue-200 dark:border-blue-700">$</span>
                                        <input type="number" name="swap_price" step="0.000001" min="0.000001" value="<?php echo e(old('swap_price', $setting->swap_price ?? '0.002000')); ?>" required class="rounded-none rounded-r-xl bg-white border border-blue-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono font-bold dark:bg-gray-700 dark:border-blue-700 dark:text-white transition duration-150">
                                    </div>
                                    <p class="mt-2 text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                                        <span class="font-bold underline">Critical for /user/token/swap:</span> Tokens &times; this price = USD credited directly to the user's Cashout balance.<br>
                                        <span class="italic text-blue-600 dark:text-blue-400">Example: 100,000 tokens &times; $0.0025 = $250.00 instant cashout credit.</span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Trading Price <span class="text-xs font-normal text-amber-600 bg-amber-50 px-2 py-0.5 rounded ml-1 dark:bg-amber-900/40 dark:text-amber-300">Reserved</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm opacity-80">
                                        <span class="inline-flex items-center px-4 text-sm text-gray-400 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:border-gray-600">$</span>
                                        <input type="number" name="trading_price" step="0.000001" min="0.000001" value="<?php echo e(old('trading_price', $setting->trading_price ?? '0.002500')); ?>" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Reserved for future token buy/sell trading module.
                                    </p>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Package Price <span class="text-xs font-normal text-amber-600 bg-amber-50 px-2 py-0.5 rounded ml-1 dark:bg-amber-900/40 dark:text-amber-300">Reserved</span>
                                    </label>
                                    <div class="flex rounded-xl shadow-sm opacity-80">
                                        <span class="inline-flex items-center px-4 text-sm text-gray-400 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl dark:bg-gray-700 dark:border-gray-600">$</span>
                                        <input type="number" name="package_price" step="0.000001" min="0.000001" value="<?php echo e(old('package_price', $setting->package_price ?? '0.002500')); ?>" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-600 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 font-mono dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                                        <i class="fas fa-clock"></i> Reserved for future referral-package purchase flow.
                                    </p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-6">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Audit Notes <span class="text-xs font-normal text-gray-400">(Optional reason for price change)</span>
                                </label>
                                <textarea name="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition duration-150" placeholder="e.g., Adjusted UVP price to reflect market demand and liquidity target..."></textarea>
                            </div>

                            <div class="p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl text-amber-800 text-xs dark:bg-gray-700 dark:text-amber-300 flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                                <div class="leading-relaxed">
                                    <strong class="font-bold">Important Notice:</strong> Price adjustments take effect immediately for all <span class="underline font-bold">future</span> transactions and swaps. Existing user balances or historical transactions are never recalculated.
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full sm:w-auto min-w-[240px] text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-extrabold rounded-xl text-base px-8 py-3.5 text-center inline-flex items-center justify-center gap-2.5 shadow-lg shadow-blue-500/25 transition duration-200 ease-in-out transform active:scale-[0.99] dark:focus:ring-blue-800">
                                    <i class="fas fa-save text-lg"></i>
                                    <span>Save All Price Updates</span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/token-settings.blade.php ENDPATH**/ ?>
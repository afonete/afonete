

<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg text-xl">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit FOM Licence Miner Package</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update package details for <span class="font-bold text-slate-700"><?php echo e($package->name); ?></span>.</p>
            </div>
        </div>
        <div>
            <a href="<?php echo e(route('admin.fom-licence-miner.index')); ?>" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-arrow-left"></i> <span>Back to Packages</span>
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-4xl mx-auto">
        
        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
                <ul class="list-disc pl-5 space-y-1 font-semibold">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php
            $numPrice       = \App\Models\FomLicenceMiner::cleanNum($package->price);
            $numTokens      = \App\Models\FomLicenceMiner::cleanNum($package->tokens);
            $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($package->token_bonus);
            $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($package->direct_sponsors);
            $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($package->affiliate_vbonus);
            $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($package->volume_point);
            $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($package->total_return);
        ?>

        <form action="<?php echo e(route('admin.fom-licence-miner.update', $package->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Package Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name', $package->name)); ?>" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. BASIC, STARTER, PRO, SUPER">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Price (USDT) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="<?php echo e(old('price', $numPrice)); ?>" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 25000">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Display Price Label (Optional text)</label>
                    <input type="text" name="display_price" value="<?php echo e(old('display_price', $package->display_price)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 25K - 200K USDT">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Tokens Allocated (Numeric) <span class="text-red-500">*</span></label>
                    <input type="number" name="tokens" value="<?php echo e(old('tokens', $numTokens)); ?>" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 20833333">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: TOKEN | <?php echo e(number_format($numTokens)); ?> <?php echo e($tokenSymbol); ?></span>
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Duration (Days) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_days" value="<?php echo e(old('duration_days', $package->duration_days)); ?>" required
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="600">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Token Bonus (%)</label>
                    <input type="number" step="0.01" name="token_bonus" value="<?php echo e(old('token_bonus', $numTokenBonus)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 100 for 100%">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: Token Bonus: X<?php echo e($numTokenBonus); ?>%</span>
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Direct Sponsors Bonus (%)</label>
                    <input type="number" step="0.01" name="direct_sponsors" value="<?php echo e(old('direct_sponsors', $numSponsors)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 20 for 20%">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Affiliate V.Bonus (%)</label>
                    <input type="number" step="0.01" name="affiliate_vbonus" value="<?php echo e(old('affiliate_vbonus', $numAffiliate)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="10">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Space Shop Room Limit (Text)</label>
                    <input type="text" name="space_shop_limit" value="<?php echo e(old('space_shop_limit', $package->space_shop_limit)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Space Shop Room Limit">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Volume Bonus / Point (Numeric)</label>
                    <input type="number" name="volume_point" value="<?php echo e(old('volume_point', $numVolumePoint)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 6000 or 4">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: Volume Bonus: <?php echo e(number_format($numVolumePoint)); ?></span>
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Unlocked Per Week (Text)</label>
                    <input type="text" name="unlocked_per_week" value="<?php echo e(old('unlocked_per_week', $package->unlocked_per_week)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="YES">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Loan Access (Text)</label>
                    <input type="text" name="allowed_loan" value="<?php echo e(old('allowed_loan', $package->allowed_loan)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Allowed Loan or Not Allowed Loan">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Investment Option Feature (Text)</label>
                    <input type="text" name="investment_option" value="<?php echo e(old('investment_option', $package->investment_option)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="Access Investment feature">
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Total Return Tokens (Numeric)</label>
                    <input type="number" name="total_return" value="<?php echo e(old('total_return', $numTotalReturn)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="e.g. 41666666">
                    <span class="text-[11px] text-slate-400 block mt-1">Displays as: TOTAL RETURN: <?php echo e(number_format($numTotalReturn)); ?> <?php echo e($tokenSymbol); ?></span>
                </div>

                
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1.5">Display Sort Order</label>
                    <input type="number" name="sort_order" value="<?php echo e(old('sort_order', $package->sort_order)); ?>"
                           class="w-full bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition-all"
                           placeholder="0">
                </div>

                
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $package->is_active) ? 'checked' : ''); ?> class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 relative"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Active Package (Visible on /investment-package)</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="<?php echo e(route('admin.fom-licence-miner.index')); ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-5 rounded-lg transition-all text-sm">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Update Package
                </button>
            </div>

        </form>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/fom-licence-miner/edit.blade.php ENDPATH**/ ?>
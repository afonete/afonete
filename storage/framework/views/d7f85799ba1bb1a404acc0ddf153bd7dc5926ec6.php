

<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    
    <?php if(session('message')): ?>
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-semibold flex items-center justify-between" role="alert">
            <span><i class="fas fa-check-circle mr-1"></i> <?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?>

    
    <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 mb-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 text-amber-600 p-3 rounded-lg text-xl">
                <i class="fas fa-microchip"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">FOM Licence Miner Packages</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Configure numerical &amp; textual plan values for <code class="bg-slate-100 text-amber-700 px-1.5 py-0.5 rounded border border-slate-200 font-mono text-[11px]">/investment-package</code>.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold px-3 py-2 rounded-lg flex items-center gap-1.5">
                <i class="fas fa-coins text-amber-500"></i> Active Token: <span class="text-amber-900 font-extrabold uppercase"><?php echo e($tokenSymbol); ?></span>
            </span>
            <a href="<?php echo e(route('admin.fom-licence-miner.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-plus"></i> <span>New Package</span>
            </a>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        
        
        <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <span class="font-bold text-slate-800 text-sm">Active &amp; Configured Licence Miner Plans</span>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" id="searchInput"
                       class="bg-white border border-slate-300 text-slate-800 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-9 p-2.5 transition-all" 
                       placeholder="Search packages, prices, bonuses...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="myTable">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12 text-center">#</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Package Name</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Price (USDT)</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tokens Allocated</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bonuses &amp; Sponsors</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Duration &amp; Limits</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Return</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $numPrice       = \App\Models\FomLicenceMiner::cleanNum($pkg->price);
                            $numTokens      = \App\Models\FomLicenceMiner::cleanNum($pkg->tokens);
                            $numTokenBonus  = \App\Models\FomLicenceMiner::cleanNum($pkg->token_bonus);
                            $numSponsors    = \App\Models\FomLicenceMiner::cleanNum($pkg->direct_sponsors);
                            $numAffiliate   = \App\Models\FomLicenceMiner::cleanNum($pkg->affiliate_vbonus);
                            $numVolumePoint = \App\Models\FomLicenceMiner::cleanNum($pkg->volume_point);
                            $numTotalReturn = \App\Models\FomLicenceMiner::cleanNum($pkg->total_return);
                        ?>
                        <tr class="hover:bg-slate-50 border-b border-slate-100 transition-all">
                            <td class="p-4 text-sm font-semibold text-slate-500 text-center">
                                <?php echo e($loop->iteration); ?>

                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 text-sm block"><?php echo e($pkg->name); ?></span>
                                <span class="text-xs text-slate-400 mt-0.5 block">Display: <?php echo e($pkg->display_price ?: (number_format($numPrice, 0) . ' USDT')); ?></span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="font-extrabold text-emerald-600 block">$<?php echo e(number_format($numPrice, 2)); ?></span>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="font-bold text-indigo-600 block"><?php echo e(number_format($numTokens)); ?> <?php echo e($tokenSymbol); ?></span>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Bonus: X<?php echo e($numTokenBonus); ?>%</span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="space-y-0.5 text-xs">
                                    <div><span class="text-slate-400">Direct Sponsor:</span> <span class="font-semibold text-slate-700"><?php echo e($numSponsors); ?>%</span></div>
                                    <div><span class="text-slate-400">Affiliate V.Bonus:</span> <span class="font-semibold text-slate-700"><?php echo e($numAffiliate); ?>%</span></div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="text-xs space-y-0.5">
                                    <span class="bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-100 inline-block mb-1"><?php echo e($pkg->duration_days); ?> Days</span>
                                    <div class="text-slate-500 text-[11px]">
                                        <?php if($numVolumePoint > 10): ?>
                                            Volume Bonus: <?php echo e(number_format($numVolumePoint)); ?>

                                        <?php else: ?>
                                            Volume Point: <?php echo e($numVolumePoint); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="text-slate-400 text-[11px]"><?php echo e($pkg->allowed_loan); ?></div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-slate-700">
                                <?php echo e(number_format($numTotalReturn)); ?> <?php echo e($tokenSymbol); ?>

                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                <form action="<?php echo e(route('admin.fom-licence-miner.toggle', $pkg->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="cursor-pointer focus:outline-none">
                                        <?php if($pkg->is_active): ?>
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 hover:bg-emerald-200 transition-all">
                                                <i class="fas fa-check-circle"></i> Active
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1 hover:bg-slate-300 transition-all">
                                                <i class="fas fa-times-circle"></i> Inactive
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-sm text-right">
                                <div class="flex justify-end gap-2">
                                    
                                    <a href="<?php echo e(route('admin.fom-licence-miner.edit', $pkg->id)); ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 rounded-lg shadow-sm transition-all text-xs flex items-center justify-center" title="Edit Package">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    
                                    <form action="<?php echo e(route('admin.fom-licence-miner.destroy', $pkg->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this FOM Licence Miner package?');" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg shadow-sm transition-all text-xs flex items-center justify-center" title="Delete Package">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-8 text-slate-400 text-sm">
                                <i class="fas fa-folder-open mb-2 text-slate-300 block text-2xl"></i>
                                No FOM Licence Miner packages found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script>
    $(document).ready(function(){
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#myTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/fom-licence-miner/index.blade.php ENDPATH**/ ?>
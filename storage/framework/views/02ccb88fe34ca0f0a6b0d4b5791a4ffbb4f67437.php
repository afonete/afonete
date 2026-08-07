<?php $__env->startSection('contents'); ?>
<div class="container mx-auto py-6 px-6 bg-slate-50 min-h-screen">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="bg-white hover:bg-slate-100 text-slate-700 font-semibold py-2 px-4 border border-slate-200 rounded-lg shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> <span>Back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Deposit Wallets &amp; Accounts</h1>
                <p class="text-xs text-slate-500 mt-1">Configure deposit options, perfect money accounts, or crypto addresses shown on user deposit screens.</p>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 font-semibold" role="alert">
            <i class="fas fa-check-circle mr-1"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 font-semibold" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.deposit-wallets.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="mb-8">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fab fa-bitcoin text-lg text-warning"></i> <span>Crypto Wallets</span>
            </h4>
            <?php $__currentLoopData = ($wallets['crypto'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="mb-8">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-lg text-emerald-500"></i> <span>Advcash Accounts</span>
            </h4>
            <?php $__currentLoopData = ($wallets['advcash'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="mb-8">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fas fa-coins text-lg text-amber-500"></i> <span>Perfect Money Accounts</span>
            </h4>
            <?php $__currentLoopData = ($wallets['perfect_money'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-emerald-500/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fas fa-save"></i> <span>Save All Wallets</span>
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/settings/deposit-wallets.blade.php ENDPATH**/ ?>
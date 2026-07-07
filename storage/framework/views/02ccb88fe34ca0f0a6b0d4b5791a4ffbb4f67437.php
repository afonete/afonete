<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-2">
        <i class="fas fa-wallet text-warning mr-2"></i> Deposit Wallets &amp; Accounts
    </h3>
    <p class="text-muted">
        Configure every deposit option shown on the user deposit page.
        For crypto, set the wallet address + network. For Advcash / Perfect Money, set the account number.
    </p>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.deposit-wallets.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fab fa-bitcoin mr-1"></i> Crypto Wallets
        </h4>
        <?php $__currentLoopData = ($wallets['crypto'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fas fa-money-bill-wave mr-1"></i> Advcash
        </h4>
        <?php $__currentLoopData = ($wallets['advcash'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <h4 class="text-uppercase text-muted mt-4 mb-2">
            <i class="fas fa-coins mr-1"></i> Perfect Money
        </h4>
        <?php $__currentLoopData = ($wallets['perfect_money'] ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('admin.settings._wallet_card', ['w' => $w, 'showNetwork' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="text-right mt-4">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Wallets
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/settings/deposit-wallets.blade.php ENDPATH**/ ?>
<?php $active = $active ?? ''; ?>
<div class="mb-3">
    <h2 class="mb-1"><i class="fas fa-coins text-warning mr-2"></i>Token Wallets</h2>
    <div class="d-flex flex-wrap mt-2" style="gap:6px;">
        
        <a href="<?php echo e(route('user.token.locked')); ?>"
           class="btn btn-sm <?php echo e($active=='locked' ? 'btn-secondary' : 'btn-outline-secondary'); ?>">
            <i class="fas fa-lock mr-1"></i> Locked Token
        </a>
        
        <a href="<?php echo e(route('user.token.available')); ?>"
           class="btn btn-sm <?php echo e($active=='available' ? 'btn-success' : 'btn-outline-success'); ?>">
            <i class="fas fa-check-circle mr-1"></i> Available Token
        </a>
        
        <a href="<?php echo e(route('user.token.transfer')); ?>"
           class="btn btn-sm <?php echo e($active=='transfer' ? 'btn-primary' : 'btn-outline-primary'); ?>">
            <i class="fas fa-paper-plane mr-1"></i> Transfer
        </a>
        <a href="<?php echo e(route('user.token.swap')); ?>"
           class="btn btn-sm <?php echo e($active=='swap' ? 'btn-warning' : 'btn-outline-warning'); ?>">
            <i class="fas fa-sync-alt mr-1"></i> Swap
        </a>
        <a href="<?php echo e(route('user.token.withdraw')); ?>"
           class="btn btn-sm <?php echo e($active=='withdraw' ? 'btn-danger' : 'btn-outline-danger'); ?>">
            <i class="fas fa-arrow-up mr-1"></i> Withdraw
        </a>
    </div>
    <small class="text-muted d-block mt-1">
        Locked → (auto on expiry) → Available → (manual transfer) → Free Token → Transfer / Swap / Withdraw
    </small>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/_nav.blade.php ENDPATH**/ ?>
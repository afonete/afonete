<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-wallet text-warning mr-2"></i> Deposit Wallets</h3>
    <p class="text-muted">These are the company wallet addresses that users send USDT to when making deposits.</p>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.settings.deposit-wallets.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-<?php echo e($w->is_active ? 'success' : 'secondary'); ?> text-white">
                <strong><?php echo e($w->network); ?></strong> · <?php echo e($w->label); ?>

                <span class="float-right"><?php echo e($w->currency); ?></span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="small text-muted">Wallet Address <span class="text-danger">*</span></label>
                        <input type="text" name="wallets[<?php echo e($w->id); ?>][wallet_address]" class="form-control"
                               value="<?php echo e($w->wallet_address); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted">Label</label>
                        <input type="text" name="wallets[<?php echo e($w->id); ?>][label]" class="form-control"
                               value="<?php echo e($w->label); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted">Active?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                   name="wallets[<?php echo e($w->id); ?>][is_active]" value="1"
                                   id="active_<?php echo e($w->id); ?>" <?php echo e($w->is_active ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="active_<?php echo e($w->id); ?>">Yes</label>
                        </div>
                    </div>

                    <div class="col-md-3 mt-2">
                        <label class="small text-muted">Min amount ($)</label>
                        <input type="number" step="0.01" min="0" name="wallets[<?php echo e($w->id); ?>][min_amount]" class="form-control"
                               value="<?php echo e($w->min_amount); ?>">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small text-muted">Max amount ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="wallets[<?php echo e($w->id); ?>][max_amount]" class="form-control"
                               value="<?php echo e($w->max_amount); ?>">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="small text-muted">Notes (internal)</label>
                        <input type="text" name="wallets[<?php echo e($w->id); ?>][notes]" class="form-control"
                               value="<?php echo e($w->notes); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="text-right">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Wallets
            </button>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/settings/deposit-wallets.blade.php ENDPATH**/ ?>
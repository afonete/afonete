<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-cog text-secondary mr-2"></i> Rank Criteria Settings</h3>
    <p class="text-muted">Edit the 5 ranks. Changes apply to all future eligibility checks.</p>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.rank.settings.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php $__currentLoopData = $ranks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-primary text-white font-weight-bold">
                <?php echo e($rank->order); ?>. <?php echo e($rank->name); ?>

                <small class="float-right">slug: <code><?php echo e($rank->slug); ?></code> · level <?php echo e($rank->level); ?></small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small">Min Active Direct Referrals</label>
                        <input type="number" min="0" name="ranks[<?php echo e($rank->id); ?>][min_active_direct_referrals]" class="form-control"
                               value="<?php echo e($rank->min_active_direct_referrals); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Associates from Direct</label>
                        <input type="number" min="0" name="ranks[<?php echo e($rank->id); ?>][min_associates_from_direct]" class="form-control"
                               value="<?php echo e($rank->min_associates_from_direct); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Directors from Direct</label>
                        <input type="number" min="0" name="ranks[<?php echo e($rank->id); ?>][min_directors_from_direct]" class="form-control"
                               value="<?php echo e($rank->min_directors_from_direct); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Regional Supervisors from Direct</label>
                        <input type="number" min="0" name="ranks[<?php echo e($rank->id); ?>][min_regional_supervisors_from_direct]" class="form-control"
                               value="<?php echo e($rank->min_regional_supervisors_from_direct); ?>">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label class="small">Min Direct Ref Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[<?php echo e($rank->id); ?>][min_direct_referral_investment]" class="form-control"
                               value="<?php echo e($rank->min_direct_referral_investment); ?>">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="small">Min Total Network Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[<?php echo e($rank->id); ?>][min_total_investment]" class="form-control"
                               value="<?php echo e($rank->min_total_investment); ?>">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="small">Reward Amount ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[<?php echo e($rank->id); ?>][reward_amount]" class="form-control"
                               value="<?php echo e($rank->reward_amount); ?>">
                    </div>

                    <div class="col-md-3 mt-2">
                        <label class="small">Reward % (for AM weekly)</label>
                        <input type="number" min="0" step="0.01" name="ranks[<?php echo e($rank->id); ?>][reward_percentage]" class="form-control"
                               value="<?php echo e($rank->reward_percentage); ?>">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small">AM: Min Qualifying Direct Refs</label>
                        <input type="number" min="0" name="ranks[<?php echo e($rank->id); ?>][am_min_active_direct_investment_users]" class="form-control"
                               value="<?php echo e($rank->am_min_active_direct_investment_users); ?>">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small">AM: Per-Ref Min Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[<?php echo e($rank->id); ?>][am_per_user_min_investment]" class="form-control"
                               value="<?php echo e($rank->am_per_user_min_investment); ?>">
                    </div>
                    <div class="col-md-3 mt-2 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="ranks[<?php echo e($rank->id); ?>][is_active]" value="1"
                                   id="active_<?php echo e($rank->id); ?>" <?php echo e($rank->is_active ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="active_<?php echo e($rank->id); ?>">Active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Rank Criteria
            </button>
        </div>
    </form>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral/rank-settings.blade.php ENDPATH**/ ?>
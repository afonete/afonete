<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-user-check text-success mr-2"></i> Users Who Meet Rank Criteria</h3>
    <p class="text-muted">
        Live criteria check. Users here qualify but have not yet had a rank application created
        (the <code>ranks:check</code> cron will pick them up at 00:10 daily).
    </p>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            <?php if(empty($rows)): ?>
                <p class="text-muted p-3 mb-0">No users currently meet any rank criteria.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th><th>Email</th><th>Rank</th><th>Reward</th>
                            <th>Active Directs</th><th>Direct Inv.</th><th>Total Inv.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($row['user']->name); ?></strong></td>
                                <td><small><?php echo e($row['user']->email); ?></small></td>
                                <td><span class="badge badge-success"><?php echo e($row['rank']->name); ?></span></td>
                                <td><?php echo e($row['rank']->rewardLabel()); ?></td>
                                <td><?php echo e($row['actual']['active_direct_referrals']); ?></td>
                                <td>$<?php echo e(number_format($row['actual']['direct_referral_investment'], 2)); ?></td>
                                <td>$<?php echo e(number_format($row['actual']['total_investment'], 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral/eligible.blade.php ENDPATH**/ ?>
<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-3"><i class="fas fa-coins text-primary mr-2"></i> Referral Bonuses — Platform Overview</h3>

    
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">All-Time Total</small>
                <div class="font-weight-bold text-primary" style="font-size:1.8rem;">$<?php echo e(number_format($totals['all_time'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Currently Withdrawable</small>
                <div class="font-weight-bold text-success" style="font-size:1.8rem;">$<?php echo e(number_format($totals['withdrawable'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Pending (next Monday)</small>
                <div class="font-weight-bold text-warning" style="font-size:1.8rem;">$<?php echo e(number_format($totals['pending'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Already Paid Out</small>
                <div class="font-weight-bold text-info" style="font-size:1.8rem;">$<?php echo e(number_format($totals['paid'], 2)); ?></div>
            </div></div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <a href="<?php echo e(route('admin.referral.withdrawals')); ?>" class="btn btn-warning">
            <i class="fas fa-money-bill-wave mr-1"></i> Weekly Withdrawals Queue
        </a>
        <a href="<?php echo e(route('admin.rank.applications')); ?>" class="btn btn-primary">
            <i class="fas fa-trophy mr-1"></i> Rank Applications
        </a>
        <a href="<?php echo e(route('admin.rank.eligible')); ?>" class="btn btn-success">
            <i class="fas fa-user-check mr-1"></i> Eligible Users (Criteria Overview)
        </a>
        <a href="<?php echo e(route('admin.rank.settings')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-cog mr-1"></i> Rank Settings
        </a>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold">
            <i class="fas fa-users mr-1"></i> Per-User Totals (Top Earners)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th><th>Email</th><th>Total</th><th>Pending</th>
                            <th>Withdrawable</th><th>Withdrawn</th><th>Rows</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $perUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $u = $users[$row->user_id] ?? null; ?>
                            <tr>
                                <td><strong><?php echo e($u->name ?? '—'); ?></strong></td>
                                <td><small><?php echo e($u->email ?? '—'); ?></small></td>
                                <td class="font-weight-bold text-primary">$<?php echo e(number_format($row->total_bonus, 2)); ?></td>
                                <td class="text-warning">$<?php echo e(number_format($row->pending, 2)); ?></td>
                                <td class="text-success">$<?php echo e(number_format($row->withdrawable, 2)); ?></td>
                                <td class="text-info">$<?php echo e(number_format($row->withdrawn, 2)); ?></td>
                                <td><small><?php echo e($row->row_count); ?></small></td>
                                <td>
                                    <a href="<?php echo e(route('admin.referral.bonuses.user', $row->user_id)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="8" class="text-center text-muted p-3">No referral bonuses recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($perUser->links()); ?></div>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral/bonuses.blade.php ENDPATH**/ ?>
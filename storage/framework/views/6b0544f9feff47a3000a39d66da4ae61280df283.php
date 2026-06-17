<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-money-bill-wave text-warning mr-2"></i> Weekly Withdrawal Queue</h3>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-hourglass-half mr-1"></i>
            Pending (<?php echo e($pending->count()); ?>)
        </div>
        <div class="card-body p-0">
            <?php if($pending->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No pending withdrawals.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Email</th><th>Week</th><th>Amount</th><th>Ref</th><th>Submitted</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($w->user->name); ?></strong></td>
                                <td><small><?php echo e($w->user->email); ?></small></td>
                                <td><?php echo e($w->week_start->format('d M Y')); ?></td>
                                <td class="font-weight-bold text-success">$<?php echo e(number_format($w->amount, 2)); ?></td>
                                <td><small><?php echo e($w->transaction_no); ?></small></td>
                                <td><small><?php echo e($w->created_at->format('d M Y H:i')); ?></small></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.referral.withdrawals.approve', $w->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <input name="admin_notes" class="form-control form-control-sm d-inline-block" style="width:160px" placeholder="Notes (optional)">
                                        <button name="mark_paid" value="1" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Pay</button>
                                        <button name="mark_paid" value="0" class="btn btn-sm btn-info"><i class="fas fa-thumbs-up"></i> Approve</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.referral.withdrawals.reject', $w->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Approved / Paid</div>
        <div class="card-body p-0">
            <?php if($approved->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No history yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Week</th><th>Amount</th><th>Status</th><th>Processed</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $approved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($w->user->name); ?></td>
                                <td><?php echo e($w->week_start->format('d M Y')); ?></td>
                                <td>$<?php echo e(number_format($w->amount, 2)); ?></td>
                                <td><?php echo $w->statusBadge(); ?></td>
                                <td><small><?php echo e(optional($w->processed_at)->format('d M Y')); ?></small></td>
                                <td><small class="text-muted"><?php echo e($w->admin_notes ?? '—'); ?></small></td>
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
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral/withdrawals.blade.php ENDPATH**/ ?>
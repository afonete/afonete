<?php echo $__env->make('admin.admin-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('admin.referral.bonuses')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-trophy text-primary mr-2"></i> Rank Applications</h3>

    <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-hourglass-half mr-1"></i>
            Pending Review (<?php echo e($pending->count()); ?>)
        </div>
        <div class="card-body p-0">
            <?php if($pending->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No pending applications. System checks daily via <code>php artisan ranks:check</code>.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Email</th><th>Rank</th><th>Reward</th><th>Detected</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($ur->user->name); ?></strong></td>
                                <td><small><?php echo e($ur->user->email); ?></small></td>
                                <td><span class="badge badge-primary"><?php echo e($ur->rank_name); ?></span></td>
                                <td><?php echo e($ur->rank->rewardLabel()); ?></td>
                                <td><small><?php echo e(optional($ur->detected_at)->format('d M Y H:i')); ?></small></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.rank.approve', $ur->id)); ?>" enctype="multipart/form-data" class="d-inline-block">
                                        <?php echo csrf_field(); ?>
                                        <input type="file" name="congratulation_image" accept="image/*" required class="form-control form-control-sm mb-1" style="width:220px">
                                        <input type="text" name="admin_notes" class="form-control form-control-sm mb-1" placeholder="Congratulation message" style="width:220px">
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check mr-1"></i> Approve + Upload</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.rank.reject', $ur->id)); ?>" class="d-inline-block">
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
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Approved</div>
        <div class="card-body p-0">
            <?php if($approved->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No approved ranks yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Rank</th><th>Reward</th><th>Reviewed</th><th>Picture</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $approved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($ur->user->name); ?></td>
                                <td><span class="badge badge-primary"><?php echo e($ur->rank_name); ?></span></td>
                                <td>$<?php echo e(number_format($ur->reward_amount, 2)); ?></td>
                                <td><small><?php echo e(optional($ur->reviewed_at)->format('d M Y')); ?></small></td>
                                <td>
                                    <?php if($ur->congratulation_image): ?>
                                        <a href="<?php echo e(asset('storage/' . $ur->congratulation_image)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                    <?php else: ?> — <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($approved->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral/rank-applications.blade.php ENDPATH**/ ?>
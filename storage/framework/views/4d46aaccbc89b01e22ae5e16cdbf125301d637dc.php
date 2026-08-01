<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <?php echo $__env->make('user.referral._nav', ['active' => 'downline'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Direct Referrals</small>
                <div class="font-weight-bold" style="font-size:1.6rem;"><?php echo e($directCount); ?></div>
                <small class="text-muted"><?php echo e($activeDirect); ?> active (with package)</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Direct Ref Investment</small>
                <div class="font-weight-bold text-primary" style="font-size:1.6rem;">$<?php echo e(number_format($directInv, 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Total Network Investment</small>
                <div class="font-weight-bold text-success" style="font-size:1.6rem;">$<?php echo e(number_format($totalInv, 2)); ?></div>
                <small class="text-muted">Direct + indirect (all levels)</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Your Referral Link</small>
                <input type="text" class="form-control form-control-sm mt-1" readonly
                       value="<?php echo e(url('/register?referral=' . (auth()->user()->activation ?? auth()->user()->user ?? auth()->id()))); ?>">
                <small class="text-muted">Share to invite referrals.</small>
            </div></div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-users mr-1"></i> Direct Referrals</div>
        <div class="card-body p-0">
            <?php if($directs->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No direct referrals yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Name</th><th>Email</th><th>Active Package</th><th>Investment</th><th>Joined</th><th>Rank</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $directs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $active = $d->investments->first();
                                $highest = $d->userRanks->first();
                            ?>
                            <tr>
                                <td><?php echo e($d->name); ?></td>
                                <td><small><?php echo e($d->email); ?></small></td>
                                <td>
                                    <?php if($active): ?>
                                        <span class="badge badge-success"><?php echo e($active->package); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">No active package</span>
                                    <?php endif; ?>
                                </td>
                                <td>$<?php echo e(number_format($active->amount ?? 0, 2)); ?></td>
                                <td><small><?php echo e($d->created_at->format('d M Y')); ?></small></td>
                                <td>
                                    <?php if($highest): ?>
                                        <span class="badge badge-primary"><?php echo e($highest->rank_name); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center border-top">
                <?php echo e($directs->links('pagination::bootstrap-4')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/referral/downline.blade.php ENDPATH**/ ?>
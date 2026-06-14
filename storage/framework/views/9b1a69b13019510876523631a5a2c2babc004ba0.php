<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <header class="bg-blue-50 py-8 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            <i class="fas fa-users mr-2"></i> Referral Bonuses — All Users
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Overview of every user's referral network and commission balance.
        </p>
    </header>

    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                <h3 class="font-weight-bold">$<?php echo e(number_format($totalCommissionPaid, 2)); ?></h3>
                <p class="text-muted mb-0">Total Commission Balances</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-user-friends fa-2x text-info mb-2"></i>
                <h3 class="font-weight-bold"><?php echo e($users->count()); ?></h3>
                <p class="text-muted mb-0">Users with Referrals or Bonuses</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                <h3 class="font-weight-bold"><?php echo e($users->where('active_referrals', '>', 0)->count()); ?></h3>
                <p class="text-muted mb-0">Users with Active Referrals</p>
            </div>
        </div>
    </div>

    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white font-weight-bold border-bottom">
            <i class="fas fa-list mr-1"></i> Users & Their Referral Bonuses
            <small class="text-muted ml-2">Sorted by highest commission balance</small>
        </div>
        <div class="card-body p-0">
            <?php if($users->isEmpty()): ?>
                <p class="text-muted p-4">No users with referral activity yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Package Status</th>
                            <th>Total Referrals</th>
                            <th>Active Referrals</th>
                            <th>Commission Balance</th>
                            <th>Joined</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td>
                                <strong><?php echo e($u->name); ?></strong>
                                <small class="d-block text-muted"><?php echo e($u->email); ?></small>
                                <small class="text-muted">ID: <?php echo e($u->id); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($u->has_paid_package !== 'no' ? 'success' : 'secondary'); ?>">
                                    <?php echo e($u->has_paid_package !== 'no' ? 'Active' : 'No Package'); ?>

                                </span>
                            </td>
                            <td><?php echo e($u->total_referrals); ?></td>
                            <td>
                                <span class="font-weight-bold text-success"><?php echo e($u->active_referrals); ?></span>
                                <small class="text-muted">/ <?php echo e($u->total_referrals); ?></small>
                            </td>
                            <td>
                                <span class="font-weight-bold <?php echo e($u->commission_balance > 0 ? 'text-warning' : 'text-muted'); ?>">
                                    $<?php echo e(number_format($u->commission_balance, 2)); ?>

                                </span>
                            </td>
                            <td><small><?php echo e($u->created_at->format('d M Y')); ?></small></td>
                            <td>
                                <a href="<?php echo e(route('admin.referral-bonus-detail', $u->id)); ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral-bonuses.blade.php ENDPATH**/ ?>
<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <div class="mb-3">
        <a href="<?php echo e(route('admin.referral-bonuses')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to All Users
        </a>
    </div>

    <header class="bg-blue-50 py-6 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            <i class="fas fa-user mr-2"></i> <?php echo e($user->name); ?> — Referral Bonus Detail
        </h1>
        <p class="text-gray-500 mt-1 text-sm"><?php echo e($user->email); ?> &bull; Joined <?php echo e($user->created_at->format('d M Y')); ?></p>
    </header>

    
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                <h4 class="font-weight-bold">$<?php echo e(number_format($commissionBalance, 2)); ?></h4>
                <p class="text-muted small mb-0">Commission Balance</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                <h4 class="font-weight-bold"><?php echo e($directReferrals->count()); ?></h4>
                <p class="text-muted small mb-0">Direct Referrals</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-users fa-2x text-info mb-2"></i>
                <h4 class="font-weight-bold"><?php echo e($indirectReferrals->count()); ?></h4>
                <p class="text-muted small mb-0">Indirect Referrals</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-receipt fa-2x text-secondary mb-2"></i>
                <h4 class="font-weight-bold"><?php echo e($transactions->count()); ?></h4>
                <p class="text-muted small mb-0">Bonus Transactions</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white font-weight-bold">
                    <i class="fas fa-user-check mr-1"></i> Direct Referrals (10% bonus)
                    <span class="float-right">$<?php echo e(number_format($directBonusTotal, 2)); ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if($directReferrals->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">None.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Name</th><th>Email</th><th>Invested</th><th>Bonus</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $directReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($r->name); ?></strong></td>
                                    <td><small><?php echo e($r->email); ?></small></td>
                                    <td>$<?php echo e(number_format($r->total_invested, 2)); ?></td>
                                    <td class="text-success font-weight-bold">$<?php echo e(number_format($r->bonus_earned, 2)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($r->is_active ? 'success' : 'secondary'); ?>">
                                            <?php echo e($r->is_active ? 'Active' : 'No Investment'); ?>

                                        </span>
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

        
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white font-weight-bold">
                    <i class="fas fa-users mr-1"></i> Indirect Referrals (1% bonus)
                    <span class="float-right">$<?php echo e(number_format($indirectBonusTotal, 2)); ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if($indirectReferrals->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">None.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Name</th><th>Via</th><th>Invested</th><th>Bonus</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $indirectReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($r->name); ?></strong></td>
                                    <td><small class="text-muted"><?php echo e($r->referred_through); ?></small></td>
                                    <td>$<?php echo e(number_format($r->total_invested, 2)); ?></td>
                                    <td class="text-info font-weight-bold">$<?php echo e(number_format($r->bonus_earned, 2)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($r->is_active ? 'success' : 'secondary'); ?>">
                                            <?php echo e($r->is_active ? 'Active' : 'No Investment'); ?>

                                        </span>
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
    </div>

    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-history mr-1"></i> Commission Transaction History
        </div>
        <div class="card-body p-0">
            <?php if($transactions->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No commission transactions recorded.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Reference</th><th>From User</th><th>Description</th><th>Amount</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><small class="text-muted"><?php echo e($t->transaction_no); ?></small></td>
                            <td><?php echo e($t->from_user); ?></td>
                            <td><small><?php echo e($t->parsed_description); ?></small></td>
                            <td class="font-weight-bold text-success">$<?php echo e(number_format($t->parsed_amount, 2)); ?></td>
                            <td><small><?php echo e($t->created_at->format('d M Y H:i')); ?></small></td>
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

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/referral-bonus-detail.blade.php ENDPATH**/ ?>
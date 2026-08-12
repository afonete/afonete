<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content-wrapper">
    <div class="w-full p-4">

        
        <div class="mb-3 mx-2">
            <ul class="flex flex-col md:flex-row md:space-x-5">
                <li><a href="<?php echo e(route('overview')); ?>" class="font-medium text-lg hover:text-orange-600">Overview</a></li>
                <li><a href="<?php echo e(route('commission')); ?>" class="font-medium text-lg text-blue-900 border-b-2 border-blue-900">Referral Bonuses</a></li>
                <li><a href="<?php echo e(route('transaction')); ?>" class="font-medium text-lg hover:text-orange-600">Transactions</a></li>
                <li><a href="<?php echo e(route('subscription')); ?>" class="font-medium text-lg hover:text-orange-600">My Subscriptions</a></li>
            </ul>
        </div>

        
        <div class="row px-2 mb-3">
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                    <h4 class="font-weight-bold">$<?php echo e(number_format($totalCommission, 2)); ?></h4>
                    <p class="text-muted small mb-0">Total Commission Balance</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                    <h4 class="font-weight-bold"><?php echo e($totalDirectCount); ?></h4>
                    <p class="text-muted small mb-0">Direct Referrals</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                    <h4 class="font-weight-bold"><?php echo e($activeDirectCount); ?></h4>
                    <p class="text-muted small mb-0">Active Investors</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-clock fa-2x text-secondary mb-2"></i>
                    <h4 class="font-weight-bold">$<?php echo e(number_format($previousWeekTotal, 2)); ?></h4>
                    <p class="text-muted small mb-0">Previous Week</p>
                </div>
            </div>
        </div>

        
        <div class="row px-2 mb-4">
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white font-weight-bold">
                        <i class="fas fa-user-check mr-1"></i> Direct Referral Bonus (10%)
                        <span class="float-right badge badge-light text-success">$<?php echo e(number_format($directBonusTotal, 2)); ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if($directReferrals->isEmpty()): ?>
                            <p class="text-muted p-3 mb-0">No direct referrals yet.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Invested</th>
                                        <th>Bonus (10%)</th>
                                        <th>Status</th>
                                        <th>Since</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $directReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($ref->name); ?></strong>
                                            <small class="d-block text-muted"><?php echo e($ref->email); ?></small>
                                        </td>
                                        <td>$<?php echo e(number_format($ref->total_invested, 2)); ?></td>
                                        <td class="font-weight-bold text-success">
                                            $<?php echo e(number_format($ref->bonus_earned, 2)); ?>

                                        </td>
                                        <td>
                                            <?php if($ref->is_active): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">No Investment</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small><?php echo e($ref->created_at->format('d M Y')); ?></small></td>
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
                        <i class="fas fa-users mr-1"></i> Indirect Referral Bonus (1%)
                        <span class="float-right badge badge-light text-info">$<?php echo e(number_format($indirectBonusTotal, 2)); ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if($indirectReferrals->isEmpty()): ?>
                            <p class="text-muted p-3 mb-0">No indirect referrals yet.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Via</th>
                                        <th>Invested</th>
                                        <th>Bonus (1%)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $indirectReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong><?php echo e($ref->name); ?></strong></td>
                                        <td><small class="text-muted"><?php echo e($ref->referred_through); ?></small></td>
                                        <td>$<?php echo e(number_format($ref->total_invested, 2)); ?></td>
                                        <td class="font-weight-bold text-info">
                                            $<?php echo e(number_format($ref->bonus_earned, 2)); ?>

                                        </td>
                                        <td>
                                            <?php if($ref->is_active): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">No Investment</span>
                                            <?php endif; ?>
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

        
        <div class="px-2 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-history mr-1"></i> Commission Transaction History
                </div>
                <div class="card-body p-0">
                    <?php if($commissionTrx->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">No commission transactions yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Reference</th>
                                    <th>From</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $commissionTrx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

    </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/commission.blade.php ENDPATH**/ ?>
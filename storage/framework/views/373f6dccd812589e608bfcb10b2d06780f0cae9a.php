<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <?php echo $__env->make('user.referral._nav', ['active' => 'bonus'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?><div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2"><?php echo e(session('error')); ?></div><?php endif; ?>

    
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Total Referral Bonus</small>
                    <div class="font-weight-bold" style="font-size:1.6rem;">$<?php echo e(number_format($totals['total'], 2)); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Withdrawable Now</small>
                    <div class="font-weight-bold text-success" style="font-size:1.6rem;">$<?php echo e(number_format($totals['withdrawable'], 2)); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Pending This Week</small>
                    <div class="font-weight-bold text-warning" style="font-size:1.6rem;">$<?php echo e(number_format($totals['pending'], 2)); ?></div>
                    <small class="text-muted">Withdrawable next <?php echo e($nextMonday->format('D d M')); ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Already Withdrawn</small>
                    <div class="font-weight-bold text-info" style="font-size:1.6rem;">$<?php echo e(number_format($totals['lifetime_withdrawn'], 2)); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-1"><i class="fas fa-calendar-week mr-2 text-primary"></i>Weekly Withdrawal</h5>
                    <small class="text-muted">
                        <?php if($isMonday): ?>
                            ✅ <span class="text-success font-weight-bold">Today is Monday</span> — you can withdraw now.
                        <?php else: ?>
                            Next withdrawal window opens on <strong><?php echo e($nextMonday->format('l, d M Y')); ?></strong>.
                        <?php endif; ?>
                    </small>
                </div>
                <div>
                    <?php if($isMonday && $totals['withdrawable'] > 0 && !$alreadyReq): ?>
                        <form method="POST" action="<?php echo e(route('user.referral.withdraw')); ?>" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-lg font-weight-bold"
                                    onclick="return confirm('Submit withdrawal of $<?php echo e(number_format($totals['withdrawable'], 2)); ?> for this Monday?')">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                Withdraw $<?php echo e(number_format($totals['withdrawable'], 2)); ?>

                            </button>
                        </form>
                    <?php elseif($alreadyReq): ?>
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-hourglass-half mr-1"></i> Already requested this Monday
                        </button>
                    <?php elseif(!$isMonday): ?>
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-lock mr-1"></i> Available on Monday only
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-ban mr-1"></i> Nothing to withdraw
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Bonus History</div>
        <div class="card-body p-0">
            <?php if($rows->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No referral bonuses yet. Invite people to start earning!</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Source</th>
                            <th>From</th>
                            <th>Level</th>
                            <th>Pkg Amt</th>
                            <th>Bonus</th>
                            <th>Withdrawable On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusClass = match($r->status) {
                                'withdrawn'   => 'success',
                                'withdrawable'=> 'info',
                                'pending'     => 'warning',
                                'reversed'    => 'danger',
                                default       => 'secondary',
                            };
                        ?>
                        <tr>
                            <td><?php echo $r->sourceLabel(); ?></td>
                            <td><?php echo e($r->sourceUser->name ?? '—'); ?> <small class="text-muted">(<?php echo e($r->sourceUser->email ?? '—'); ?>)</small></td>
                            <td>
                                <?php if($r->level > 0): ?>
                                    <span class="badge badge-primary">L<?php echo e($r->level); ?> · <?php echo e(rtrim(rtrim(number_format($r->percentage, 2), '0'), '.')); ?>%</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">—</span>
                                <?php endif; ?>
                            </td>
                            <td>$<?php echo e(number_format($r->source_amount, 2)); ?></td>
                            <td class="font-weight-bold text-success">$<?php echo e(number_format($r->bonus_amount, 4)); ?></td>
                            <td><small><?php echo e($r->week_start->format('d M Y')); ?></small></td>
                            <td><span class="badge badge-<?php echo e($statusClass); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($rows->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/referral/bonus.blade.php ENDPATH**/ ?>
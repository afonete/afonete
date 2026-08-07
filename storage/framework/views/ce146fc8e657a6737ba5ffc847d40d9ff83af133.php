<div class="wrapper">
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('user.investments')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back to My Investments
    </a>

    <h3 class="font-weight-bold">
        <i class="fas fa-box text-primary mr-2"></i>
        <?php echo e($payment->package); ?>

        <span class="badge badge-<?php echo e($payment->category === 'VENTURE' ? 'primary' : 'info'); ?> ml-2">
            <?php echo e(strtoupper($payment->category ?? '—')); ?>

        </span>
    </h3>

    
    <?php
        $expired = $payment->is_expired;
        $paidOk  = (int) $payment->status === 1;
        $bannerClass = $expired ? 'secondary' : ($paidOk ? 'success' : 'warning');
        $bannerText  = $expired ? 'EXPIRED — Locked tokens released to Available Token' :
                       ($paidOk ? 'ACTIVE — Earning daily income' : 'PENDING PAYMENT');
    ?>
    <div class="alert alert-<?php echo e($bannerClass); ?> font-weight-bold">
        <i class="fas fa-info-circle mr-2"></i> <?php echo e($bannerText); ?>

    </div>

    
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Amount</small>
                <div class="font-weight-bold text-primary" style="font-size:1.4rem;">$<?php echo e(number_format($payment->amount, 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Paid</small>
                <div class="font-weight-bold text-success" style="font-size:1.4rem;">$<?php echo e(number_format($payment->paid ?? 0, 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Overpaid</small>
                <div class="font-weight-bold text-info" style="font-size:1.4rem;">$<?php echo e(number_format($payment->over_paid ?? 0, 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Duration</small>
                <div class="font-weight-bold text-secondary" style="font-size:1.4rem;"><?php echo e($payment->duration ?? '—'); ?> days</div>
            </div></div>
        </div>
    </div>

    
    <div class="row mt-2">
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-lock text-secondary mr-1"></i> Locked Tokens Granted</h6>
                <div class="font-weight-bold text-secondary" style="font-size:1.3rem;">
                    <?php echo e(number_format($lockedTokens, 0)); ?> <?php echo e(\App\Models\TokenSetting::currentSymbol()); ?>

                </div>
                <small class="text-muted">$<?php echo e(number_format($payment->amount, 0)); ?> ÷ $<?php echo e(number_format($uvpPrice, 4)); ?> (UVP price)</small>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-calendar text-info mr-1"></i> Dates</h6>
                <small class="d-block"><strong>Bought:</strong> <?php echo e(\Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i')); ?></small>
                <small class="d-block"><strong>Expires:</strong> <?php echo e($payment->expiration_date ? \Carbon\Carbon::parse($payment->expiration_date)->format('d M Y') : '—'); ?></small>
                <small class="d-block"><strong>Days elapsed:</strong> <?php echo e($daysElapsed); ?></small>
                <?php if(!is_null($daysRemaining)): ?>
                    <small class="d-block"><strong>Days remaining:</strong> <?php echo e($daysRemaining); ?></small>
                <?php endif; ?>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0"><div class="card-body">
                <h6 class="font-weight-bold mb-2"><i class="fas fa-sync text-warning mr-1"></i> Renewals</h6>
                <div class="font-weight-bold text-warning" style="font-size:1.3rem;"><?php echo e($renewalsCount); ?> / <?php echo e($maxRenewals); ?></div>
                <small class="text-muted">Every 30 days · renewal_price = $<?php echo e(number_format($renewalPrice, 4)); ?></small>
                <?php if($payment->status == 1 && !$expired && $renewalsCount < $maxRenewals): ?>
                    <div class="mt-2">
                        <a href="<?php echo e(route('packageRenew', $payment->id)); ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-redo mr-1"></i> Renew Now
                        </a>
                    </div>
                <?php endif; ?>
            </div></div>
        </div>
    </div>

    
    <?php if($package): ?>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Package Configuration</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><small class="text-muted">Name:</small> <strong><?php echo e($package->name ?? '—'); ?></strong></div>
                <div class="col-md-3"><small class="text-muted">Plan:</small> <?php echo e($package->plan ?? '—'); ?></div>
                <?php if(property_exists($package, 'min_amount')): ?>
                <div class="col-md-3"><small class="text-muted">Min amount:</small> $<?php echo e(number_format($package->min_amount, 0)); ?></div>
                <div class="col-md-3"><small class="text-muted">Max amount:</small> $<?php echo e(number_format($package->max_amount, 0)); ?></div>
                <?php endif; ?>
                <?php if(property_exists($package, 'percentage')): ?>
                <div class="col-md-3"><small class="text-muted">Daily %:</small> <?php echo e($package->percentage); ?>%</div>
                <?php endif; ?>
                <?php if(property_exists($package, 'total_return')): ?>
                <div class="col-md-3"><small class="text-muted">Total return:</small> <?php echo e($package->total_return); ?></div>
                <?php endif; ?>
                <?php if(property_exists($package, 'current_price')): ?>
                <div class="col-md-3"><small class="text-muted">Token price at purchase:</small> $<?php echo e(number_format($package->current_price, 4)); ?></div>
                <?php endif; ?>
                <?php if(property_exists($package, 'default_token')): ?>
                <div class="col-md-3"><small class="text-muted">Default tokens:</small> <?php echo e(number_format($package->default_token, 0)); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($renewals->isNotEmpty()): ?>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Renewal History</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Renewed At</th><th>Next Due</th><th>Amount Paid</th><th>Token Price</th><th>Tokens Received</th><th>Status</th><th>Ref</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $renewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($r->renewal_number); ?> / <?php echo e($maxRenewals); ?></td>
                            <td><small><?php echo e(\Carbon\Carbon::parse($r->renewed_at)->format('d M Y')); ?></small></td>
                            <td><small><?php echo e($r->next_renewal_due ? \Carbon\Carbon::parse($r->next_renewal_due)->format('d M Y') : '—'); ?></small></td>
                            <td>$<?php echo e(number_format($r->amount_paid, 2)); ?></td>
                            <td>$<?php echo e(number_format($r->token_price_at_renewal, 4)); ?></td>
                            <td class="font-weight-bold text-success"><?php echo e(number_format($r->tokens_received, 4)); ?> <?php echo e(\App\Models\TokenSetting::currentSymbol()); ?></td>
                            <td><span class="badge badge-<?php echo e($r->status === 'completed' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
                            <td><small><?php echo e($r->transaction_no); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($dailyIncomes->isNotEmpty()): ?>
    <?php
        $runningSum = 0.0;
        $dailyIncomesAsc = $dailyIncomes->reverse();
        $cumulativeList = [];
        foreach ($dailyIncomesAsc as $d) {
            $runningSum += (float) $d->amount;
            $cumulativeList[$d->id] = $runningSum;
        }
    ?>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-chart-line mr-1"></i> Daily Income History</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Date</th><th>Income</th><th>Cumulative</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dailyIncomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><small><?php echo e(\Carbon\Carbon::parse($d->earned_at)->format('d M Y')); ?></small></td>
                            <td class="font-weight-bold text-success">$<?php echo e(number_format($d->amount, 4)); ?></td>
                            <td class="font-weight-bold text-primary">$<?php echo e(number_format($cumulativeList[$d->id] ?? 0, 2)); ?></td>
                            <td><small class="text-muted"><?php echo e($d->notes ?? '—'); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($transactions->isNotEmpty()): ?>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-exchange-alt mr-1"></i> Related Transactions</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>Date</th><th>Type</th><th>Ref</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $details = json_decode($t->transaction_details, true) ?: []; ?>
                            <tr>
                                <td><small><?php echo e($t->created_at->format('d M Y H:i')); ?></small></td>
                                <td><?php echo e($t->transaction_type); ?></td>
                                <td><small><?php echo e($t->transaction_no); ?></small></td>
                                <td>$<?php echo e(number_format($details['amount'] ?? 0, 4)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/investments/show.blade.php ENDPATH**/ ?>
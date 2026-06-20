<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper">
    <div class="container-fluid py-4">

        
        <div class="content-header mb-3">
            <h1 class="m-0 text-dark">
                <i class="fas fa-sync-alt mr-2 text-info"></i> Renew Package
            </h1>
            <small class="text-muted">
                Renewal <?php echo e($renewalNumber); ?> of <?php echo e($maxRenewals); ?> &mdash;
                Package runs <?php echo e($pkgDuration); ?> days, renewed every 30 days
                <?php if($isPartialFee): ?>
                    &mdash; <strong>Final renewal covers <?php echo e($leftoverDays); ?> leftover days</strong>
                <?php endif; ?>
            </small>
        </div>

        
        <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
            <i class="fas fa-calculator mr-1"></i>
            <strong>How renewal works:</strong>
            Your daily Trading Voucher (75% of daily ROI) is added every day.
            After every 30 days you have enough to renew this package.
            The renewal fee is pro-rated for the final partial window
            (only <?php echo e($leftoverDays); ?> day<?php echo e($leftoverDays > 1 ? 's' : ''); ?> left if applicable).
        </div>

        
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-1"></i> <?php echo e(session('error')); ?>

                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <?php if(session('message')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> <?php echo e(session('message')); ?>

                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        
        <?php if(!$isDue): ?>
            <div class="alert alert-warning">
                <i class="fas fa-clock mr-1"></i>
                Your next renewal is not due yet. It becomes available on
                <strong><?php echo e($renewalDueDate->format('d M Y')); ?></strong>.
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">
                            <i class="fas fa-ticket-alt mr-2"></i>Trading Voucher Renewal Details
                        </h3>
                    </div>

                    <div class="card-body">

                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Package Renewal Progress</small>
                                <small class="text-muted"><?php echo e($renewalNumber - 1); ?> / <?php echo e($maxRenewals); ?> completed</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <?php $progress = (($renewalNumber - 1) / $maxRenewals) * 100; ?>
                                <div class="progress-bar bg-info" style="width: <?php echo e($progress); ?>%"></div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Day 30 &bull; Day 60 &bull; Day 90 &mdash; Package expires at Day <?php echo e($pkgDuration); ?>

                                <?php if($leftoverDays > 0 && $isPartialFee): ?>
                                    &mdash; Final renewal covers Day <?php echo e($pkgDuration - $leftoverDays + 1); ?>–<?php echo e($pkgDuration); ?> (<?php echo e($leftoverDays); ?> days)
                                <?php endif; ?>
                            </small>
                        </div>

                        
                        <?php if(isset($schedule) && count($schedule) > 0): ?>
                        <div class="mb-3">
                            <h6 class="mb-2 text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i> Full Renewal Schedule
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Due</th>
                                            <th class="text-right">Days covered</th>
                                            <th class="text-right">Fee</th>
                                            <th class="text-right">Tokens</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isPast       = $row['renewal_number'] <  $renewalNumber;
                                            $isCurrent    = $row['renewal_number'] == $renewalNumber;
                                            $rowBg        = $isCurrent ? 'table-warning' : ($isPast ? 'table-success' : '');
                                            $statusBadge  = $isPast
                                                ? '<span class="badge badge-success"><i class="fa fa-check"></i> Done</span>'
                                                : ($isCurrent
                                                    ? '<span class="badge badge-warning"><i class="fa fa-arrow-right"></i> Now</span>'
                                                    : '<span class="badge badge-secondary">Upcoming</span>');
                                        ?>
                                        <tr class="<?php echo e($rowBg); ?>">
                                            <td><strong><?php echo e($row['renewal_number']); ?></strong></td>
                                            <td>
                                                <small>Day <?php echo e($row['due_at_day']); ?></small><br>
                                                <small class="text-muted"><?php echo e($row['due_at_date']); ?></small>
                                            </td>
                                            <td class="text-right">
                                                <?php echo e($row['days_covered']); ?>

                                                <?php if($row['is_partial']): ?>
                                                    <br><small class="text-warning">(partial)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">
                                                $<?php echo e(number_format($row['fee'], 2)); ?>

                                            </td>
                                            <td class="text-right">
                                                <?php echo e(number_format($row['tokens'], 0)); ?>

                                            </td>
                                            <td><?php echo $statusBadge; ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i>
                                Each renewal unlocks 30 days of income (or the leftover days for the final one).
                                If you miss a renewal, those days' cashout <strong>and</strong> trading voucher are skipped.
                            </small>
                        </div>
                        <?php endif; ?>

                        <hr>

                        
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-wallet mr-1"></i> Your Trading Voucher Balance
                                    </td>
                                    <td class="text-right font-weight-bold
                                        <?php echo e($tradingVoucherBalance >= $renewalFee ? 'text-success' : 'text-danger'); ?>">
                                        $<?php echo e(number_format($tradingVoucherBalance, 2)); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-minus-circle mr-1 text-danger"></i> Renewal Fee
                                        <small class="d-block text-muted">
                                            <?php if($isPartialFee): ?>
                                                Pro-rated for <?php echo e($daysCovered); ?> days &divide; 30
                                                (monthly: $<?php echo e(number_format($monthlyFee, 2)); ?>)
                                            <?php else: ?>
                                                Standard monthly fee (30 days)
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        &minus; $<?php echo e(number_format($renewalFee, 2)); ?>

                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-coins mr-1 text-warning"></i> New Token Price
                                        <small class="d-block text-muted">(set by admin)</small>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        $<?php echo e(number_format($tokenPrice, 4)); ?> / token
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-plus-circle mr-1 text-success"></i> Tokens You Will Receive
                                        <small class="d-block text-muted">= Fee &divide; Token Price</small>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-success px-3 py-2" style="font-size: 1rem;">
                                            <?php echo e(number_format($tokensToReceive, 4)); ?> tokens
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-calendar-alt mr-1"></i> Renewal Cycle
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        #<?php echo e($renewalNumber); ?> of <?php echo e($maxRenewals); ?>

                                    </td>
                                </tr>
                                <?php if($renewalNumber < $maxRenewals): ?>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-forward mr-1"></i> Next Renewal Due
                                    </td>
                                    <td class="text-right text-muted">
                                        ~<?php echo e(\Carbon\Carbon::now()->addDays(30)->format('d M Y')); ?>

                                    </td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        <i class="fas fa-flag-checkered mr-1"></i>
                                        <?php if($isPartialFee): ?>
                                            This is your <strong>final renewal</strong> for this package cycle
                                            &mdash; covers <?php echo e($leftoverDays); ?> leftover days.
                                        <?php else: ?>
                                            This is your <strong>final renewal</strong> for this package cycle.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                    </div>

                    
                    <?php if($tradingVoucherBalance < $renewalFee): ?>
                        <div class="card-footer bg-danger text-white text-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Insufficient Trading Voucher balance. You need
                            <strong>$<?php echo e(number_format($renewalFee, 2)); ?></strong>
                            but only have
                            <strong>$<?php echo e(number_format($tradingVoucherBalance, 2)); ?></strong>.
                        </div>
                    <?php else: ?>
                        <div class="card-footer p-0">
                            <?php if($isDue): ?>
                                
                                <button type="button"
                                        class="btn btn-info btn-block py-3"
                                        style="font-size: 1.1rem; border-radius: 0;"
                                        data-toggle="modal"
                                        data-target="#confirmRenewModal">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Buy &mdash; Renew Package Now
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-block py-3" disabled
                                        style="font-size: 1rem; border-radius: 0;">
                                    <i class="fas fa-clock mr-2"></i>
                                    Available on <?php echo e($renewalDueDate->format('d M Y')); ?>

                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="text-center mt-2">
                    <a href="<?php echo e(route('user.dashboard')); ?>" class="text-muted">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmRenewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sync-alt mr-2"></i> Confirm Package Renewal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to renew your package using your <strong>Trading Voucher</strong> balance.</p>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Amount to deduct:</td>
                        <td class="font-weight-bold text-danger">$<?php echo e(number_format($renewalFee, 2)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Token price:</td>
                        <td class="font-weight-bold">$<?php echo e(number_format($tokenPrice, 4)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tokens you receive:</td>
                        <td class="font-weight-bold text-success"><?php echo e(number_format($tokensToReceive, 4)); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Renewal cycle:</td>
                        <td class="font-weight-bold">#<?php echo e($renewalNumber); ?> of <?php echo e($maxRenewals); ?></td>
                    </tr>
                </table>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle mr-1"></i>
                    Your package expiration will be extended by <strong>30 days</strong>.
                    This action <strong>cannot be undone</strong>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
                <form action="<?php echo e(route('packageRenewPay')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-check mr-1"></i> Confirm &amp; Pay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/adminlte.min.js')); ?>"></script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/package-renew.blade.php ENDPATH**/ ?>
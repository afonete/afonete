<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper">
    <div class="container-fluid py-4">

        
        <div class="content-header mb-3">
            <h1 class="m-0 text-dark">
                <i class="fas fa-sync-alt mr-2 text-info"></i> Renew Package
            </h1>
            <small class="text-muted">Renewal <?php echo e($renewalNumber); ?> of <?php echo e($maxRenewals); ?> &mdash; Package runs 100 days, renewed every 30 days</small>
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
                                Day 30 &bull; Day 60 &bull; Day 90 &mdash; Package expires at Day 100
                            </small>
                        </div>

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
                                        <i class="fas fa-minus-circle mr-1 text-danger"></i> Renewal Fee (Amount)
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
                                        This is your <strong>final renewal</strong> for this package cycle.
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
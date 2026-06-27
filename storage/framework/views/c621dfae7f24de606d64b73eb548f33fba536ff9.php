<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <?php echo $__env->make('user.token._nav', ['active' => 'withdraw'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?><div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2"><?php echo e(session('error')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-danger mt-2"><?php echo e($errors->first()); ?></div><?php endif; ?>

    

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white font-weight-bold">
                    <i class="fas fa-wallet mr-1"></i> Withdraw <?php echo e($symbol); ?> from Free Token
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-3">
                        <tr><td class="text-muted">Free Token Balance:</td><td class="font-weight-bold"><?php echo e(number_format($freeBal, 0)); ?> <?php echo e($symbol); ?></td></tr>
                        <tr><td class="text-muted">Coin value:</td><td>1 <?php echo e($symbol); ?> = $<?php echo e(number_format($coinValue, 4)); ?></td></tr>
                    </table>

                    <form method="POST" action="<?php echo e(route('user.token.withdraw.post')); ?>" class="js-transaction-password-form">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="font-weight-bold">FONE Wallet Address <span class="text-danger">*</span></label>
                            <input type="text" name="wallet_address" class="form-control"
                                   placeholder="Your FOCOIN/FONE wallet address" required>
                            <small class="text-muted">Admin will send tokens to this address externally after approval.</small>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Token Amount <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control"
                                   min="1" step="1" max="<?php echo e($freeBal); ?>" required id="wdTokens">
                        </div>
                        <div class="p-2 mb-2 bg-light rounded text-center">
                            <small class="text-muted">Equivalent value: </small>
                            <span class="font-weight-bold text-success" id="wdValue">$0.00</span>
                        </div>
                        <div class="alert alert-info small py-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Tokens are held from your balance on submission. Admin approves and sends externally. If rejected, tokens are returned.
                        </div>
                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <button type="submit" class="btn btn-danger btn-block font-weight-bold"
                                <?php echo e($freeBal < 1 ? 'disabled' : ''); ?>>
                            <i class="fas fa-paper-plane mr-1"></i> Submit Withdrawal Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7 mt-3 mt-md-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Withdrawal History</div>
                <div class="card-body p-0">
                    <?php if($history->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">No withdrawal requests yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Ref</th><th>Tokens</th><th>Wallet</th><th>Status</th><th>Note</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><small class="text-muted"><?php echo e($tw->transaction_no); ?></small></td>
                                    <td class="font-weight-bold"><?php echo e(number_format($tw->token_amount, 0)); ?></td>
                                    <td><small><?php echo e(Str::limit($tw->wallet_address, 16)); ?></small></td>
                                    <td>
                                        <?php $bc = ['pending'=>'warning','approved'=>'success','rejected'=>'danger']; ?>
                                        <span class="badge badge-<?php echo e($bc[$tw->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($tw->status)); ?></span>
                                    </td>
                                    <td><small class="text-muted"><?php echo e($tw->admin_note ?? '—'); ?></small></td>
                                    <td><small><?php echo e($tw->created_at->format('d M Y')); ?></small></td>
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
<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
document.getElementById('wdTokens').addEventListener('input', function () {
    const v = (parseFloat(this.value) || 0) * <?php echo e($coinValue); ?>;
    document.getElementById('wdValue').textContent = '$' + v.toFixed(2);
});
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/withdraw.blade.php ENDPATH**/ ?>
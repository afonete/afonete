<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <?php echo $__env->make('user.token._nav', ['active' => 'swap'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?><div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2"><?php echo e(session('error')); ?></div><?php endif; ?>

    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-sync-alt mr-1"></i> Swap <?php echo e($symbol); ?> → Cashout
                </div>
                <div class="card-body">

                    <table class="table table-borderless table-sm mb-3">
                        <tr>
                            <td class="text-muted">Free Token Balance:</td>
                            <td class="font-weight-bold"><?php echo e(number_format($freeBal, 0)); ?> <?php echo e($symbol); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Example:</td>
                            <td class="text-success">200,000 <?php echo e($symbol); ?> → $<?php echo e(number_format(200000 * $swapPrice, 2)); ?></td>
                        </tr>
                    </table>

                    <form method="POST" action="<?php echo e(route('user.token.swap.post')); ?>" class="js-transaction-password-form">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Token Amount to Swap <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" id="swapInput"
                                   class="form-control form-control-lg" min="1" step="1"
                                   max="<?php echo e($freeBal); ?>" required placeholder="Enter token amount">
                        </div>

                        <div class="p-3 mb-3 text-center" style="background:#f8f9fa; border-radius:6px; border:1px solid #dee2e6;">
                            <span class="text-muted">You will receive in Cashout:</span>
                            <div class="font-weight-bold text-success" style="font-size:1.5rem;" id="swapResult">$0.00</div>
                            <small class="text-muted">Cashout is withdrawable anytime (min <strong>$10</strong> per spec)</small>
                        </div>

                        <div class="alert alert-warning small py-2 d-none" id="minCashoutWarn">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            After swap, your Cashout balance must reach at least <strong>$10</strong> before you can withdraw it.
                        </div>

                        <div class="alert alert-warning small py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Swap is <strong>immediate and irreversible</strong>. Tokens become Cashout.
                        </div>

                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2"
                                <?php echo e($freeBal < 1 ? 'disabled' : ''); ?>>
                            <i class="fas fa-sync-alt mr-1"></i> Swap Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
document.getElementById('swapInput').addEventListener('input', function () {
    // Use swap_price (per spec) for the conversion, not coin_value
    const v = (parseFloat(this.value) || 0) * <?php echo e($swapPrice); ?>;
    document.getElementById('swapResult').textContent = '$' + v.toFixed(2);
    // Warn if total cashout would be below the $10 withdrawable minimum (per spec)
    const warnBox = document.getElementById('minCashoutWarn');
    if (v > 0 && v < 10) {
        warnBox.classList.remove('d-none');
    } else {
        warnBox.classList.add('d-none');
    }
});
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/swap.blade.php ENDPATH**/ ?>
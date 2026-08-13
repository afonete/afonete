<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="font-weight-bold mb-0"><i class="fas fa-check-circle text-success mr-2"></i> Available Token Wallet</h3>
        <a href="<?php echo e(route('user.investments')); ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-box-open mr-1"></i> My Investments
        </a>
    </div>

    <?php echo $__env->make('user.token._nav', ['active' => 'available'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?><div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2"><?php echo e(session('error')); ?></div><?php endif; ?>

    <?php
        $y1 = \App\Models\FomTokenStaking::getYieldPercent(1);
        $y2 = \App\Models\FomTokenStaking::getYieldPercent(2);
        $y3 = \App\Models\FomTokenStaking::getYieldPercent(3);
        $y4 = \App\Models\FomTokenStaking::getYieldPercent(4);
        $y5 = \App\Models\FomTokenStaking::getYieldPercent(5);
    ?>

    <div class="row mt-3">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white font-weight-bold">
                    <i class="fas fa-exchange-alt mr-1"></i> Move to Free Token Wallet
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6 border-right">
                            <div class="text-muted small">Available Balance</div>
                            <div class="font-weight-bold text-success" style="font-size:1.3rem;"><?php echo e(number_format($availableBal, 0)); ?></div>
                            <div class="text-muted small"><?php echo e($symbol); ?></div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Free Token Balance</div>
                            <div class="font-weight-bold" style="font-size:1.3rem;"><?php echo e(number_format($freeBal, 0)); ?></div>
                            <div class="text-muted small"><?php echo e($symbol); ?></div>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Move Available Tokens to <strong>Free Token Wallet</strong> to use them for internal transfers, token swaps, or withdrawals.
                    </div>

                    <form method="POST" action="<?php echo e(route('user.token.available-to-free')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Amount to Move <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control"
                                   min="1" step="1" max="<?php echo e($availableBal); ?>" required
                                   placeholder="Number of tokens">
                        </div>
                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2 mt-2"
                                <?php echo e($availableBal < 1 ? 'disabled' : ''); ?>>
                            <i class="fas fa-arrow-right mr-1"></i>
                            Move to Free Token Wallet
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-lock mr-1"></i> Available Token &rarr; Escrow Staking (1–5 Years)
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small mb-3">
                        <i class="fas fa-chart-line mr-1"></i>
                        Transfer Available Tokens back to <strong>Escrow Wallet (Locked Tokens)</strong>. Choose a lock period of 1 to 5 years to earn bonus profit. Principal + Profit tokens will be released back to Available Tokens at the end of the lock period!
                    </div>

                    <form method="POST" action="<?php echo e(route('user.token.available-to-escrow-staking')); ?>" class="js-transaction-password-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <div class="form-group mb-2">
                            <label class="font-weight-bold small">Amount to Stake <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="stakeAmount" class="form-control"
                                   min="1" step="1" max="<?php echo e($availableBal); ?>" required
                                   placeholder="e.g. 10000">
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Choose Lock Duration &amp; Profit Yield <span class="text-danger">*</span></label>
                            <select name="years" id="stakeYears" class="form-control font-weight-bold" required>
                                <option value="1" data-yield="<?php echo e($y1); ?>">1 Year (+<?php echo e($y1); ?>% Profit)</option>
                                <option value="2" data-yield="<?php echo e($y2); ?>">2 Years (+<?php echo e($y2); ?>% Profit)</option>
                                <option value="3" data-yield="<?php echo e($y3); ?>">3 Years (+<?php echo e($y3); ?>% Profit)</option>
                                <option value="4" data-yield="<?php echo e($y4); ?>">4 Years (+<?php echo e($y4); ?>% Profit)</option>
                                <option value="5" data-yield="<?php echo e($y5); ?>">5 Years (+<?php echo e($y5); ?>% Profit)</option>
                            </select>
                        </div>

                        
                        <div class="p-2.5 bg-light rounded border mb-3 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Principal Staked:</span>
                                <span class="font-weight-bold" id="prevPrincipal">0 <?php echo e($symbol); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Bonus Profit:</span>
                                <span class="font-weight-bold text-success" id="prevProfit">+0 <?php echo e($symbol); ?></span>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-1 font-weight-bold">
                                <span>Total Released to Available Tokens:</span>
                                <span class="text-primary" id="prevTotal">0 <?php echo e($symbol); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-block text-dark font-weight-bold py-2"
                                <?php echo e($availableBal < 1 ? 'disabled' : ''); ?>>
                            <i class="fas fa-lock mr-1"></i>
                            Lock Tokens in Escrow Staking
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-12 mt-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Available Token Transfer History</div>
                <div class="card-body p-0">
                    <?php if($history->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">No transfers yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Tokens</th><th>Ref</th><th>Date</th></tr></thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="font-weight-bold text-success"><?php echo e(number_format($t->tok_amt, 0)); ?> <?php echo e($symbol); ?></td>
                                    <td><small class="text-muted"><?php echo e($t->transaction_no); ?></small></td>
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

<?php echo $__env->make('user.components.transaction-password-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var stakeAmountInput = document.getElementById("stakeAmount");
        var stakeYearsSelect = document.getElementById("stakeYears");
        var prevPrincipal    = document.getElementById("prevPrincipal");
        var prevProfit       = document.getElementById("prevProfit");
        var prevTotal        = document.getElementById("prevTotal");
        var symbol           = "<?php echo e($symbol); ?>";

        function updatePreview() {
            var amount = parseFloat(stakeAmountInput.value) || 0;
            var opt = stakeYearsSelect.options[stakeYearsSelect.selectedIndex];
            var yieldPct = parseFloat(opt.getAttribute("data-yield")) || 0;

            var profit = amount * (yieldPct / 100.0);
            var total  = amount + profit;

            prevPrincipal.innerText = amount.toLocaleString('en-US') + " " + symbol;
            prevProfit.innerText    = "+" + profit.toLocaleString('en-US') + " " + symbol + " (" + yieldPct + "%)";
            prevTotal.innerText     = total.toLocaleString('en-US') + " " + symbol;
        }

        if (stakeAmountInput && stakeYearsSelect) {
            stakeAmountInput.addEventListener("input", updatePreview);
            stakeYearsSelect.addEventListener("change", updatePreview);
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/available.blade.php ENDPATH**/ ?>
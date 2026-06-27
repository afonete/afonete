<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    
    <?php echo $__env->make('user.token._nav', ['active' => 'transfer'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?><div class="alert alert-success mt-2"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2"><?php echo e(session('error')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-danger mt-2"><?php echo e($errors->first()); ?></div><?php endif; ?>

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fas fa-paper-plane mr-1"></i> Transfer <?php echo e($symbol); ?> Tokens to Another User
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 p-2 bg-light rounded">
                        <span class="text-muted">Your Free Token balance:</span>
                        <span class="font-weight-bold"><?php echo e(number_format($freeBal, 0)); ?> <?php echo e($symbol); ?></span>
                    </div>

                    <form method="POST" action="<?php echo e(route('user.token.transfer.post')); ?>" id="transferForm" class="js-transaction-password-form">
                        <?php echo csrf_field(); ?>

                        
                        <div class="form-group">
                            <label class="font-weight-bold">Recipient Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="email" name="recipient_email" id="recipientEmail"
                                       class="form-control" placeholder="recipient@email.com"
                                       value="<?php echo e(old('recipient_email')); ?>" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="lookupBtn">
                                        <i class="fas fa-search"></i> Find
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        <div id="recipientPreview" class="alert alert-success py-2 d-none">
                            <i class="fas fa-user-check mr-1"></i>
                            Sending to: <strong id="recipientName"></strong>
                        </div>
                        <div id="recipientError" class="alert alert-danger py-2 d-none">
                            <i class="fas fa-times-circle mr-1"></i>
                            <span id="recipientErrorMsg"></span>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Token Amount <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control"
                                   min="1" step="1" max="<?php echo e($freeBal); ?>"
                                   placeholder="How many tokens" required>
                        </div>

                        <div class="alert alert-warning small py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Transfers go to the recipient's <strong>Free Token</strong> wallet. Immediate and irreversible.
                        </div>

                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold"
                                id="submitBtn" <?php echo e($freeBal < 1 ? 'disabled' : ''); ?>>
                            <i class="fas fa-paper-plane mr-1"></i> Transfer Tokens
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7 mt-3 mt-md-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light font-weight-bold">
                    <i class="fas fa-history mr-1"></i> Transfer History
                </div>
                <div class="card-body p-0">
                    <?php if($history->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">No transfers yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr><th>To Name</th><th>To Email</th><th>Tokens</th><th>Ref</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($t->to_name); ?></strong></td>
                                    <td><small class="text-muted"><?php echo e($t->to_email); ?></small></td>
                                    <td class="font-weight-bold text-primary"><?php echo e(number_format($t->tok_amt, 0)); ?> <?php echo e($symbol); ?></td>
                                    <td><small class="text-muted"><?php echo e($t->transaction_no); ?></small></td>
                                    <td><small><?php echo e($t->created_at->format('d M Y')); ?></small></td>
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
document.getElementById('lookupBtn').addEventListener('click', function () {
    const email   = document.getElementById('recipientEmail').value.trim();
    const preview = document.getElementById('recipientPreview');
    const errBox  = document.getElementById('recipientError');
    const nameEl  = document.getElementById('recipientName');

    if (!email) { return; }

    preview.classList.add('d-none');
    errBox.classList.add('d-none');

    fetch('<?php echo e(route("user.token.transfer.lookup")); ?>?email=' + encodeURIComponent(email), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.found) {
            nameEl.textContent = data.name + ' (' + data.email + ')';
            preview.classList.remove('d-none');
        } else {
            document.getElementById('recipientErrorMsg').textContent = data.message;
            errBox.classList.remove('d-none');
        }
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/transfer.blade.php ENDPATH**/ ?>
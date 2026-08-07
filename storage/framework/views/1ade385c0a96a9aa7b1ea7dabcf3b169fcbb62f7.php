<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    
    <?php echo $__env->make('user.token._nav', ['active' => 'transfer'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="alert alert-dark d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 mb-3 text-white shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(245, 158, 11, 0.4);">
        <div>
            <span class="text-uppercase small d-block text-warning font-weight-bold" style="letter-spacing: 0.8px;">
                <i class="fas fa-id-card text-warning mr-1"></i> Your 7-Digit Transfer Code
            </span>
            <h3 class="font-weight-bold text-white mb-0 font-mono" id="myTransferCodeText" style="font-size: 1.6rem; letter-spacing: 2px;">
                <?php echo e(Auth::user()->getTransferCode()); ?>

            </h3>
            <small class="text-light opacity-90">Share this 7-digit Transfer Code or your username (&#64;<?php echo e(Auth::user()->user); ?>) with other users to receive token transfers.</small>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-warning font-weight-bold text-dark px-3 py-2" onclick="navigator.clipboard.writeText('<?php echo e(Auth::user()->getTransferCode()); ?>'); alert('Your Transfer Code <?php echo e(Auth::user()->getTransferCode()); ?> was copied!');" style="border-radius: 8px;">
                <i class="fas fa-copy mr-1"></i> Copy Transfer Code
            </button>
        </div>
    </div>

    <?php if(session('success')): ?><div class="alert alert-success mt-2" style="border-radius: 8px;"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger mt-2" style="border-radius: 8px;"><?php echo e(session('error')); ?></div><?php endif; ?>
    <?php if($errors->any()): ?><div class="alert alert-danger mt-2" style="border-radius: 8px;"><?php echo e($errors->first()); ?></div><?php endif; ?>

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-primary text-white font-weight-bold py-3">
                    <i class="fas fa-paper-plane mr-1"></i> Transfer <?php echo e($symbol); ?> Tokens to Another User
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3 p-2.5 bg-light rounded" style="border-radius: 8px;">
                        <span class="text-muted">Your Free Token balance:</span>
                        <span class="font-weight-bold text-primary" style="font-size: 1.05rem;"><?php echo e(number_format($freeBal, 0)); ?> <?php echo e($symbol); ?></span>
                    </div>

                    <form method="POST" action="<?php echo e(route('user.token.transfer.post')); ?>" id="transferForm" class="js-transaction-password-form">
                        <?php echo csrf_field(); ?>

                        
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Recipient Username or 7-Digit Transfer Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="recipient_identifier" id="recipientIdentifier"
                                       class="form-control" placeholder="e.g. johndoe or 8492041"
                                       value="<?php echo e(old('recipient_identifier')); ?>" required style="border-radius: 8px 0 0 8px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary font-weight-bold" id="lookupBtn" style="border-radius: 0 8px 8px 0;">
                                        <i class="fas fa-search mr-1"></i> Find User
                                    </button>
                                </div>
                            </div>
                            <?php $__errorArgs = ['recipient_identifier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger d-block mt-1"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div id="recipientPreview" class="alert alert-success py-2.5 d-none mb-3" style="border-radius: 8px;">
                            <i class="fas fa-user-check mr-1"></i>
                            Sending to: <strong id="recipientName"></strong>
                            <div class="small mt-1">
                                Username: <strong id="recipientUsername"></strong> · Transfer Code: <strong id="recipientTransferCode"></strong>
                            </div>
                        </div>
                        <div id="recipientError" class="alert alert-danger py-2.5 d-none mb-3" style="border-radius: 8px;">
                            <i class="fas fa-times-circle mr-1"></i>
                            <span id="recipientErrorMsg"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Token Amount <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control"
                                   min="1" step="1" max="<?php echo e($freeBal); ?>"
                                   placeholder="Enter token amount to send" required style="border-radius: 8px;">
                        </div>

                        <div class="alert alert-warning small py-2.5 mb-3" style="border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Transfers go to the recipient's <strong>Free Token</strong> wallet. Immediate and irreversible.
                        </div>

                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2.5"
                                id="submitBtn" disabled data-can-transfer="<?php echo e($freeBal >= 1 ? '1' : '0'); ?>" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-1"></i> Transfer Tokens
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7 mt-3 mt-md-0">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-light font-weight-bold py-3">
                    <i class="fas fa-history mr-1"></i> Transfer History
                </div>
                <div class="card-body p-0">
                    <?php if($history->isEmpty()): ?>
                        <p class="text-muted p-3 mb-0">No transfers yet.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr><th>To Name</th><th>Username</th><th>Transfer Code</th><th>Tokens</th><th>Ref</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($t->to_name); ?></strong></td>
                                    <td><small class="text-muted">@ <?php echo e($t->to_username); ?></small></td>
                                    <td><small class="font-mono font-weight-bold text-dark"><?php echo e($t->to_transfer_code); ?></small></td>
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
(function () {
    const lookupBtn        = document.getElementById('lookupBtn');
    const input            = document.getElementById('recipientIdentifier');
    const preview          = document.getElementById('recipientPreview');
    const errBox           = document.getElementById('recipientError');
    const nameEl           = document.getElementById('recipientName');
    const usernameEl       = document.getElementById('recipientUsername');
    const transferCodeEl   = document.getElementById('recipientTransferCode');
    const submitBtn        = document.getElementById('submitBtn');
    const canTransfer      = submitBtn && submitBtn.getAttribute('data-can-transfer') === '1';

    function resetLookup() {
        preview.classList.add('d-none');
        errBox.classList.add('d-none');
        submitBtn.disabled = true;
    }

    lookupBtn.addEventListener('click', function () {
        const identifier = input.value.trim();
        resetLookup();

        if (!identifier) {
            document.getElementById('recipientErrorMsg').textContent = 'Enter recipient username or 7-digit Transfer Code.';
            errBox.classList.remove('d-none');
            return;
        }

        fetch('<?php echo e(route("user.token.transfer.lookup")); ?>?identifier=' + encodeURIComponent(identifier), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) {
                nameEl.textContent = data.name;
                usernameEl.textContent = data.username ? ('@' + data.username) : '—';
                transferCodeEl.textContent = data.transfer_code || '—';
                preview.classList.remove('d-none');
                submitBtn.disabled = !canTransfer;
            } else {
                document.getElementById('recipientErrorMsg').textContent = data.message || 'No user found.';
                errBox.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        })
        .catch(() => {
            document.getElementById('recipientErrorMsg').textContent = 'Lookup failed. Please try again.';
            errBox.classList.remove('d-none');
            submitBtn.disabled = true;
        });
    });

    input.addEventListener('input', resetLookup);
})();
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/token/transfer.blade.php ENDPATH**/ ?>
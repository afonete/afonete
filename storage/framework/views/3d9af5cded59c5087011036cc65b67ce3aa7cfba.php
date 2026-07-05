<?php $__env->startSection('contents'); ?>
<div class="container bg-white min-h-screen py-4 px-3">

    
    <?php if(session('message')): ?>
        <div class="alert alert-success bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="mb-3">
        <a href="<?php echo e(route('admin.payments')); ?>" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
            <i class="fa fa-arrow-left mr-1"></i> Back to Deposit List
        </a>
    </div>

    
    <header class="bg-blue-50 py-[2rem] px-4 rounded mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            Deposit Details
            <span class="ml-2 text-sm font-normal text-gray-500">#<?php echo e($deposit->id); ?></span>
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Review the transaction below before approving, rejecting, or marking it.
        </p>
    </header>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <div class="text-xs uppercase text-gray-500 font-semibold">Amount Deposited</div>
            <div class="text-2xl font-bold text-gray-800 mt-1">
                $<?php echo e(number_format($deposit->amount_deposited, 2)); ?>

            </div>
            <div class="text-xs text-gray-500 mt-1">
                <?php echo e(strtoupper($deposit->currency_type ?? 'USD')); ?>

            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <div class="text-xs uppercase text-gray-500 font-semibold">Current Status</div>
            <?php
                $color = match($deposit->status) {
                    'pending'      => 'bg-yellow-500',
                    'approved'     => 'bg-green-500',
                    'rejected'     => 'bg-purple-700',
                    'cancelled'    => 'bg-red-500',
                    'under-review' => 'bg-blue-500',
                    default        => 'bg-gray-500',
                };
            ?>
            <div class="mt-2">
                <span class="px-3 py-1 inline-block text-xs text-center <?php echo e($color); ?> rounded-2xl text-white font-semibold">
                    <?php echo e(ucfirst($deposit->status)); ?>

                </span>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <div class="text-xs uppercase text-gray-500 font-semibold">Submitted At</div>
            <div class="text-base font-semibold text-gray-800 mt-1">
                <?php echo e($deposit->created_at->format('Y-m-d h:i A')); ?>

            </div>
            <div class="text-xs text-gray-500 mt-1">
                <?php echo e($deposit->created_at->diffForHumans()); ?>

            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
            <div class="text-xs uppercase text-gray-500 font-semibold">15-Min Countdown</div>
            <?php if($deposit->expires_at): ?>
                <div class="text-2xl font-bold text-orange-600 mt-1 deposit-countdown"
                     data-expires="<?php echo e($deposit->expires_at->toIso8601String()); ?>">
                    --:--
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Expires: <?php echo e($deposit->expires_at->format('Y-m-d h:i A')); ?>

                </div>
            <?php else: ?>
                <div class="text-base font-semibold text-gray-500 mt-1">—</div>
                <div class="text-xs text-gray-500 mt-1">No timer for this deposit.</div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white shadow rounded-lg border border-gray-200 mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fa fa-info-circle mr-2 text-blue-500"></i>Transaction Information
            </h2>
        </div>
        <dl class="divide-y divide-gray-200">
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Transaction No.</dt>
                <dd class="md:col-span-2 font-mono text-sm text-gray-800 break-all">
                    <?php echo e($deposit->transaction_id ?: '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Deposit Method</dt>
                <dd class="md:col-span-2 text-sm text-gray-800 uppercase">
                    <?php echo e($deposit->deposit_method ?: '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Network</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">
                    <?php echo e($deposit->network ?: '—'); ?>

                </dd>
            </div>
            <?php if($deposit->payment_context): ?>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Payment Context</dt>
                <dd class="md:col-span-2 text-sm text-gray-800 uppercase">
                    <?php echo e($deposit->payment_context); ?>

                </dd>
            </div>
            <?php endif; ?>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Company Deposit Address / Account</dt>
                <dd class="md:col-span-2 font-mono text-xs text-gray-800 break-all">
                    <?php echo e($deposit->deposit_address ?: '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">User's Wallet Address</dt>
                <dd class="md:col-span-2 font-mono text-xs text-gray-800 break-all">
                    <?php echo e($deposit->user_wallet_address ?: '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Amount Removed (Used)</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">
                    $<?php echo e(number_format($deposit->amount_removed, 2)); ?>

                </dd>
            </div>
            <?php if($deposit->comment): ?>
                <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                    <dt class="text-xs uppercase text-gray-500 font-semibold">Admin Comment</dt>
                    <dd class="md:col-span-2 text-sm text-gray-800 whitespace-pre-line">
                        <?php echo e($deposit->comment); ?>

                    </dd>
                </div>
            <?php endif; ?>
        </dl>
    </div>

    
    <div class="bg-white shadow rounded-lg border border-gray-200 mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fa fa-user mr-2 text-blue-500"></i>Customer Information
            </h2>
        </div>
        <dl class="divide-y divide-gray-200">
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Name</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">
                    <?php echo e($deposit->user->name ?? '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Email</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">
                    <?php echo e($deposit->user->email ?? '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">Phone</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">
                    <?php echo e($deposit->user->phone ?? '—'); ?>

                </dd>
            </div>
            <div class="px-4 py-3 grid grid-cols-1 md:grid-cols-3 gap-2">
                <dt class="text-xs uppercase text-gray-500 font-semibold">User ID</dt>
                <dd class="md:col-span-2 text-sm text-gray-800">#<?php echo e($deposit->user_id); ?></dd>
            </div>
        </dl>
    </div>

    
    <div class="bg-white shadow rounded-lg border border-gray-200 mb-6">
        <div class="px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fa fa-file-image-o mr-2 text-blue-500"></i>Proof of Payment
            </h2>
        </div>
        <div class="p-4">
            <?php if($deposit->proof_of_payment): ?>
                <?php
                    $ext = strtolower(pathinfo($deposit->proof_of_payment, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                ?>
                <?php if($isImage): ?>
                    <a href="<?php echo e(asset('storage/' . $deposit->proof_of_payment)); ?>" target="_blank">
                        <img src="<?php echo e(asset('storage/' . $deposit->proof_of_payment)); ?>"
                             alt="Proof of payment"
                             class="max-h-96 rounded border border-gray-200 hover:opacity-90 transition">
                    </a>
                    <p class="text-xs text-gray-500 mt-2">
                        Click image to open full size in a new tab.
                    </p>
                <?php else: ?>
                    <a href="<?php echo e(asset('storage/' . $deposit->proof_of_payment)); ?>" target="_blank"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fa fa-download mr-2"></i>
                        Download proof of payment (<?php echo e(strtoupper($ext)); ?>)
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-sm text-gray-500 italic">No proof of payment was uploaded for this transaction.</p>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if($deposit->status !== 'used'): ?>
        <div class="bg-white shadow rounded-lg border border-gray-200 mb-6">
            <div class="px-4 py-3 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <i class="fa fa-gavel mr-2 text-blue-500"></i>Decision
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Choose how to handle this transaction. Approval credits the user's DEPOSIT balance and unlocks dashboard access for Manual Deposit & Pay Later requests.
                </p>
            </div>

            
            <div class="px-4 py-4 border-b border-gray-200">
                <?php if($deposit->status === 'approved'): ?>
                    <p class="text-sm text-green-700 font-semibold">
                        <i class="fa fa-check-circle"></i> This deposit has already been approved.
                    </p>
                <?php else: ?>
                    <form action="<?php echo e(route('admin.approve-deposit')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="deposit" value="<?php echo e($deposit->id); ?>">
                        <button type="submit"
                                onclick="return confirm('Approve this deposit and credit <?php echo e(number_format($deposit->amount_deposited, 2)); ?> USD to the user\'s DEPOSIT balance? Deposits are not withdrawable.')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded shadow">
                            <i class="fa fa-check mr-2"></i> Approve Deposit
                        </button>
                        <span class="ml-3 text-xs text-gray-500">
                            Credits the user's DEPOSIT balance immediately. Deposits are not withdrawable.
                        </span>
                    </form>
                <?php endif; ?>
            </div>

            
            <div class="px-4 py-4">
                <form action="<?php echo e(route('admin.otherwise-decision-deposit')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="deposit" value="<?php echo e($deposit->id); ?>">

                    <label class="block text-xs uppercase text-gray-500 font-semibold mb-2">
                        Or, send this deposit to another state with a comment
                    </label>

                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php if($deposit->status !== 'under-review'): ?>
                            <label class="inline-flex items-center px-3 py-2 border border-blue-300 rounded cursor-pointer hover:bg-blue-50">
                                <input type="radio" name="action" value="under-review" class="mr-2">
                                <span class="text-sm text-blue-700 font-semibold">
                                    <i class="fa fa-hourglass-half"></i> Under Review
                                </span>
                            </label>
                        <?php endif; ?>
                        <?php if($deposit->status !== 'rejected'): ?>
                            <label class="inline-flex items-center px-3 py-2 border border-yellow-300 rounded cursor-pointer hover:bg-yellow-50">
                                <input type="radio" name="action" value="rejected" class="mr-2" required>
                                <span class="text-sm text-yellow-700 font-semibold">
                                    <i class="fa fa-ban"></i> Reject
                                </span>
                            </label>
                        <?php endif; ?>
                        <?php if($deposit->status !== 'cancelled'): ?>
                            <label class="inline-flex items-center px-3 py-2 border border-red-300 rounded cursor-pointer hover:bg-red-50">
                                <input type="radio" name="action" value="cancelled" class="mr-2">
                                <span class="text-sm text-red-700 font-semibold">
                                    <i class="fa fa-trash"></i> Cancel
                                </span>
                            </label>
                        <?php endif; ?>
                    </div>

                    <textarea name="comment" rows="4"
                              class="w-full px-3 py-2 outline-none rounded border border-gray-300 focus:ring-2 focus:ring-blue-200 focus:border-blue-500"
                              placeholder="Reason / message that will be emailed to the user..."><?php echo e(old('comment')); ?></textarea>

                    <div class="mt-3">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded shadow">
                            <i class="fa fa-paper-plane mr-2"></i> Submit Decision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>
<script>
(function(){
    function pad(n){ return String(n).padStart(2, '0'); }
    function renderCountdowns(){
        document.querySelectorAll('.deposit-countdown[data-expires]').forEach(function(el){
            var expires = new Date(el.getAttribute('data-expires')).getTime();
            var remaining = Math.floor((expires - Date.now()) / 1000);
            if (remaining <= 0) {
                el.textContent = 'EXPIRED';
                el.classList.remove('text-orange-600');
                el.classList.add('text-red-600');
                return;
            }
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;
            el.textContent = pad(m) + ':' + pad(s);
        });
    }
    renderCountdowns();
    setInterval(renderCountdowns, 1000);
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/deposit-detail.blade.php ENDPATH**/ ?>
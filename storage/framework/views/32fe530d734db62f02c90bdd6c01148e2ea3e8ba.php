<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4 px-4">

    <?php if(session('message')): ?><div class="alert alert-success"><?php echo e(session('message')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="alert alert-danger"><?php echo e(session('error')); ?></div><?php endif; ?>

    <header class="bg-blue-50 py-6 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold"><i class="fas fa-coins mr-2"></i>Token Withdrawals</h1>
    </header>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white font-weight-bold"><i class="fas fa-gas-pump mr-1"></i>Gas Fees (20% Charges) — Admin Only</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Gas Fee (tokens)</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $gasFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userId => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $u = \App\Models\User::find($userId); ?>
                        <tr>
                            <td><?php echo e($u->name ?? '—'); ?> <small class="text-muted"><?php echo e($u->email ?? ''); ?></small></td>
                            <td><?php echo e(number_format($amount, 0)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="2" class="text-muted p-3">No gas fees recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark font-weight-bold"><i class="fas fa-clock mr-1"></i>Pending (<?php echo e($pending->count()); ?>)</div>
        <div class="card-body p-0">
            <?php if($pending->isEmpty()): ?><p class="text-muted p-3 mb-0">No pending requests.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Tokens</th><th>Wallet</th><th>Coin Value</th><th>Ref</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($tw->user->name ?? '—'); ?></strong><br><small class="text-muted"><?php echo e($tw->user->email ?? ''); ?></small></td>
                            <td class="font-weight-bold"><?php echo e(number_format($tw->token_amount, 0)); ?></td>
                            <td><small><?php echo e($tw->wallet_address); ?></small></td>
                            <td><small>$<?php echo e(number_format($tw->coin_value_at_request, 6)); ?></small></td>
                            <td><small class="text-muted"><?php echo e($tw->transaction_no); ?></small></td>
                            <td><small><?php echo e($tw->created_at->format('d M Y H:i')); ?></small></td>
                            <td>
                                <form method="POST" action="<?php echo e(route('admin.token-withdrawals.approve')); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="token_withdrawal_id" value="<?php echo e($tw->id); ?>">
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve?')"><i class="fas fa-check"></i></button>
                                </form>
                                <button class="btn btn-danger btn-sm" onclick="showReject(<?php echo e($tw->id); ?>)"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i>Processed</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Tokens</th><th>Status</th><th>Note</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($tw->user->name ?? '—'); ?></td>
                            <td><?php echo e(number_format($tw->token_amount, 0)); ?></td>
                            <td><span class="badge badge-<?php echo e($tw->status === 'approved' ? 'success' : 'danger'); ?>"><?php echo e(ucfirst($tw->status)); ?></span></td>
                            <td><small><?php echo e($tw->admin_note ?? '—'); ?></small></td>
                            <td><small><?php echo e($tw->created_at->format('d M Y')); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-muted p-3">None yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="p-2"><?php echo e($completed->links()); ?></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white"><h5 class="modal-title">Reject Token Withdrawal</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST" action="<?php echo e(route('admin.token-withdrawals.reject')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="token_withdrawal_id" id="rejectId">
                    <div class="form-group"><label>Reason</label><textarea name="admin_note" class="form-control" rows="2" placeholder="Reason for rejection…"></textarea></div>
                    <p class="text-info small"><i class="fas fa-info-circle mr-1"></i>Tokens will be refunded to the user.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>function showReject(id){ document.getElementById('rejectId').value=id; $('#rejectModal').modal('show'); }</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/token-withdrawals.blade.php ENDPATH**/ ?>
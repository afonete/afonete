<?php $__env->startSection('contents'); ?>
<div class="container-fluid py-4">

    <?php if(session('message')): ?>
        <div class="alert alert-success"><?php echo e(session('message')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold uppercase text-slate-700 mb-0">Withdrawal Requests</h2>
        <div class="btn-group btn-group-sm" role="group" aria-label="Filter by method">
            <a href="<?php echo e(route('admin.withdrawal')); ?>"
               class="btn btn-<?php echo e(empty($methodFilter) ? 'primary' : 'outline-primary'); ?>">All</a>
            <a href="<?php echo e(route('admin.withdrawal', ['method' => 'crypto'])); ?>"
               class="btn btn-<?php echo e(($methodFilter ?? '') === 'crypto' ? 'primary' : 'outline-primary'); ?>">
                <i class="fab fa-bitcoin"></i> Crypto
            </a>
            <a href="<?php echo e(route('admin.withdrawal', ['method' => 'advcash'])); ?>"
               class="btn btn-<?php echo e(($methodFilter ?? '') === 'advcash' ? 'warning' : 'outline-warning'); ?>">
                <i class="fas fa-money-bill-wave"></i> Advcash
            </a>
            <a href="<?php echo e(route('admin.withdrawal', ['method' => 'perfect_money'])); ?>"
               class="btn btn-<?php echo e(($methodFilter ?? '') === 'perfect_money' ? 'info' : 'outline-info'); ?>">
                <i class="fas fa-coins"></i> Perfect Money
            </a>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-clock mr-1"></i> Pending (<?php echo e($pending->count()); ?>)
            <small class="ml-2 font-weight-normal">— Requires approval or rejection</small>
        </div>
        <div class="card-body p-0">
            <?php if($pending->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No pending withdrawals.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Currency</th>
                            <th>Network</th>
                            <th>Wallet Address</th>
                            <th>Reference</th>
                            <th>Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td>
                                <strong><?php echo e($w->user->name ?? '—'); ?></strong><br>
                                <small class="text-muted"><?php echo e($w->user->email ?? ''); ?></small>
                            </td>
                            <td class="font-weight-bold text-danger">$<?php echo e(number_format($w->amount, 2)); ?></td>
                            <td>
                                <?php
                                    $mb = $w->method === 'crypto' ? 'primary'
                                        : ($w->method === 'advcash' ? 'warning'
                                        : ($w->method === 'perfect_money' ? 'info' : 'secondary'));
                                ?>
                                <span class="badge badge-<?php echo e($mb); ?>"><?php echo e($w->methodLabel()); ?></span>
                            </td>
                            <td><small><?php echo e($w->currency); ?></small></td>
                            <td><small class="text-muted"><?php echo e($w->network ?? '—'); ?></small></td>
                            <td><small style="word-break:break-all;"><?php echo e($w->wallet_address); ?></small></td>
                            <td><small class="text-muted"><?php echo e($w->transaction_no); ?></small></td>
                            <td><small><?php echo e($w->created_at->format('d M Y H:i')); ?></small></td>
                            <td>
                                
                                <form method="POST" action="<?php echo e(route('admin.withdrawal.approve')); ?>" class="d-inline-block">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="withdrawal_id" value="<?php echo e($w->id); ?>">
                                    <input type="text" name="txn_hash" placeholder="on-chain hash (optional)"
                                           class="form-control form-control-sm mb-1" style="width:220px">
                                    <input type="text" name="admin_note" placeholder="note (optional)"
                                           class="form-control form-control-sm mb-1" style="width:220px">
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Approve withdrawal of $<?php echo e(number_format($w->amount,2)); ?> for <?php echo e($w->user->name ?? ''); ?>?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="showRejectModal(<?php echo e($w->id); ?>, '<?php echo e(addslashes($w->user->name ?? '')); ?>', <?php echo e($w->amount); ?>)">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white font-weight-bold">
            <i class="fas fa-sync-alt mr-1"></i> Processing / Queued (<?php echo e(isset($processing) ? $processing->count() : 0); ?>)
            <small class="ml-2 font-weight-normal">— Automatic blockchain payout in progress</small>
        </div>
        <div class="card-body p-0">
            <?php if(!isset($processing) || $processing->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No processing withdrawals.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Destination</th>
                            <th>Reference</th>
                            <th>Attempts</th>
                            <th>Last Attempt</th>
                            <th>Risk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $processing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($w->user->name ?? '—'); ?><br><small class="text-muted"><?php echo e($w->user->email ?? ''); ?></small></td>
                            <td>$<?php echo e(number_format($w->amount, 2)); ?></td>
                            <td><small style="word-break:break-all;"><?php echo e($w->currency); ?> <?php echo e($w->network); ?> — <?php echo e($w->wallet_address); ?></small></td>
                            <td><small><?php echo e($w->transaction_no); ?></small></td>
                            <td><?php echo e($w->attempts ?? 0); ?></td>
                            <td><small><?php echo e($w->last_attempt_at ? $w->last_attempt_at->format('d M H:i') : '—'); ?></small></td>
                            <td>
                                <span class="badge badge-<?php echo e(($w->risk_score ?? 0) >= 50 ? 'danger' : 'secondary'); ?>"><?php echo e($w->risk_score ?? 0); ?></span>
                                <?php if(!empty($w->risk_flags)): ?>
                                    <small class="d-block text-muted"><?php echo e(implode(', ', $w->risk_flags)); ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white font-weight-bold">
            <i class="fas fa-check-circle mr-1"></i> Completed
        </div>
        <div class="card-body p-0">
            <?php if($completed->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No completed withdrawals yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Wallet</th>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($w->user->name ?? '—'); ?><br><small class="text-muted"><?php echo e($w->user->email ?? ''); ?></small></td>
                            <td>$<?php echo e(number_format($w->amount, 2)); ?></td>
                            <td>
                                <?php
                                    $mb = $w->method === 'crypto' ? 'primary'
                                        : ($w->method === 'advcash' ? 'warning'
                                        : ($w->method === 'perfect_money' ? 'info' : 'secondary'));
                                ?>
                                <span class="badge badge-<?php echo e($mb); ?>"><?php echo e($w->methodLabel()); ?></span>
                                <small class="text-muted d-block"><?php echo e($w->currency); ?> <?php echo e($w->network ? '· ' . $w->network : ''); ?></small>
                            </td>
                            <td><small style="word-break:break-all;"><?php echo e(Str::limit($w->wallet_address, 25)); ?></small></td>
                            <td><small class="text-muted"><?php echo e($w->transaction_no); ?></small></td>
                            <td><span class="badge badge-info"><?php echo e($w->typeLabel()); ?></span></td>
                            <td><small><?php echo e($w->admin_note ?? '—'); ?></small></td>
                            <td><small><?php echo e($w->created_at->format('d M Y')); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="p-2"><?php echo e($completed->links()); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>


<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times mr-2"></i>Reject Withdrawal</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.withdrawal.reject')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="withdrawal_id" id="rejectWithdrawalId">
                    <p id="rejectSummary" class="text-muted"></p>
                    <p class="text-info"><i class="fas fa-info-circle mr-1"></i>The amount will be <strong>refunded</strong> to the user's CASHOUT balance.</p>
                    <div class="form-group">
                        <label>Reason / Note (shown to user)</label>
                        <textarea name="admin_note" rows="3" class="form-control" placeholder="e.g. Invalid wallet address, please resubmit..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i>Confirm Reject & Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(id, name, amount) {
    document.getElementById('rejectWithdrawalId').value = id;
    document.getElementById('rejectSummary').textContent =
        'Rejecting $' + parseFloat(amount).toFixed(2) + ' withdrawal for ' + name + '.';
    $('#rejectModal').modal('show');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/admin/Withdrawal.blade.php ENDPATH**/ ?>
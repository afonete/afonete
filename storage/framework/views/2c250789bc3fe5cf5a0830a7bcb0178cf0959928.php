<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back to Deposit
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-history text-primary mr-2"></i> My Deposit History</h3>

    
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Pending</small>
                <div class="font-weight-bold text-warning" style="font-size:1.4rem;">$<?php echo e(number_format($totals['pending'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Approved (total)</small>
                <div class="font-weight-bold text-success" style="font-size:1.4rem;">$<?php echo e(number_format($totals['approved'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Available Balance</small>
                <div class="font-weight-bold text-primary" style="font-size:1.4rem;">$<?php echo e(number_format($totals['available'], 2)); ?></div>
                <small class="text-muted">approved − used</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Used for Packages</small>
                <div class="font-weight-bold text-info" style="font-size:1.4rem;">$<?php echo e(number_format($totals['used'], 2)); ?></div>
            </div></div>
        </div>
    </div>

    
    <form method="GET" class="mt-3">
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('user.deposits.history')); ?>" class="btn btn-sm btn-<?php echo e(!request('status') ? 'primary' : 'outline-primary'); ?>">All</a>
            <a href="<?php echo e(route('user.deposits.history', ['status'=>'pending'])); ?>" class="btn btn-sm btn-<?php echo e(request('status')==='pending' ? 'warning' : 'outline-warning'); ?>">Pending</a>
            <a href="<?php echo e(route('user.deposits.history', ['status'=>'approved'])); ?>" class="btn btn-sm btn-<?php echo e(request('status')==='approved' ? 'success' : 'outline-success'); ?>">Approved</a>
            <a href="<?php echo e(route('user.deposits.history', ['status'=>'rejected'])); ?>" class="btn btn-sm btn-<?php echo e(request('status')==='rejected' ? 'danger' : 'outline-danger'); ?>">Rejected</a>
            <a href="<?php echo e(route('user.deposits.history', ['status'=>'used'])); ?>" class="btn btn-sm btn-<?php echo e(request('status')==='used' ? 'info' : 'outline-info'); ?>">Used</a>
        </div>
    </form>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            <?php if($deposits->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No deposits yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Ref</th><th>Date</th><th>Method</th><th>Network</th>
                            <th>Amount</th><th>Used</th><th>Status</th><th>Your Wallet</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $statusBadge = match($d->status) {
                                    'approved'  => 'success',
                                    'pending'   => 'warning',
                                    'rejected'  => 'danger',
                                    'cancelled' => 'secondary',
                                    'used'      => 'info',
                                    'under-review' => 'secondary',
                                    default     => 'secondary',
                                };
                            ?>
                            <tr>
                                <td><small><?php echo e($d->transaction_id); ?></small></td>
                                <td><small><?php echo e($d->created_at->format('d M Y H:i')); ?></small></td>
                                <td><?php echo e($d->deposit_method); ?></td>
                                <td><?php echo e($d->network ?? '—'); ?></td>
                                <td class="font-weight-bold">$<?php echo e(number_format($d->amount_deposited, 2)); ?></td>
                                <td>$<?php echo e(number_format($d->amount_removed, 2)); ?></td>
                                <td><span class="badge badge-<?php echo e($statusBadge); ?>"><?php echo e(ucfirst($d->status)); ?></span></td>
                                <td><small class="text-muted"><?php echo e(Str::limit($d->user_wallet_address ?? '—', 14)); ?></small></td>
                                <td>
                                    <?php if($d->proof_of_payment): ?>
                                        <a href="<?php echo e(asset('storage/' . $d->proof_of_payment)); ?>" target="_blank"
                                           class="btn btn-sm btn-outline-info">View</a>
                                    <?php else: ?> — <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($deposits->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/balance/deposit-history.blade.php ENDPATH**/ ?>
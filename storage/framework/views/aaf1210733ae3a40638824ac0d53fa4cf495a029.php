<div class="wrapper">
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <h3 class="font-weight-bold mb-3"><i class="fas fa-box-open text-primary mr-2"></i> My Investments</h3>

    
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Total Investments</small>
                <div class="font-weight-bold text-primary" style="font-size:1.6rem;"><?php echo e($totals['total_count']); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Currently Active</small>
                <div class="font-weight-bold text-success" style="font-size:1.6rem;"><?php echo e($totals['active_count']); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Total Invested (paid)</small>
                <div class="font-weight-bold text-info" style="font-size:1.6rem;">$<?php echo e(number_format($totals['total_invested'], 2)); ?></div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center"><div class="card-body">
                <small class="text-muted">Active Amount</small>
                <div class="font-weight-bold text-warning" style="font-size:1.6rem;">$<?php echo e(number_format($totals['active_amount'], 2)); ?></div>
            </div></div>
        </div>
    </div>

    
    <div class="d-flex gap-2 mt-3">
        <a href="<?php echo e(route('user.venture')); ?>" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Buy a New UVP Package
        </a>
        <a href="<?php echo e(route('user.buypackage')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart mr-1"></i> Browse Available Packages
        </a>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold">
            <i class="fas fa-list mr-1"></i> All My Packages
        </div>
        <div class="card-body p-0">
            <?php if($investments->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">You haven't bought any packages yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Package</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Bought On</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $investments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $cat = strtoupper($inv->category ?? '—');
                                $expired = $inv->is_expired;
                                $paidOk  = (int) $inv->status === 1;
                                $badgeClass = $expired ? 'secondary' : ($paidOk ? 'success' : 'warning');
                                $badgeText  = $expired ? 'Expired' : ($paidOk ? 'Active' : 'Pending');
                            ?>
                            <tr>
                                <td><?php echo e($inv->id); ?></td>
                                <td><strong><?php echo e($inv->package); ?></strong></td>
                                <td>
                                    <span class="badge badge-<?php echo e($cat === 'VENTURE' ? 'primary' : ($cat === 'FC' ? 'info' : 'secondary')); ?>">
                                        <?php echo e($cat); ?>

                                    </span>
                                </td>
                                <td>$<?php echo e(number_format($inv->amount, 2)); ?></td>
                                <td>$<?php echo e(number_format($inv->paid ?? 0, 2)); ?></td>
                                <td><span class="badge badge-<?php echo e($badgeClass); ?>"><?php echo e($badgeText); ?></span></td>
                                <td>
                                    <?php if($inv->expiration_date): ?>
                                        <small><?php echo e(\Carbon\Carbon::parse($inv->expiration_date)->format('d M Y')); ?></small>
                                    <?php else: ?> — <?php endif; ?>
                                </td>
                                <td><small><?php echo e(\Carbon\Carbon::parse($inv->created_at)->format('d M Y')); ?></small></td>
                                <td>
                                    <a href="<?php echo e(route('user.investments.show', $inv->id)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($investments->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/investments/index.blade.php ENDPATH**/ ?>
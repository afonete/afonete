<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="content-wrapper">
<div class="container-fluid py-4">

    <?php echo $__env->make('user.referral._nav', ['active' => 'rank'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="card shadow-sm border-0 mt-2">
        <div class="card-body text-center py-4">
            <?php if($current): ?>
                <div class="mb-2" style="font-size:2.4rem;">🏆</div>
                <h3 class="font-weight-bold text-primary mb-1"><?php echo e($current->rank_name); ?></h3>
                <small class="text-muted">Approved on <?php echo e($current->reviewed_at->format('d M Y')); ?></small>

                <?php if($current->congratulation_image): ?>
                    <div class="mt-3">
                        <img src="<?php echo e(asset('storage/' . $current->congratulation_image)); ?>"
                             alt="Congratulations" class="img-fluid rounded shadow-sm" style="max-height:300px;">
                    </div>
                    <div class="mt-2">
                        <a href="<?php echo e(asset('storage/' . $current->congratulation_image)); ?>"
                           download="congratulations-<?php echo e($current->rank_slug); ?>.jpg"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download mr-1"></i> Download Picture
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mb-2" style="font-size:2.4rem;">🎯</div>
                <h3 class="font-weight-bold text-muted mb-1">No rank yet</h3>
                <small class="text-muted">Build your downline to unlock ranks and earn rewards.</small>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-chart-line mr-1"></i> Rank Progress</div>
        <div class="card-body">
            <div class="row">
                <?php $__currentLoopData = $allRanks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $info = $progress[$rank->slug] ?? null; ?>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded <?php echo e($info['met'] ? 'bg-success text-white' : 'border'); ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="font-weight-bold mb-0"><?php echo e($rank->name); ?></h5>
                                <span class="badge <?php echo e($info['met'] ? 'badge-light' : 'badge-primary'); ?>">
                                    <?php echo e($info['met'] ? '✓ Qualified' : $rank->rewardLabel()); ?>

                                </span>
                            </div>
                            <?php if(!$info['met'] && $info): ?>
                                <ul class="small mb-0">
                                    <?php if($rank->min_active_direct_referrals > 0): ?>
                                        <li><?php echo e($info['actual']['active_direct_referrals']); ?> / <?php echo e($rank->min_active_direct_referrals); ?> active direct referrals</li>
                                    <?php endif; ?>
                                    <?php if($rank->min_associates_from_direct > 0): ?>
                                        <li><?php echo e($info['actual']['associates_from_direct']); ?> / <?php echo e($rank->min_associates_from_direct); ?> of your directs are Associates</li>
                                    <?php endif; ?>
                                    <?php if($rank->min_directors_from_direct > 0): ?>
                                        <li><?php echo e($info['actual']['directors_from_direct']); ?> / <?php echo e($rank->min_directors_from_direct); ?> of your directs are Directors</li>
                                    <?php endif; ?>
                                    <?php if($rank->min_regional_supervisors_from_direct > 0): ?>
                                        <li><?php echo e($info['actual']['regional_supervisors_from_direct']); ?> / <?php echo e($rank->min_regional_supervisors_from_direct); ?> of your directs are Regional Supervisors</li>
                                    <?php endif; ?>
                                    <?php if($rank->min_direct_referral_investment > 0): ?>
                                        <li>$<?php echo e(number_format($info['actual']['direct_referral_investment'], 2)); ?> / $<?php echo e(number_format($rank->min_direct_referral_investment, 0)); ?> direct-ref investment</li>
                                    <?php endif; ?>
                                    <?php if($rank->min_total_investment > 0): ?>
                                        <li>$<?php echo e(number_format($info['actual']['total_investment'], 2)); ?> / $<?php echo e(number_format($rank->min_total_investment, 0)); ?> total network investment</li>
                                    <?php endif; ?>
                                </ul>
                            <?php elseif($info['met']): ?>
                                <small>Your application will be reviewed by admin. Reward: <strong><?php echo e($rank->rewardLabel()); ?></strong></small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Rank History</div>
        <div class="card-body p-0">
            <?php if($history->isEmpty()): ?>
                <p class="text-muted p-3 mb-0">No rank history yet.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Rank</th><th>Status</th><th>Detected</th><th>Reviewed</th><th>Reward</th><th>Picture</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($h->rank_name); ?></strong></td>
                                <td><?php echo $h->statusBadge(); ?></td>
                                <td><small><?php echo e(optional($h->detected_at)->format('d M Y') ?? '—'); ?></small></td>
                                <td><small><?php echo e(optional($h->reviewed_at)->format('d M Y') ?? '—'); ?></small></td>
                                <td>$<?php echo e(number_format($h->reward_amount, 2)); ?></td>
                                <td>
                                    <?php if($h->congratulation_image): ?>
                                        <a href="<?php echo e(asset('storage/' . $h->congratulation_image)); ?>" target="_blank"
                                           class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo e(asset('storage/' . $h->congratulation_image)); ?>" download
                                           class="btn btn-sm btn-outline-success">Download</a>
                                    <?php else: ?> — <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?php echo e($h->admin_notes ?? '—'); ?></small></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="p-3"><?php echo e($history->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/referral/rank.blade.php ENDPATH**/ ?>
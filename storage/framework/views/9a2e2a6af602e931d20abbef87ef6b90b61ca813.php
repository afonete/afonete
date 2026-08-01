<?php
    $tabs = [
        ['route' => 'user.referral.bonus',    'label' => 'Bonus & Withdraw'],
        ['route' => 'user.referral.downline',  'label' => 'Downline'],
        ['route' => 'user.referral.rank',      'label' => 'Ranks & Rewards'],
    ];
?>
<ul class="nav nav-pills mb-3">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs($tab['route']) ? 'active bg-primary text-white' : 'text-primary'); ?>"
               href="<?php echo e(route($tab['route'])); ?>">
                <?php echo e($tab['label']); ?>

            </a>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/referral/_nav.blade.php ENDPATH**/ ?>
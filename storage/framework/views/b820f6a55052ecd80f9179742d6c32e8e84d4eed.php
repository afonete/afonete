<?php $__env->startComponent('mail::message'); ?>
# ❌ Withdrawal Rejected

Hello <?php echo e($user->name); ?>,

Unfortunately, your withdrawal request has been rejected.

| | |
|--|--|
| **Amount** | $<?php echo e(number_format($amount, 2)); ?> USDT |
| **Reference** | <?php echo e($transactionNo); ?> |
| **Reason** | <?php echo e($reason); ?> |

The requested amount has been **refunded** back to your CASHOUT wallet balance. You can submit a new request with corrected details.

If you have questions, please contact support.

Thanks,
<?php echo e(config('app.name')); ?> Team
<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/withdrawal-rejected.blade.php ENDPATH**/ ?>
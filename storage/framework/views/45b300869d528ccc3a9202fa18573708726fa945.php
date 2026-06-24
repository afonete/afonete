<?php $__env->startComponent('mail::message'); ?>
# 🔔 New Manual Withdrawal Request

**User:** <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)  
**Amount:** $<?php echo e(number_format($amount, 2)); ?> USDT  
**Wallet:** <?php echo e($address); ?>  
**Reference:** <?php echo e($transactionNo); ?>  
**Submitted:** <?php echo e(now()->format('d M Y H:i')); ?>


Please review and approve/reject in the admin panel.

<?php $__env->startComponent('mail::button', ['url' => url('/admin/withdrawal')]); ?>
Review Withdrawal
<?php echo $__env->renderComponent(); ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/withdrawal-requested.blade.php ENDPATH**/ ?>
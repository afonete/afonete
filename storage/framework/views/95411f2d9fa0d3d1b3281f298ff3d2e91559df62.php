<!DOCTYPE html>
<html>
<head>
    <title>Deposit Approval Notification</title>
</head>
<body>
    <p>Dear <?php echo e($user->name); ?>,</p>

    <p>We are pleased to inform you that your recent deposit of <?php echo e($deposit); ?> has been successfully approved.</p>
    <p>You can now start investing and making money on our site. We are committed to providing you with the best investment opportunities and support to help you achieve your financial goals.</p>
    <p>If you have any questions or need assistance, please do not hesitate to contact our support team.</p>
    <p>Thank you for choosing us for your investment needs.</p>
    <p>Best regards,</p>
    <p><?php echo e(config('app.name')); ?><br>
       Customer Support Team<br>
       <?php echo e(config('app.support_email')); ?><br>
       <?php echo e(config('app.phone_number')); ?></p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/deposit-approval.blade.php ENDPATH**/ ?>
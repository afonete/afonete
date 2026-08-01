<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification PIN</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f5f7; padding: 20px; color: #333;">
    <div style="max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        <h2 style="color: #4f46e5; margin-top: 0;">Email Verification Code</h2>
        <p>Hello <strong><?php echo e($name); ?></strong>,</p>
        <p>Thank you for registering on Bifonex. To activate your account and verify your email address, please use the following single-use activation PIN code:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-family: monospace; font-size: 2.2rem; font-weight: 800; letter-spacing: 4px; color: #1e293b; background-color: #f1f5f9; padding: 12px 24px; border-radius: 8px; border: 1px dashed #cbd5e1; display: inline-block;">
                <?php echo e($pin); ?>

            </span>
        </div>
        
        <p style="font-size: 0.85rem; color: #64748b;">If you did not initiate this registration request, please ignore this email or contact support if you have any questions.</p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;">
        <p style="font-size: 0.8rem; color: #94a3b8; text-align: center; margin-bottom: 0;">&copy; <?php echo e(date('Y')); ?> Bifonex Inc. All rights reserved.</p>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/verification_pin.blade.php ENDPATH**/ ?>
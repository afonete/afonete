<!DOCTYPE html>
<html>

<head>
    <title>Dear Fonepo user</title>
</head>

<body><?php if($package=="standard"): ?>
    <h1>Standard Package activation!</h1>
    <p>Thank you for Activating standard package. We are happy to have you in our family of winners. </p>
    <p>Your account has been directly activated to Free account.This is individual email<br> from Fonepo
        don't share it. <i>Fill free to upgrade your account to super package</i></p>
    <?php else: ?>
    <h1>Welcome on Package activation!</h1>
    <p>Thank you for Buying package. We are happy to have you in our family of winners. </p>
    <p>enter this code <b><u><?php echo e($activation); ?></u></b> to activate your package.This is individual email from Fonepo
        don't share it.</p>
    <p>thank u</p>
    <?php endif; ?>

</body>

</html><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/emails/activate.blade.php ENDPATH**/ ?>
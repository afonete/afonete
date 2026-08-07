<?php
use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->name;
$uname = $user->user;
$email = $user->email;
$phone = $user->phone;
$country = $user->country;
$package = $user->has_paid_package;
?>

<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="content-wrapper">
        <div class="container-fluid py-4">

            
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-light p-3">
                    <ul class="nav nav-pills gap-2">
                        <li class="nav-item">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="nav-link active font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-address-card mr-1"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('password.show')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-lock mr-1"></i> Password &amp; Security
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('wallet')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-wallet mr-1"></i> Wallet Address
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('user.kyc')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                                <i class="fas fa-id-card mr-1"></i> KYC Verification
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3">
                    <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('fail')): ?>
                <div class="alert alert-danger shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('fail')); ?>

                </div>
            <?php endif; ?>

            
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-slate-900 text-white font-weight-bold py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <i class="fas fa-user-circle text-warning mr-2"></i> User Information Update
                </div>

                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-3 text-center mb-4 mb-lg-0 border-right">
                            <img src="<?php echo e(asset('assets/a/img/user.png')); ?>" alt="User Image" class="rounded-circle shadow-sm border p-1 bg-white mb-2" style="width: 110px; height: 110px; object-fit: cover;">
                            <h4 class="font-weight-bold text-dark mb-0"><?php echo e($name); ?></h4>
                            <small class="text-primary font-weight-bold d-block mt-0.5">@ <?php echo e($uname); ?></small>
                            <span class="badge badge-info mt-2 px-3 py-1 font-weight-bold" style="border-radius: 6px;">
                                <?php echo e(strtoupper($user->has_paid_package ?: 'Free Standard')); ?>

                            </span>
                        </div>

                        <div class="col-lg-9">
                            <form action="<?php echo e(route('profile.updates')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Username</label>
                                        <input type="text" value="@ <?php echo e($uname); ?>" readonly class="form-control font-weight-bold" style="background:#eef2ff; border-radius: 8px;">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">7-Digit Transfer Code</label>
                                        <div class="input-group">
                                            <input type="text" value="<?php echo e($user->transfer_code ?? $user->getTransferCode()); ?>" readonly class="form-control font-mono font-weight-bold" style="background:#f8fafc; border-radius: 8px 0 0 8px;">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-dark font-weight-bold text-xs" onclick="navigator.clipboard.writeText('<?php echo e($user->transfer_code ?? $user->getTransferCode()); ?>'); alert('Transfer Code <?php echo e($user->transfer_code ?? $user->getTransferCode()); ?> copied to clipboard!');" style="border-radius: 0 8px 8px 0;">
                                                    <i class="fas fa-copy mr-1"></i> Copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="<?php echo e($name); ?>" required class="form-control font-weight-bold" style="border-radius: 8px;">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Email Address</label>
                                        <input type="email" value="<?php echo e($email); ?>" readonly class="form-control" style="background:#f8fafc; border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Phone Number</label>
                                        <input type="tel" name="phone" value="<?php echo e($phone); ?>" placeholder="Enter phone number" class="form-control" style="border-radius: 8px;">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Country</label>
                                        <input type="text" name="country" value="<?php echo e($country); ?>" placeholder="Enter country" class="form-control" style="border-radius: 8px;">
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="form-group col-md-6 mb-0">
                                        <label class="font-weight-bold text-dark">Account Package</label>
                                        <span class="badge badge-primary px-3 py-2 font-weight-bold d-inline-block" style="border-radius: 6px;"><?php echo e(strtoupper($user->has_paid_package ?: 'Free / Standard')); ?></span>
                                    </div>

                                    <div class="form-group col-md-6 mb-0">
                                        <label class="font-weight-bold text-dark">Contract Status</label>
                                        <span class="badge badge-<?php echo e($user->contract === 'Signed' ? 'success' : 'warning'); ?> px-3 py-2 font-weight-bold d-inline-block" style="border-radius: 6px;"><?php echo e($user->contract ?: 'Not Signed'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group mt-3 text-right">
                                    <button type="submit" class="btn btn-success font-weight-bold px-4 py-2" style="border-radius: 8px;">
                                        <i class="fas fa-save mr-1"></i> Save Profile Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/profile-component.blade.php ENDPATH**/ ?>
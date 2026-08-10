<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->name ?? $user->user;
$uname = $user->user;
$email = $user->email;
$phone = $user->phone;
$country = $user->country;
$package = $user->has_paid_package;
$referrer = $user->referrer ?: (\App\Models\User::find($user->referee_id));
?>

<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper py-4 px-3" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid max-w-7xl mx-auto">

        
        <div class="tabs tab_links mb-4">
            <span class="links_tabs d-flex align-items-center gap-4 border-bottom pb-2">
                <a href="<?php echo e(route('profile.edit')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" id="tabs" style="font-size: 0.95rem;">
                    <i class="fa-regular fa-address-card mr-1 text-primary"></i> My profile
                </a>
                <a href="<?php echo e(route('password.show')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-lock mr-1 text-secondary"></i> Password
                </a>
                <a href="<?php echo e(route('wallet')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-wallet mr-1 text-secondary"></i> wallet address
                </a>
                <a href="<?php echo e(route('user.kyc')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-id-card mr-1 text-secondary"></i> KYC
                </a>
            </span>
        </div>

        
        <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="upd-title text-center font-weight-bold text-dark uppercase mb-4" style="font-size: 1.35rem; letter-spacing: 0.5px;">
                USER INFORMATION UPDATE
                <?php if(session('success')): ?>
                    <span class="badge badge-success font-weight-bold ml-2 text-xs" style="font-size: 0.75rem;"><?php echo e(session('success')); ?></span>
                <?php endif; ?>
                <?php if(session('fail')): ?>
                    <span class="badge badge-danger font-weight-bold ml-2 text-xs" style="font-size: 0.75rem;"><?php echo e(session('fail')); ?></span>
                <?php endif; ?>
            </h2>

            <div class="row align-items-center">
                
                <div class="col-lg-3 text-center mb-4 mb-lg-0 border-right pr-lg-4">
                    <img src="<?php echo e(asset('assets/a/img/user.png')); ?>" class="rounded-circle shadow-sm border p-1 bg-white mb-2" alt="User Image" style="width: 130px; height: 130px; object-fit: cover;">
                    <div class="UserName font-weight-bold text-dark text-lg mt-2" style="font-size: 1.15rem;">
                        <?php echo e($name); ?>

                    </div>
                    <small class="text-primary font-weight-bold d-block">@ <?php echo e($uname); ?></small>
                </div>

                
                <div class="col-lg-9 pl-lg-4">
                    <form action="<?php echo e(route('profile.updates')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Username:</label>
                                <input type="text" value="<?php echo e($uname); ?>" disabled class="form-control" style="background:#f1f5f9;">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Name:</label>
                                <input type="text" name="name" value="<?php echo e($name); ?>" required class="form-control font-weight-bold">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Member ID / Transfer Code:</label>
                                <input type="text" value="<?php echo e($user->transfer_code ?? $user->getTransferCode()); ?>" readonly class="form-control font-mono font-weight-bold" style="background:#f8fafc;">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">City:</label>
                                <input type="text" name="city" value="<?php echo e(old('city', $user->city ?? '')); ?>" placeholder="City" class="form-control">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Date of Birth:</label>
                                <input type="date" name="dob" value="<?php echo e(old('dob', $user->dob ?? '')); ?>" class="form-control">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Joining Date:</label>
                                <input type="date" value="<?php echo e($user->created_at ? $user->created_at->format('Y-m-d') : ''); ?>" readonly class="form-control" style="background:#f8fafc;">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">User Referral Date:</label>
                                <input type="date" value="<?php echo e($user->created_at ? $user->created_at->format('Y-m-d') : ''); ?>" readonly class="form-control" style="background:#f8fafc;">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Referral User Name:</label>
                                <input type="text" value="<?php echo e($referrer ? '@' . $referrer->user : 'N/A'); ?>" readonly class="form-control" style="background:#f8fafc;">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Referral ID / Code:</label>
                                <input type="text" value="<?php echo e($referrer ? ($referrer->transfer_code ?? '#' . $referrer->id) : 'N/A'); ?>" readonly class="form-control font-mono" style="background:#f8fafc;">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Referral Email:</label>
                                <input type="email" value="<?php echo e($referrer ? $referrer->email : 'N/A'); ?>" readonly class="form-control" style="background:#f8fafc;">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-12">
                                <label class="font-weight-bold text-dark small mb-1">Referral Country:</label>
                                <input type="text" value="<?php echo e($referrer ? ($referrer->country ?: 'N/A') : ($country ?: 'N/A')); ?>" readonly class="form-control" style="background:#f8fafc;">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Email:</label>
                                <input type="email" value="<?php echo e($email); ?>" disabled class="form-control" style="background:#f8fafc;">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Phone:</label>
                                <input type="tel" name="phone" value="<?php echo e($phone); ?>" class="form-control font-weight-bold">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark small mb-1">Country:</label>
                                <input type="text" name="country" value="<?php echo e($country); ?>" class="form-control font-weight-bold">
                            </div>
                            <div class="form-group col-md-6 mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Residential Address:</label>
                                <input type="text" name="address" value="<?php echo e(old('address', $user->address ?? '')); ?>" placeholder="Enter street/residential address" class="form-control font-weight-bold">
                            </div>
                        </div>

                        <div class="mt-4">
                            <input type="submit" class="btn btn-success btn-block font-weight-bold py-2.5" value="Update" style="background-color: #4CAF50; border: none; border-radius: 6px; font-size: 1rem;">
                        </div>

                        <div class="text-center mt-2">
                            <a href="<?php echo e(route('password.show')); ?>" style="color: #2563eb; text-decoration: underline; font-weight: 600; font-size: 0.9rem;">change password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/profile-component.blade.php ENDPATH**/ ?>
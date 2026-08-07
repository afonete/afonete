<?php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use App\Models\Wallet;

    $user        = Auth::user();
    $uname       = $user->user;
    $wallet      = Wallet::where('user', $uname)->first();
    $address     = $wallet ? $wallet->wallet : '';
    $placeholder = 'Enter external wallet address. ex: TRC-20 address (T...)';
?>

<div class="wrapper">
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper">
    <div class="container-fluid py-4">

        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light p-3">
                <ul class="nav nav-pills gap-2">
                    <li class="nav-item">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-address-card mr-1"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('password.show')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-lock mr-1"></i> Password &amp; Security
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('wallet')); ?>" class="nav-link active font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
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

        <?php if(session('success')): ?><div class="alert alert-success shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3" style="border-radius: 8px;"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('failed')): ?><div class="alert alert-danger shadow-sm rounded-xl p-3 font-weight-bold text-sm mb-3" style="border-radius: 8px;"><?php echo e(session('failed')); ?></div><?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-slate-900 text-white font-weight-bold py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <i class="fas fa-wallet text-warning mr-2"></i> External Withdrawal Wallet Address
                    </div>
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('user.wallet')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold text-dark">External Wallet Address (TRC-20 USDT) <span class="text-danger">*</span></label>
                                <input type="text" name="wallet" class="form-control font-mono font-weight-bold" value="<?php echo e(old('wallet', $address)); ?>" placeholder="<?php echo e($placeholder); ?>" required style="border-radius: 8px;">
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Ensure your withdrawal address is accurate. Withdrawals will be sent to this destination address.</small>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn btn-success font-weight-bold px-4 py-2 text-white" style="border-radius: 8px;">
                                    <i class="fas fa-save mr-1"></i> Save Wallet Address
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
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/wallet.blade.php ENDPATH**/ ?>
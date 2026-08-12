<?php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;

    $user = Auth::user();
    $name = $user->name ?? $user->user;
    $userr = $user->user;
    $email = $user->email;

    $highestUvpPackageAmount = $user ? $user->highestUvpPackageAmount() : 0.0;

    $smartBackUrl = url('/');
    if ($user) {
        $isSignedContract = ($user->contract === 'Signed');
        $isVerified       = ($user->email_verified_at !== null || $user->activation_status === 'verified' || $user->has_request === 'approved');
        $isFreeUser       = ($user->has_free_package === 'yes');
        $hasPaidPackage   = !empty($user->has_paid_package) && !in_array(strtolower(trim($user->has_paid_package)), ['no', 'standard', '']);

        if ($isSignedContract || $isVerified || $isFreeUser || $hasPaidPackage) {
            $smartBackUrl = route('user.dashboard');
        }
    }
?>

<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .pkg-container {
            width: 100%;
            padding: 1.5rem 1rem;
        }
        .dash-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.25s ease !important;
            background: #ffffff;
            color: #1e293b;
            overflow: hidden;
        }
        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }
        .dash-header-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 16px;
        }
    </style>

    <div class="content-wrapper" style="background-color: #f8fafc; min-height: 100vh;">
        <div class="container-fluid pkg-container max-w-7xl mx-auto">

            
            <div class="dash-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="javascript:history.back()" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <div>
                        <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.25rem;">
                            <i class="fas fa-gem text-warning mr-2"></i> FC Packages &amp; Account Activation
                        </h4>
                        <small class="text-light opacity-90">Manage membership activations, redeem codes, and view package tiers.</small>
                    </div>
                </div>
            </div>

            
            <?php if(session('message')): ?>
                <div class="alert alert-info text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-info-circle mr-2"></i><?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            
            <?php
                $isLeader = in_array(strtoupper((string) $user->has_paid_package), ['TEAM_LEADER', 'SUPER_LEADER']);
                $matchingLeader = \App\Models\TeamLeader::where('User_name', $user->user)->orWhere('Email', $user->email)->first();
                $leaderCredit = $matchingLeader ? $matchingLeader->superLeaderCredit : null;
                $isCreditDisabled = $leaderCredit ? in_array(strtolower($leaderCredit->status), ['disabled', 'deactivated', 'rejected']) : false;

                $latestLeaderPayment = \App\Models\Payment::where('user', $user->id)
                    ->whereIn('package', ['TEAM_LEADER', 'SUPER_LEADER'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                $isLeaderExpired = false;
                if ($latestLeaderPayment) {
                    if ($latestLeaderPayment->is_expired) {
                        $isLeaderExpired = true;
                    } elseif ($latestLeaderPayment->expiration_date && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($latestLeaderPayment->expiration_date))) {
                        $isLeaderExpired = true;
                    }
                }

                $isActiveLeader = $isLeader && $matchingLeader && $matchingLeader->status === 'confirmed' && !$isCreditDisabled && !$isLeaderExpired;
            ?>

            <?php if(!$isActiveLeader): ?>
            <div class="row mb-4">
                
                <div class="col-12 col-md-6 mb-3">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-between border">
                        <div>
                            <div class="p-2 mb-3 rounded bg-warning text-dark font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-key mr-1"></i> Account Activation Code</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Have an Activation Code?</h4>
                            <p class="text-muted small mb-3">Redeem your package or Team Leader activation code to activate or upgrade immediately.</p>
                        </div>
                        <div>
                            <form action="<?php echo e(route('user.dashboard.validate')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-group mb-2">
                                    <input type="text" name="code" placeholder="Enter Code (e.g. FOM-XXXX-XXXX or Team Leader Code)" required class="form-control text-center font-mono font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                                </div>
                                <button type="submit" class="btn btn-dark btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;">
                                    <i class="fas fa-bolt text-warning mr-1"></i> Submit Activation Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <?php
                    $isAlreadyFree = ($user->has_free_package === 'yes') || (strtolower(trim((string)$user->has_paid_package)) === 'standard');
                    $userHasActiveUvp = ($highestUvpPackageAmount > 0) || (isset($hasPaidPackage) && $hasPaidPackage);
                    $canSeeFreeTier = !$isAlreadyFree && !$userHasActiveUvp;
                ?>
                <?php if($canSeeFreeTier): ?>
                <div class="col-12 col-md-6 mb-3">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-between border">
                        <div>
                            <div class="p-2 mb-3 rounded bg-light text-dark font-weight-bold border" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;">Free Tier</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Standard Account</h4>
                            <p class="text-muted small mb-3">Activate free dashboard access for regular exploration.</p>
                        </div>
                        <div>
                            <form action="<?php echo e(route('free')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <button type="submit" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px;">
                                    <i class="fas fa-user-check mr-1"></i> Activate Free Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-box-open text-primary mr-2"></i> FC VIP Packages
                    </h5>
                </div>

                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="dash-card h-100 p-4 d-flex flex-column justify-between text-center border">
                                <div>
                                    <div class="p-2 mb-3 rounded bg-gradient-dark text-white font-weight-bold" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 8px;">
                                        <span class="text-warning font-weight-bold text-uppercase" style="font-size: 0.95rem;">FC VIP Tier</span>
                                    </div>

                                    <h2 class="font-weight-bold text-primary mb-2" style="font-size: 1.8rem;">
                                        $<?php echo e(number_format($package->price, 2)); ?>

                                    </h2>

                                    <p class="text-muted small mb-4">
                                        Full FC VIP membership access with dedicated token rewards and ecosystem privileges.
                                    </p>
                                </div>

                                <div>
                                    <form action="<?php echo e(route('payment.directPackage')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="package_type" value="FC">
                                        <input type="hidden" name="package_id" value="<?php echo e($package->id); ?>">
                                        <input type="hidden" name="network" value="TRC-20">
                                        <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 13px;">
                                            <i class="fas fa-shopping-cart mr-1"></i> BUY NOW ($<?php echo e(number_format($package->price, 0)); ?>)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12 text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2 text-slate-300"></i>
                            <p class="font-weight-bold mb-0">No FC packages found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/user-package.blade.php ENDPATH**/ ?>
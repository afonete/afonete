<?php
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use App\Models\Adventures;
    use App\Models\FCpackage;

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
        .venture-container {
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
        <div class="container-fluid venture-container max-w-7xl mx-auto">

            
            <div class="dash-header-bg p-4 mb-4 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="<?php echo e($smartBackUrl); ?>" class="btn btn-outline-light font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <div>
                        <h4 class="font-weight-bold text-white mb-0" style="font-size: 1.25rem;">
                            <i class="fas fa-microchip text-warning mr-2"></i> UVP AI License Packages
                        </h4>
                        <small class="text-light opacity-90">Select a UVP AI License package tier to activate daily yield ROI earnings.</small>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                        Deposit Balance: $<?php echo e(number_format($balance ?? 0, 2)); ?>

                    </span>
                </div>
            </div>

            
            <?php if(session('message')): ?>
                <div class="alert alert-info text-center font-weight-bold p-3 mb-4" style="border-radius: 10px;">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>

            
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-cubes text-primary mr-2"></i> Available UVP AI License Tiers
                    </h5>
                    <?php if($highestUvpPackageAmount > 0): ?>
                        <span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="font-size: 0.8rem; border-radius: 6px;">
                            <i class="fas fa-lock mr-1"></i> Minimum Required Tier: $<?php echo e(number_format($highestUvpPackageAmount, 0)); ?>

                        </span>
                    <?php endif; ?>
                </div>

                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $Adventures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $minUvpTier   = $highestUvpPackageAmount ?? 0;
                            $effectiveMin = max($venture->min_amount, $minUvpTier);
                            $isBelowTier  = $minUvpTier > 0 && $venture->max_amount < $minUvpTier;
                        ?>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="dash-card h-100 p-3 d-flex flex-column justify-content-between text-center border">
                                <div>
                                    <div class="p-2.5 mb-2.5 rounded bg-dark text-white font-weight-bold" style="border-radius: 10px;">
                                        <span class="badge badge-warning text-dark font-weight-bold text-uppercase px-2 py-1 mb-1 d-inline-block" style="font-size: 0.72rem;">
                                            <i class="fas fa-layer-group mr-1"></i> <?php echo e($venture->plan ?: 'VENTURE LIGHT'); ?>

                                        </span>
                                        <span class="text-white d-block font-weight-bold" style="font-size: 0.95rem;">
                                            <?php echo e($venture->name ?: 'UVP AI License'); ?>

                                        </span>
                                        <small class="text-light opacity-90 d-block mt-0.5" style="font-size: 0.72rem;">
                                            Duration: <?php echo e($venture->duration); ?> Days
                                        </small>
                                    </div>

                                    <div class="my-3">
                                        <img src="<?php echo e(asset('image/ai-package.png')); ?>" style="width: 55px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" alt="AI Package">
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.8rem;">
                                            <?php if(!empty($venture->percentage_range)): ?>
                                                <?php echo e($venture->percentage_range); ?>

                                            <?php else: ?>
                                                <?php echo e($venture->percentage); ?>% Daily ROI
                                            <?php endif; ?>
                                        </span>
                                    </div>

                                    <div class="text-muted small mb-3">
                                        Range: <strong>$<?php echo e(number_format($venture->min_amount, 0)); ?></strong> – <strong>$<?php echo e(number_format($venture->max_amount, 0)); ?></strong><br>
                                        <span class="text-success font-weight-bold">Total Return: <?php echo e($venture->total_return ?? '200'); ?>%</span>
                                    </div>
                                </div>

                                <div>
                                    <?php if($isBelowTier): ?>
                                        <div class="alert alert-warning text-center p-2 mb-2" style="font-size: 11px; border-radius: 6px;">
                                            <i class="fas fa-lock mr-1"></i> Below Previous Tier ($<?php echo e(number_format($minUvpTier, 0)); ?> Min)
                                        </div>
                                        <button class="btn btn-secondary btn-block font-weight-bold" disabled style="font-size: 11px; border-radius: 6px;">
                                            TIER RESTRICTED
                                        </button>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('ventures')); ?>" method="POST" class="mt-2">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="venture" value="<?php echo e($venture->id); ?>"/>
                                            <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                            <div class="form-group mb-2">
                                                <input type="number" name="amount_invest"
                                                       placeholder="Amount ($<?php echo e(number_format($effectiveMin, 0)); ?> - $<?php echo e(number_format($venture->max_amount, 0)); ?>)"
                                                       class="form-control form-control-sm text-center font-weight-bold"
                                                       min="<?php echo e($effectiveMin); ?>"
                                                       max="<?php echo e($venture->max_amount); ?>"
                                                       step="0.01"
                                                       required
                                                       style="border-radius: 6px; font-size: 12px;"/>
                                            </div>
                                            <button class="btn btn-dark btn-block font-weight-bold py-2" style="font-size: 12px; border-radius: 6px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;" type="submit">
                                                <i class="fas fa-bolt text-warning mr-1"></i> INVEST NOW
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fas fa-cubes fa-3x mb-3 text-slate-300"></i>
                            <p class="font-weight-bold">No UVP AI License packages available at this moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if(isset($fc) && $fc->isNotEmpty()): ?>
            <div class="card dash-card p-4 mb-4">
                <div class="border-bottom pb-3 mb-4">
                    <h5 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-box-open text-primary mr-2"></i> FC VIP Packages
                    </h5>
                </div>

                <div class="row">
                    <?php $__currentLoopData = $fc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-sm-6 col-md-4 mb-3">
                            <div class="dash-card p-3 text-center border">
                                <span class="badge badge-danger text-white font-weight-bold uppercase mb-2" style="font-size: 0.75rem;"><?php echo e($f->name); ?></span>
                                <h4 class="font-weight-bold text-dark mb-3">$<?php echo e(number_format($f->price, 2)); ?></h4>
                                <form action="<?php echo e(route('ventures')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="venture" value="FC"/>
                                    <input type="hidden" name="package" value="<?php echo e($f->id); ?>"/>
                                    <input type="hidden" name="routes" value="<?php echo e($f->name); ?>"/>
                                    <input type="hidden" name="payment_method" value="FROM_DEPOSITS">
                                    <input type="hidden" name="amount_invest" value="<?php echo e($f->price); ?>"/>
                                    <button class="btn btn-primary btn-block font-weight-bold py-2" style="border-radius: 6px; font-size: 12px;" type="submit">
                                        BUY NOW ($<?php echo e(number_format($f->price, 0)); ?>)
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="row">
                
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-warning text-dark font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-key mr-1"></i> Activation Code</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Have a Code?</h4>
                            <p class="text-muted small mb-3">Redeem your package or Team Leader activation code.</p>
                        </div>
                        <div>
                            <form action="<?php echo e(route('validate')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form-group mb-2">
                                    <input type="text" name="code" placeholder="Enter Activation Code" required class="form-control text-center font-mono font-weight-bold" style="border-radius: 8px; font-size: 13px;">
                                </div>
                                <button type="submit" class="btn btn-dark btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px; background: linear-gradient(135deg, #1e293b, #0f172a); border: none;">
                                    <i class="fas fa-bolt text-warning mr-1"></i> Submit Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-success text-white font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-wallet mr-1"></i> Deposit Funds</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">Add Balance</h4>
                            <p class="text-muted small mb-3">Deposit USDT TRC-20 to purchase packages anytime.</p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('user.manual-deposit')); ?>" class="btn btn-success btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px;">
                                <i class="fas fa-plus-circle mr-1"></i> Make Deposit
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 col-md-4 mb-4">
                    <div class="dash-card p-4 h-100 text-center d-flex flex-column justify-content-between">
                        <div>
                            <div class="p-2 mb-3 rounded bg-primary text-white font-weight-bold" style="border-radius: 8px;">
                                <span class="text-uppercase" style="font-size: 0.85rem;"><i class="fas fa-gem mr-1"></i> FC Packages</span>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">VIP Membership</h4>
                            <p class="text-muted small mb-3">Explore FC VIP packages and membership tiers.</p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('user.package')); ?>" class="btn btn-outline-primary btn-block font-weight-bold py-2" style="border-radius: 8px; font-size: 12px;">
                                View FC Packages <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/buypackages.blade.php ENDPATH**/ ?>
<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <div class="content-wrapper" style="">   
        <section class="content">
            <div class="container-fluid">
                <?php
                    $user = Auth::user();
                    $name = $user->user;
                    $email = $user->email;
                    $activated = $user->has_paid_package;
                    $ref_code = $user->activation;
                    $totalReferrals = 0;
                    $baseUrl =  url('/');
                ?>



<!-- navigation -->
<div class="d-flex flex-wrap justify-content-center mt-4">
    <ul class="list-unstyled resource-list d-flex flex-wrap justify-content-center m-0 p-0">
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-link mr-2"></i>Referral Banners & Link
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-share-alt mr-2"></i>Social Media Images
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-file-pdf mr-2"></i>Presentation PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-trophy mr-2"></i>Ranks & Reward PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-user-tie mr-2"></i>CEO PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-play-circle mr-2"></i>Video
            </a>
        </li>
    </ul>
</div>




<!-- back the first cards -->
<div class="row mt-4">
                    <!-- Left Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-success  p-3" >
                      <span class="mb-0 text-white"><i class="las la-users"></i> Left Referral</span>
                              </div>

                            <div class="card-body">
                                <div class="referral-link-container">
                                 <h6>Left Referral Link</h6>
                                    <div class="input-group mb-3">
                                        <?php if($activated != 'standard'): ?>
                                            <input type="text" class="form-control" id="leftRefLink" 
                                            value="<?php echo e($baseUrl); ?>/register?referral=<?php echo e($ref_code); ?>&side=LEFT"

                                                 readonly>
                                        <?php else: ?>
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        <?php endif; ?>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary copy-btn" type="button" onclick="copyToClipboard('leftRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>                                      
                                    </div>
                                    <div>
                                <h6><i class="las la-users"></i> Left Referrals: <?php echo e($left_referrals->count()); ?></h6>
                                <h6><i class="las la-coins"></i> Left Earning Coins: <?php echo e($leftEarnings ?? 0); ?></h6>
                                        </div>
                                </div>
                                
                            
                            </div>
                        </div>
                    </div>

                    <!-- Right Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-primary p-3" >
                      <span class="mb-0"><i class="las la-users"></i> Right Referral</span>
                              </div>
                            <div class="card-body">
                                <div class="referral-link-container">
                                <h6>Right Referral Link</h6>
                                    <div class="input-group mb-3">
                                        <?php if($activated != 'standard'): ?>
                                            <input type="text" class="form-control" id="rightRefLink" 
                                            value="<?php echo e($baseUrl); ?>/register?referral=<?php echo e($ref_code); ?>&side=RIGHT"

                                                readonly>
                                        <?php else: ?>
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        <?php endif; ?>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                <h6><i class="las la-users"></i> Right Referrals: <?php echo e($right_referrals->count()); ?></h6>
                                <h6><i class="las la-coins"></i> Right Earning Coins: <?php echo e($leftEarnings ?? 0); ?></h6>
                                        </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <span class="badge badge-warning text-dark font-weight-bold text-uppercase px-2.5 py-1 mb-2" style="border-radius: 4px; font-size: 0.72rem;">
                                <i class="fas fa-user-check mr-1"></i> Your Referrer (Referred By)
                            </span>
                            <?php if(isset($referrerUser) && $referrerUser): ?>
                                <h4 class="font-weight-bold text-white mb-1" style="font-size: 1.3rem;">
                                    <?php echo e($referrerUser->name); ?> <span class="text-warning small font-weight-normal">(@ <?php echo e($referrerUser->user); ?>)</span>
                                </h4>
                                <div class="text-light small opacity-90">
                                    Email: <strong><?php echo e($referrerUser->email); ?></strong> · Transfer Code: <strong><?php echo e($referrerUser->getTransferCode()); ?></strong> · Joined: <strong><?php echo e($referrerUser->created_at ? $referrerUser->created_at->format('d M Y') : 'N/A'); ?></strong>
                                </div>
                            <?php else: ?>
                                <h4 class="font-weight-bold text-white mb-1" style="font-size: 1.2rem;">
                                    Registered Directly / System Master Account
                                </h4>
                                <div class="text-light small opacity-75">You joined directly without a referee link.</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="badge badge-dark px-3 py-2 font-weight-bold text-uppercase border border-secondary" style="border-radius: 8px;">
                                <i class="fas fa-sitemap text-warning mr-1"></i> Network Tree Node
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-primary text-white font-weight-bold py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-user-friends mr-2"></i> Direct Referrals (Level 1 — 10% Bonus)</span>
                        <span class="badge badge-light text-primary font-weight-bold"><?php echo e($directReferrals->total()); ?> Direct <?php echo e(Str::plural('Referral', $directReferrals->total())); ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if($directReferrals->isEmpty()): ?>
                            <p class="text-muted p-4 mb-0 text-center"><i class="fas fa-users text-muted mb-2 d-block fa-2x"></i>No direct referrals yet. Share your referral link above to invite new members.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped text-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Code</th>
                                            <th>Username</th>
                                            <th>Position / Side</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Joined Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $directReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $side = $refer->teamSide ? $refer->teamSide->side : 'N/A';
                                                $hasPkg = !empty($refer->has_paid_package) && !in_array(strtolower(trim($refer->has_paid_package)), ['no', 'standard', '']);
                                            ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration + ($directReferrals->currentPage() - 1) * $directReferrals->perPage()); ?></td>
                                                <td class="font-weight-bold text-dark font-mono"><?php echo e($refer->transfer_code ?? '—'); ?></td>
                                                <td><span class="badge badge-light border">@ <?php echo e($refer->user); ?></span></td>
                                                <td>
                                                    <span class="badge badge-<?php echo e($side === 'LEFT' ? 'info' : ($side === 'RIGHT' ? 'primary' : 'secondary')); ?> px-2 py-1">
                                                        <?php echo e($side); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-slate-800">
                                                        <?php echo e($hasPkg ? strtoupper($refer->has_paid_package) : 'FREE / STANDARD'); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?php echo e($hasPkg ? 'success' : 'warning'); ?> px-2 py-1">
                                                        <?php echo e($hasPkg ? 'Active Package' : 'Registered'); ?>

                                                    </span>
                                                </td>
                                                <td><small class="text-muted"><?php echo e($refer->created_at ? $refer->created_at->format('d M Y') : 'N/A'); ?></small></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 d-flex justify-content-center border-top">
                                <?php echo e($directReferrals->links('pagination::bootstrap-4')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-dark text-white font-weight-bold py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-sitemap mr-2"></i> Indirect Referrals (Level 2+ Downline)</span>
                        <span class="badge badge-light text-dark font-weight-bold"><?php echo e($indirectReferrals->total()); ?> Indirect <?php echo e(Str::plural('Referral', $indirectReferrals->total())); ?></span>
                    </div>
                    <div class="card-body p-0">
                        <?php if($indirectReferrals->isEmpty()): ?>
                            <p class="text-muted p-4 mb-0 text-center"><i class="fas fa-network-wired text-muted mb-2 d-block fa-2x"></i>No indirect referrals yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped text-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Code</th>
                                            <th>Username</th>
                                            <th>Referred By</th>
                                            <th>Position / Side</th>
                                            <th>Package</th>
                                            <th>Joined Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $indirectReferrals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $side = $refer->teamSide ? $refer->teamSide->side : 'N/A';
                                                $hasPkg = !empty($refer->has_paid_package) && !in_array(strtolower(trim($refer->has_paid_package)), ['no', 'standard', '']);
                                                $directRef = $refer->referrer;
                                            ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration + ($indirectReferrals->currentPage() - 1) * $indirectReferrals->perPage()); ?></td>
                                                <td class="font-weight-bold text-dark font-mono"><?php echo e($refer->transfer_code ?? '—'); ?></td>
                                                <td><span class="badge badge-light border">@ <?php echo e($refer->user); ?></span></td>
                                                <td><small class="text-primary font-weight-bold"><?php echo e($directRef ? '@' . $directRef->user : '—'); ?></small></td>
                                                <td>
                                                    <span class="badge badge-<?php echo e($side === 'LEFT' ? 'info' : ($side === 'RIGHT' ? 'primary' : 'secondary')); ?> px-2 py-1">
                                                        <?php echo e($side); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-slate-800">
                                                        <?php echo e($hasPkg ? strtoupper($refer->has_paid_package) : 'FREE / STANDARD'); ?>

                                                    </span>
                                                </td>
                                                <td><small class="text-muted"><?php echo e($refer->created_at ? $refer->created_at->format('d M Y') : 'N/A'); ?></small></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 d-flex justify-content-center border-top">
                                <?php echo e($indirectReferrals->links('pagination::bootstrap-4')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>



<!-- referral link -->
                <div class="row mt-4">
                    <!-- Left Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"><i class="las la-users"></i> Left Referral</span>
                              </div>

                            <div class="card-body">
                                <div class="referral-link-container">
                                 
                                    <div class="input-group mb-3">
                                        <?php if($activated != 'standard'): ?>
                                            <input type="text" class="form-control" id="leftRefLink" 
                                                value="<?php echo e(url('/register?referral=' . $ref_code . '&side=LEFT')); ?>" readonly>
                                        <?php else: ?>
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        <?php endif; ?>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary copy-btn" type="button" onclick="copyToClipboard('leftRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                            
                            </div>
                        </div>
                    </div>

                    <!-- Right Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"><i class="las la-users"></i> Right Referral</span>
                              </div>
                            <div class="card-body">
                                <div class="referral-link-container">
                                    
                                    <div class="input-group mb-3">
                                        <?php if($activated != 'standard'): ?>
                                            <input type="text" class="form-control" id="rightRefLink" 
                                                value="<?php echo e(url('/register?referral=' . $ref_code . '&side=RIGHT')); ?>" readonly>
                                        <?php else: ?>
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        <?php endif; ?>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


<!-- banners row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 120 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/tm1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 120px; height: 60px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 120 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/tm2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 30%; height: 50%;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--  banner row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm3.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>              
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--  banner row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
        <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm3.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>              
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
        <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/dm2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 468 x 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 440px; height: 60px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 468 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 440px; height: 60px;">
            </div>                
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 728 x 90</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 455px; height: 70px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 728 X 90</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 455px; height: 70px;">
            </div>                
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-12">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 1200 X 500</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn1.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 1200px; height: 120px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-12">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 1200 X 500</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="<?php echo e(asset('image/bn2.png')); ?>" alt="Right Referral Image" class="img-fluid mb-3" style="width: 1200px; height: 120px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<!-- dm cards -->

 <div class="row">
     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="<?php echo e(asset('image/dmcard.png')); ?>" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>

                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>

             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="<?php echo e(asset('image/dm3.png')); ?>" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>

                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>

             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="<?php echo e(asset('image/dm1.png')); ?>" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>


                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="<?php echo e(asset('image/dm2.png')); ?>" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>


                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="<?php echo e(url('/register?referral=' . $ref_code)); ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
             </div>
         </div>
     </div>
 </div>
 

            </div>
        </section>
    </div>
</div>


<style>
.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.copy-btn {
    cursor: pointer;
    transition: all 0.3s;
}

.copy-btn:hover {
    transform: scale(1.05);
}

.referral-stats {
    margin-top: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.stat-item i {
    font-size: 24px;
    margin-right: 10px;
}

.badge {
    padding: 8px 12px;
    border-radius: 4px;
}

.table th {
    border-top: none;
}
</style>

<script>
function copyToClipboard(elementId) {
    if ("<?php echo e($activated); ?>" === "standard") {
        alert('Activate package to use this feature');
        return;
    }
    
    const copyText = document.getElementById(elementId);
    copyText.select();
    document.execCommand("copy");
    
    alert('Referral link copied to clipboard!');
}
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/referral.blade.php ENDPATH**/ ?>
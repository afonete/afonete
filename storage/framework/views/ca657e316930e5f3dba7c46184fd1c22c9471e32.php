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
                        <a href="<?php echo e(route('wallet')); ?>" class="nav-link font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-wallet mr-1"></i> Wallet Address
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('user.kyc')); ?>" class="nav-link active font-weight-bold text-xs py-2 px-3" style="border-radius: 8px;">
                            <i class="fas fa-id-card mr-1"></i> KYC Verification
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php if(session('success')): ?><div class="alert alert-success mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e(session('error')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-danger mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e($errors->first()); ?></div><?php endif; ?>

        
        <div class="card shadow-sm border-0 mb-4 text-white" style="border-radius: 14px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(245, 158, 11, 0.4);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                    <div>
                        <h3 class="font-weight-bold text-white mb-1"><i class="fas fa-id-card text-warning mr-2"></i> Multi-Level KYC Verification</h3>
                        <p class="text-light opacity-90 small mb-0">Complete all 3 verification levels to reach 100% full account verification.</p>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-warning text-dark font-weight-bold px-3 py-1.5 text-uppercase" style="font-size: 0.85rem; border-radius: 6px;">
                            Progress: <?php echo e($kyc->overall_percentage); ?>% Completed
                        </span>
                    </div>
                </div>

                
                <div class="progress mb-2" style="height: 12px; background: rgba(255,255,255,0.15); border-radius: 999px; overflow: hidden;">
                    <div class="progress-bar bg-warning font-weight-bold" role="progressbar" style="width: <?php echo e($kyc->overall_percentage); ?>%;" aria-valuenow="<?php echo e($kyc->overall_percentage); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-flex justify-content-between small text-light opacity-80 mt-1">
                    <span>Level 1: Basic (25%)</span>
                    <span>Level 2: Identity (75%)</span>
                    <span>Level 3: Address (100%)</span>
                </div>

                <?php if($kyc->overall_percentage < 100): ?>
                    <div class="mt-3 p-2.5 rounded bg-dark border border-warning small text-warning d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-radius: 8px !important;">
                        <span><i class="fas fa-info-circle mr-1"></i> Non-KYC Fee Policy: Unverified accounts are subject to a Non-KYC Fee of <strong>$<?php echo e(number_format($kycFee, 2)); ?></strong> on withdrawals. Complete 100% verification to waive fees.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="font-weight-bold text-dark"><i class="fas fa-phone text-primary mr-2"></i> Level 1 – Basic Verification (25%)</span>
                <?php if($kyc->level_1_status === 'approved'): ?>
                    <span class="badge badge-success px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-check-circle mr-1"></i> 25% APPROVED</span>
                <?php elseif($kyc->level_1_status === 'pending'): ?>
                    <span class="badge badge-warning text-dark px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-hourglass-half mr-1"></i> PENDING REVIEW</span>
                <?php elseif($kyc->level_1_status === 'rejected'): ?>
                    <span class="badge badge-danger px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-times-circle mr-1"></i> REJECTED</span>
                <?php else: ?>
                    <span class="badge badge-secondary px-3 py-1.5 font-weight-bold" style="border-radius: 6px;">UNSUBMITTED</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Provide your official active mobile phone number for basic security verification.</p>

                <?php if(!empty($kyc->level_1_admin_notes)): ?>
                    <div class="alert alert-info small p-3 mb-3" style="border-radius: 8px;">
                        <strong>Admin Notes:</strong> <?php echo e($kyc->level_1_admin_notes); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('user.kyc.level1')); ?>" class="row align-items-end">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-8 mb-3 mb-md-0">
                        <label class="font-weight-bold text-dark small">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone_number" value="<?php echo e(old('phone_number', $kyc->phone_number ?: $user->phone)); ?>" required class="form-control font-weight-bold" placeholder="e.g. +1 555 123 4567" style="border-radius: 8px;">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary font-weight-bold btn-block py-2" <?php echo e($kyc->level_1_status === 'approved' ? 'disabled' : ''); ?> style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-1"></i> Submit Level 1
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="font-weight-bold text-dark"><i class="fas fa-id-card text-indigo mr-2"></i> Level 2 – Identity Verification (75%)</span>
                <?php if($kyc->level_2_status === 'approved'): ?>
                    <span class="badge badge-success px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-check-circle mr-1"></i> 75% APPROVED</span>
                <?php elseif($kyc->level_2_status === 'pending'): ?>
                    <span class="badge badge-warning text-dark px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-hourglass-half mr-1"></i> PENDING REVIEW</span>
                <?php elseif($kyc->level_2_status === 'rejected'): ?>
                    <span class="badge badge-danger px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-times-circle mr-1"></i> REJECTED</span>
                <?php else: ?>
                    <span class="badge badge-secondary px-3 py-1.5 font-weight-bold" style="border-radius: 6px;">UNSUBMITTED</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Upload a government-issued ID (Passport, National ID, Driver's License) + Selfie verification photo + Date of Birth.</p>

                <?php if(!empty($kyc->level_2_admin_notes)): ?>
                    <div class="alert alert-info small p-3 mb-3" style="border-radius: 8px;">
                        <strong>Admin Notes:</strong> <?php echo e($kyc->level_2_admin_notes); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('user.kyc.level2')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Government ID Type <span class="text-danger">*</span></label>
                            <select name="id_type" required class="form-control font-weight-bold" style="border-radius: 8px;">
                                <option value="">Select ID Type</option>
                                <option value="passport" <?php echo e(old('id_type', $kyc->id_type) === 'passport' ? 'selected' : ''); ?>>Passport</option>
                                <option value="national_id" <?php echo e(old('id_type', $kyc->id_type) === 'national_id' ? 'selected' : ''); ?>>National ID Card</option>
                                <option value="drivers_license" <?php echo e(old('id_type', $kyc->id_type) === 'drivers_license' ? 'selected' : ''); ?>>Driver's License</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $kyc->date_of_birth ? $kyc->date_of_birth->format('Y-m-d') : '')); ?>" required class="form-control font-weight-bold" style="border-radius: 8px;">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">ID Front Document <span class="text-danger">*</span></label>
                            <input type="file" name="id_front" accept=".jpg,.jpeg,.png,.pdf" <?php echo e($kyc->id_front_path ? '' : 'required'); ?> class="form-control" style="border-radius: 8px;">
                            <?php if($kyc->id_front_path): ?>
                                <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-file-check mr-1"></i> ID Front uploaded</small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">ID Back Document (Optional if Passport)</label>
                            <input type="file" name="id_back" accept=".jpg,.jpeg,.png,.pdf" class="form-control" style="border-radius: 8px;">
                            <?php if($kyc->id_back_path): ?>
                                <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-file-check mr-1"></i> ID Back uploaded</small>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Selfie / Live Face Verification Photo <span class="text-danger">*</span></label>
                            <input type="file" name="selfie" accept=".jpg,.jpeg,.png,.pdf" <?php echo e($kyc->selfie_path ? '' : 'required'); ?> class="form-control" style="border-radius: 8px;">
                            <small class="text-muted d-block mt-1">Upload a clear photo of your face holding your ID document.</small>
                            <?php if($kyc->selfie_path): ?>
                                <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-camera mr-1"></i> Selfie photo uploaded</small>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2" <?php echo e($kyc->level_2_status === 'approved' ? 'disabled' : ''); ?> style="border-radius: 8px;">
                                <i class="fas fa-paper-plane mr-1"></i> Submit Level 2
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="font-weight-bold text-dark"><i class="fas fa-map-marked-alt text-success mr-2"></i> Level 3 – Address Verification (100%)</span>
                <?php if($kyc->level_3_status === 'approved'): ?>
                    <span class="badge badge-success px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-check-circle mr-1"></i> 100% FULLY APPROVED</span>
                <?php elseif($kyc->level_3_status === 'pending'): ?>
                    <span class="badge badge-warning text-dark px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-hourglass-half mr-1"></i> PENDING REVIEW</span>
                <?php elseif($kyc->level_3_status === 'rejected'): ?>
                    <span class="badge badge-danger px-3 py-1.5 font-weight-bold" style="border-radius: 6px;"><i class="fas fa-times-circle mr-1"></i> REJECTED</span>
                <?php else: ?>
                    <span class="badge badge-secondary px-3 py-1.5 font-weight-bold" style="border-radius: 6px;">UNSUBMITTED</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Upload a Utility Bill, Bank Statement, or Government Proof of Residence document to complete 100% full account verification.</p>

                <?php if(!empty($kyc->level_3_admin_notes)): ?>
                    <div class="alert alert-info small p-3 mb-3" style="border-radius: 8px;">
                        <strong>Admin Notes:</strong> <?php echo e($kyc->level_3_admin_notes); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('user.kyc.level3')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Proof of Residence Document Type <span class="text-danger">*</span></label>
                            <select name="address_doc_type" required class="form-control font-weight-bold" style="border-radius: 8px;">
                                <option value="">Select Document Type</option>
                                <option value="utility_bill" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'utility_bill' ? 'selected' : ''); ?>>Utility Bill (Electricity/Water/Gas)</option>
                                <option value="bank_statement" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'bank_statement' ? 'selected' : ''); ?>>Bank Statement</option>
                                <option value="gov_residence_proof" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'gov_residence_proof' ? 'selected' : ''); ?>>Government Residence Certificate</option>
                            </select>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Upload Residence Document <span class="text-danger">*</span></label>
                            <input type="file" name="address_doc" accept=".jpg,.jpeg,.png,.pdf" <?php echo e($kyc->address_doc_path ? '' : 'required'); ?> class="form-control" style="border-radius: 8px;">
                            <?php if($kyc->address_doc_path): ?>
                                <small class="text-success font-weight-bold mt-1 d-block"><i class="fas fa-file-check mr-1"></i> Residence proof document uploaded</small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Full Residential Address <span class="text-danger">*</span></label>
                            <input type="text" name="full_address" value="<?php echo e(old('full_address', $kyc->full_address)); ?>" required placeholder="Street address, building, apartment number" class="form-control" style="border-radius: 8px;">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" value="<?php echo e(old('city', $kyc->city)); ?>" required placeholder="City" class="form-control" style="border-radius: 8px;">
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" value="<?php echo e(old('country', $kyc->country ?: $user->country)); ?>" required placeholder="Country" class="form-control" style="border-radius: 8px;">
                        </div>

                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-success font-weight-bold px-4 py-2" <?php echo e($kyc->level_3_status === 'approved' ? 'disabled' : ''); ?> style="border-radius: 8px;">
                                <i class="fas fa-paper-plane mr-1"></i> Submit Level 3
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
</div>

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/kyc.blade.php ENDPATH**/ ?>
<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper py-4 px-3" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid max-w-7xl mx-auto">

        
        <div class="tabs tab_links mb-4">
            <span class="links_tabs d-flex align-items-center gap-4 border-bottom pb-2">
                <a href="<?php echo e(route('profile.edit')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fas fa-address-card mr-1 text-secondary"></i> My profile
                </a>
                <a href="<?php echo e(route('password.show')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fas fa-lock mr-1 text-secondary"></i> Password
                </a>
                <a href="<?php echo e(route('wallet')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" style="font-size: 0.95rem;">
                    <i class="fas fa-wallet mr-1 text-secondary"></i> wallet address
                </a>
                <a href="<?php echo e(route('user.kyc')); ?>" class="fomoLink font-weight-bold text-dark text-decoration-none" id="tabs" style="font-size: 0.95rem;">
                    <i class="fas fa-id-card mr-1 text-primary"></i> KYC
                </a>
            </span>
        </div>

        <?php if(session('success')): ?><div class="alert alert-success mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('error')): ?><div class="alert alert-danger mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e(session('error')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert alert-danger mt-2 shadow-sm" style="border-radius: 8px;"><?php echo e($errors->first()); ?></div><?php endif; ?>

        
        <?php if($kyc->level_2_status === 'rejected'): ?>
            <div class="alert alert-danger border-danger shadow-sm mb-4" style="border-radius: 12px; background-color: #fef2f2; border-left: 5px solid #ef4444;">
                <h6 class="font-weight-bold text-danger mb-1"><i class="fas fa-times-circle mr-2"></i> Level 2 Identity Documents Rejected</h6>
                <p class="small text-dark mb-1">Your identity documents (ID / Selfie) were rejected during admin review.</p>
                <?php if($kyc->level_2_admin_notes): ?>
                    <div class="small bg-white p-2 rounded border border-danger text-danger font-weight-bold">
                        <i class="fas fa-comment-alt mr-1"></i> Admin Reason: <?php echo e($kyc->level_2_admin_notes); ?>

                    </div>
                <?php endif; ?>
                <p class="small text-muted mt-1 mb-0">Please re-upload clear photos of your ID Front and Live Selfie below to submit a new verification request.</p>
            </div>
        <?php endif; ?>

        <?php if($kyc->level_3_status === 'rejected'): ?>
            <div class="alert alert-danger border-danger shadow-sm mb-4" style="border-radius: 12px; background-color: #fef2f2; border-left: 5px solid #ef4444;">
                <h6 class="font-weight-bold text-danger mb-1"><i class="fas fa-times-circle mr-2"></i> Level 3 Address Document Rejected</h6>
                <p class="small text-dark mb-1">Your proof of residence document was rejected during admin review.</p>
                <?php if($kyc->level_3_admin_notes): ?>
                    <div class="small bg-white p-2 rounded border border-danger text-danger font-weight-bold">
                        <i class="fas fa-comment-alt mr-1"></i> Admin Reason: <?php echo e($kyc->level_3_admin_notes); ?>

                    </div>
                <?php endif; ?>
                <p class="small text-muted mt-1 mb-0">Please re-upload a valid Utility Bill or Bank Statement matching your residential address below.</p>
            </div>
        <?php endif; ?>

        
        <?php if(isset($isProfileComplete) && !$isProfileComplete): ?>
            <div class="alert alert-warning border-warning shadow-sm mb-4" style="border-radius: 12px; background-color: #fffbebf5; border-left: 5px solid #f59e0b;">
                <div class="d-flex align-items-center justify-between flex-wrap gap-3 p-1">
                    <div>
                        <h5 class="font-weight-bold text-warning mb-1" style="color: #b45309 !important;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Profile Incomplete Notice
                        </h5>
                        <p class="text-dark small mb-1">
                            Your profile information must be fully updated and complete before submitting KYC verification.
                        </p>
                        <div class="small text-muted font-weight-bold">
                            Missing Fields: <span class="text-danger"><?php echo e(implode(', ', $missingProfileFields ?? [])); ?></span>
                        </div>
                    </div>
                    <div>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 py-2 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-user-edit mr-1"></i> Update Profile First &rarr;
                        </a>
                    </div>
                </div>
            </div>
        <?php elseif(isset($isProfileComplete) && $isProfileComplete): ?>
            <div class="alert alert-success border-success shadow-sm mb-4" style="border-radius: 12px; background-color: #f0fdf4; border-left: 5px solid #22c55e;">
                <div class="d-flex align-items-center justify-between flex-wrap gap-2 p-1">
                    <div>
                        <span class="font-weight-bold text-success" style="color: #15803d !important;">
                            <i class="fas fa-check-circle mr-2"></i> Profile Status: Complete & Verified (Name, Phone, DOB, Address, City, Country are all set).
                        </span>
                    </div>
                    <div>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-outline-success btn-sm font-weight-bold px-3" style="border-radius: 8px;">
                            <i class="fas fa-eye mr-1"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="card shadow-sm border-0 mb-4 text-white" style="border-radius: 14px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(245, 158, 11, 0.4);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-between flex-wrap gap-3 mb-2">
                    <div>
                        <h4 class="font-weight-bold text-white mb-1"><i class="fas fa-id-card text-warning mr-2"></i> Account KYC Verification</h4>
                        <p class="text-light opacity-90 small mb-0">Admin will inspect and verify your profile information against uploaded documents.</p>
                    </div>
                    <div class="text-right">
                        <?php if($kyc->overall_percentage === 100): ?>
                            <span class="badge badge-success px-3 py-2 font-weight-bold text-uppercase" style="font-size: 0.85rem; border-radius: 6px;">
                                <i class="fas fa-check-circle mr-1"></i> Address Verification (100% Fully Verified)
                            </span>
                        <?php elseif($kyc->level_2_status === 'approved'): ?>
                            <span class="badge badge-info px-3 py-2 font-weight-bold text-uppercase" style="font-size: 0.85rem; border-radius: 6px;">
                                <i class="fas fa-user-check mr-1"></i> Identity Verification (50% Verified)
                            </span>
                        <?php elseif($kyc->status === 'pending'): ?>
                            <span class="badge badge-warning text-dark px-3 py-2 font-weight-bold text-uppercase" style="font-size: 0.85rem; border-radius: 6px;">
                                <i class="fas fa-hourglass-half mr-1"></i> Pending Admin Verification
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary px-3 py-2 font-weight-bold text-uppercase" style="font-size: 0.85rem; border-radius: 6px;">
                                Status: <?php echo e($kyc->overall_percentage); ?>% Unverified
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($kyc->overall_percentage < 100): ?>
                    <div class="mt-2 p-2.5 rounded bg-dark border border-warning small text-warning d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-radius: 8px !important;">
                        <span><i class="fas fa-info-circle mr-1"></i> Withdrawals above <strong>$5,000</strong> require 100% KYC verification.</span>
                    </div>
                <?php else: ?>
                    <div class="mt-2 p-2.5 rounded bg-dark border border-success small text-success d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-radius: 8px !important;">
                        <span><i class="fas fa-check-circle mr-1"></i> <strong>100% KYC Verified Account:</strong> Withdrawals above $5,000 are fully unlocked!</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white py-4 px-4 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="font-weight-bold text-dark text-center mb-4" style="font-size: 1.5rem;">Personal Info</h2>

            <form action="<?php echo e(route('user.kyc.submit')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">
                    
                    <div class="col-md-6 col-sm-12 border-right pr-md-4">
                        <?php
                            $names = explode(' ', $user->name, 2);
                            $firstName = $names[0] ?? $user->name;
                            $lastName = $names[1] ?? '';

                            $dobRaw = old('dob');
                            if (!$dobRaw) {
                                if (!empty($kyc->date_of_birth)) {
                                    $dobRaw = $kyc->date_of_birth;
                                } elseif (!empty($user->dob)) {
                                    $dobRaw = $user->dob;
                                }
                            }
                            if ($dobRaw) {
                                if ($dobRaw instanceof \DateTimeInterface) {
                                    $dobFormatted = $dobRaw->format('Y-m-d');
                                } else {
                                    try {
                                        $dobFormatted = \Carbon\Carbon::parse($dobRaw)->format('Y-m-d');
                                    } catch (\Throwable $e) {
                                        $dobFormatted = (string) $dobRaw;
                                    }
                                }
                            } else {
                                $dobFormatted = '';
                            }
                        ?>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" value="<?php echo e(old('first_name', $firstName)); ?>" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" value="<?php echo e(old('last_name', $lastName)); ?>" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" value="<?php echo e($dobFormatted); ?>" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" value="<?php echo e(old('phone', $kyc->phone_number ?: $user->phone)); ?>" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Email Address</label>
                                <input type="email" name="email" value="<?php echo e($user->email); ?>" class="form-control font-weight-bold" readonly style="background:#f8fafc; border-radius: 8px;">
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address" value="<?php echo e(old('address', $kyc->full_address ?: $user->address)); ?>" placeholder="Street address" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label font-weight-bold small text-dark">City <span class="text-danger">*</span></label>
                                <input type="text" name="city" value="<?php echo e(old('city', $kyc->city ?: $user->city)); ?>" placeholder="City" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold small text-dark">Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" value="<?php echo e(old('country', $kyc->country ?: $user->country)); ?>" placeholder="Country" class="form-control font-weight-bold" required style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-6 col-sm-12 pl-md-4">

                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label font-weight-bold small text-dark mb-1">ID Document Type <span class="text-danger">*</span></label>
                                <select name="id_type" class="form-control font-weight-bold text-xs" style="border-radius: 8px;" required>
                                    <option value="">Select ID Document Type</option>
                                    <option value="passport" <?php echo e(old('id_type', $kyc->id_type) === 'passport' ? 'selected' : ''); ?>>Passport</option>
                                    <option value="national_id" <?php echo e(old('id_type', $kyc->id_type) === 'national_id' ? 'selected' : ''); ?>>National ID Card</option>
                                    <option value="drivers_license" <?php echo e(old('id_type', $kyc->id_type) === 'drivers_license' ? 'selected' : ''); ?>>Driver's License</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4 text-center mb-2 mb-md-0">
                                <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Sample" class="img-fluid rounded border shadow-sm" style="max-height: 90px;">
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <p class="text-dark font-weight-bold small mb-0">National Identity (Front)</p>
                                    <?php if($kyc->level_2_status === 'approved'): ?>
                                        <span class="badge badge-success text-xs"><i class="fas fa-check-circle"></i> Approved</span>
                                    <?php elseif($kyc->level_2_status === 'pending'): ?>
                                        <span class="badge badge-warning text-dark text-xs"><i class="fas fa-hourglass-half"></i> Pending Review</span>
                                    <?php elseif($kyc->level_2_status === 'rejected'): ?>
                                        <span class="badge badge-danger text-xs"><i class="fas fa-times-circle"></i> Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <div class="drag-drop-box p-3 text-center rounded border border-dashed" style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 10px;">
                                    <span class="text-muted small d-block mb-1">Upload ID Front (.JPG, .PNG, .PDF)</span>
                                    <input type="file" name="id_front" class="form-control-file text-xs" accept="image/*,.pdf">
                                    <?php if($kyc->id_front_path): ?>
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i> Document Uploaded</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row align-items-center mb-3">
                            <div class="col-md-4 text-center mb-2 mb-md-0">
                                <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Sample" class="img-fluid rounded border shadow-sm" style="max-height: 90px;">
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <p class="text-dark font-weight-bold small mb-0">National Identity (Back)</p>
                                    <?php if($kyc->level_2_status === 'approved'): ?>
                                        <span class="badge badge-success text-xs"><i class="fas fa-check-circle"></i> Approved</span>
                                    <?php elseif($kyc->level_2_status === 'pending'): ?>
                                        <span class="badge badge-warning text-dark text-xs"><i class="fas fa-hourglass-half"></i> Pending Review</span>
                                    <?php elseif($kyc->level_2_status === 'rejected'): ?>
                                        <span class="badge badge-danger text-xs"><i class="fas fa-times-circle"></i> Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <div class="drag-drop-box p-3 text-center rounded border border-dashed" style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 10px;">
                                    <span class="text-muted small d-block mb-1">Upload ID Back (.JPG, .PNG, .PDF)</span>
                                    <input type="file" name="id_back" class="form-control-file text-xs" accept="image/*,.pdf">
                                    <?php if($kyc->id_back_path): ?>
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i> Document Uploaded</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row align-items-center mb-3">
                            <div class="col-12 mb-1">
                                <small class="text-muted d-block">Note: Maximum file size 5MB. Accepted formats .JPG, .JPEG, .PNG. Photo Selfie</small>
                            </div>
                            <div class="col-md-4 text-center mb-2 mb-md-0">
                                <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Sample" class="img-fluid rounded border shadow-sm" style="max-height: 90px;">
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <p class="text-dark font-weight-bold small mb-0">Photo Selfie (Face Verification)</p>
                                    <?php if($kyc->level_2_status === 'approved'): ?>
                                        <span class="badge badge-success text-xs"><i class="fas fa-check-circle"></i> Approved</span>
                                    <?php elseif($kyc->level_2_status === 'pending'): ?>
                                        <span class="badge badge-warning text-dark text-xs"><i class="fas fa-hourglass-half"></i> Pending Review</span>
                                    <?php elseif($kyc->level_2_status === 'rejected'): ?>
                                        <span class="badge badge-danger text-xs"><i class="fas fa-times-circle"></i> Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <div class="drag-drop-box p-3 text-center rounded border border-dashed" style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 10px;">
                                    <span class="text-muted small d-block mb-1">Upload Live Selfie Photo</span>
                                    <input type="file" name="selfie" class="form-control-file text-xs" accept="image/*,.pdf">
                                    <?php if($kyc->selfie_path): ?>
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i> Selfie Uploaded</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row align-items-center mb-3">
                            <div class="col-12 mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label font-weight-bold small text-dark mb-0">Proof of Address Document Type <span class="text-danger">*</span></label>
                                    <?php if($kyc->level_3_status === 'approved'): ?>
                                        <span class="badge badge-success text-xs"><i class="fas fa-check-circle"></i> Approved</span>
                                    <?php elseif($kyc->level_3_status === 'pending'): ?>
                                        <span class="badge badge-warning text-dark text-xs"><i class="fas fa-hourglass-half"></i> Pending Review</span>
                                    <?php elseif($kyc->level_3_status === 'rejected'): ?>
                                        <span class="badge badge-danger text-xs"><i class="fas fa-times-circle"></i> Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <select name="address_doc_type" class="form-control font-weight-bold mb-2 text-xs" style="border-radius: 8px;">
                                    <option value="utility_bill" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'utility_bill' ? 'selected' : ''); ?>>Utility Bill (Electricity, Water, Gas, Internet)</option>
                                    <option value="bank_statement" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'bank_statement' ? 'selected' : ''); ?>>Bank Statement / Credit Card Statement</option>
                                    <option value="gov_residence_proof" <?php echo e(old('address_doc_type', $kyc->address_doc_type) === 'gov_residence_proof' ? 'selected' : ''); ?>>Government Residence Certificate / Tax Proof</option>
                                </select>
                            </div>
                            <div class="col-md-4 text-center mb-2 mb-md-0">
                                <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Sample" class="img-fluid rounded border shadow-sm" style="max-height: 90px;">
                            </div>
                            <div class="col-md-8">
                                <p class="text-dark font-weight-bold small mb-1">Proof of Address Document File</p>
                                <div class="drag-drop-box p-3 text-center rounded border border-dashed" style="border: 2px dashed #cbd5e1; background: #f8fafc; border-radius: 10px;">
                                    <span class="text-muted small d-block mb-1">Upload Residence Proof (.JPG, .PNG, .PDF)</span>
                                    <input type="file" name="address_doc" class="form-control-file text-xs" accept="image/*,.pdf">
                                    <?php if($kyc->address_doc_path): ?>
                                        <small class="text-success font-weight-bold d-block mt-1"><i class="fas fa-check-circle mr-1"></i> Address Document Uploaded</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-center mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary font-weight-bold px-5 py-2.5 shadow-sm" style="border-radius: 8px; font-size: 1rem;">
                        <i class="fas fa-paper-plane mr-1"></i> Submit KYC
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/kyc.blade.php ENDPATH**/ ?>
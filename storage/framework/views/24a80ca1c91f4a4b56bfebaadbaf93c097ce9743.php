<div class="wrapper">

    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="content-wrapper">
        <div class="container-fluid py-4 px-4">
            
            
            <?php if(session('message')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle mr-2"></i><?php echo e(session('message')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            
            <div class="card shadow-sm border-0 mb-4" style="background:#1F2937; color:#fff; border-radius:10px;">
                <div class="card-header bg-dark text-white font-weight-bold d-flex justify-between align-items-center py-3">
                    <span style="font-size:1.1rem;"><i class="fas fa-key text-warning mr-2"></i>Package Activation Code</span>
                    <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-sm btn-outline-warning font-weight-bold">
                        <i class="fas fa-shopping-cart mr-1"></i>Buy More Packages
                    </a>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-3">
                        Enter your 17-character activation code (e.g., <code class="text-warning font-weight-bold">FOM-A8B9C3D2E4F5G</code>) to activate your package and release your Total Return tokens into your Escrow Wallet.
                    </p>

                    <form action="<?php echo e(route('user.dashboard.validate')); ?>" method="POST" class="max-w-md mx-auto">
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="code_input" class="font-weight-bold text-light mb-2">Activation Code (17 characters):</label>
                            <input type="text" id="code_input" name="code" class="form-control form-control-lg text-center font-weight-bold text-warning bg-dark border-secondary" placeholder="Enter 17-char code (e.g. FOM-A8B9C3D2E4F5G)" required style="letter-spacing:1px;">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg font-weight-bold px-5 py-2 shadow">
                                <i class="fas fa-bolt mr-2"></i>Activate Code Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="card shadow-sm border-0 mb-4" style="background:#1F2937; color:#fff; border-radius:10px;">
                <div class="card-header bg-dark text-white font-weight-bold py-3 d-flex justify-content-between align-items-center">
                    <span style="font-size:1.1rem;"><i class="fas fa-list-alt text-info mr-2"></i>My Purchased Activation Codes</span>
                    <span class="badge badge-info px-3 py-2 font-weight-bold"><?php echo e(count($myCodes ?? [])); ?> Total Codes</span>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($myCodes) || count($myCodes) === 0): ?>
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-ticket-alt text-secondary mb-2 d-block" style="font-size:2rem;"></i>
                            You have not purchased or generated any activation codes yet.
                            <div class="mt-2">
                                <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-sm btn-primary font-weight-bold">
                                    Browse Packages &amp; Buy Codes
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0 text-center align-middle" style="background:transparent;">
                                <thead class="bg-dark text-muted uppercase text-xs">
                                    <tr>
                                        <th class="py-3">#</th>
                                        <th class="py-3">Activation Code</th>
                                        <th class="py-3">Package</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3">Total Return</th>
                                        <th class="py-3">Activated By User</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $myCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $actCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isUsed = in_array(strtolower(trim((string)$actCode->stutus)), ['used', 'activated', '1', 'true']);
                                            $numReturn = \App\Models\FomLicenceMiner::cleanNum($actCode->token);
                                            $tokenSymbol = \App\Models\TokenSetting::currentSymbol();
                                        ?>
                                        <tr class="border-bottom border-secondary">
                                            <td class="align-middle text-muted"><?php echo e($loop->iteration); ?></td>
                                            <td class="align-middle">
                                                <code class="text-warning font-weight-bold bg-black px-2 py-1 rounded" id="user-code-<?php echo e($actCode->id); ?>"><?php echo e($actCode->code); ?></code>
                                                <button type="button" class="btn btn-xs btn-outline-warning ml-2 font-weight-bold" onclick="copyCodeText('user-code-<?php echo e($actCode->id); ?>')">
                                                    <i class="fas fa-copy"></i> Copy
                                                </button>
                                            </td>
                                            <td class="align-middle font-weight-bold text-white"><?php echo e(strtoupper($actCode->package)); ?></td>
                                            <td class="align-middle text-success font-weight-bold">$<?php echo e(number_format(\App\Models\FomLicenceMiner::cleanNum($actCode->price), 2)); ?></td>
                                            <td class="align-middle font-weight-bold text-info">
                                                <?php if($isUsed): ?>
                                                    <?php echo e($numReturn > 0 ? number_format($numReturn) . ' ' . $tokenSymbol : 'N/A'); ?>

                                                <?php else: ?>
                                                    <span class="text-muted small font-italic">Hidden until Activated</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if($isUsed): ?>
                                                    <?php if($actCode->redeemer): ?>
                                                        <span class="text-info font-weight-bold">@ <?php echo e($actCode->redeemer->user); ?></span>
                                                    <?php else: ?>
                                                        <span class="text-info font-weight-bold"><?php echo e($actCode->email); ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted small font-italic">Unused - Ready to Share</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if($isUsed): ?>
                                                    <span class="badge badge-secondary px-2 py-1">Used / Activated</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success px-2 py-1">Unused / Ready</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if(!$isUsed): ?>
                                                    <form action="<?php echo e(route('user.investment-package.activate-code')); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="activation_id" value="<?php echo e($actCode->id); ?>">
                                                        <input type="hidden" name="code" value="<?php echo e($actCode->code); ?>">
                                                        <button type="submit" class="btn btn-sm btn-success font-weight-bold shadow-sm">
                                                            <i class="fas fa-bolt mr-1"></i>Activate Code
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted small"><i class="fas fa-check-circle text-muted mr-1"></i>Redeemed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($myCodes->hasPages()): ?>
                            <div class="card-footer bg-dark border-top border-secondary d-flex justify-content-center py-3">
                                <?php echo e($myCodes->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function copyCodeText(elementId) {
        var codeElement = document.getElementById(elementId);
        if (!codeElement) return;
        var codeText = codeElement.innerText;
        navigator.clipboard.writeText(codeText).then(function() {
            alert("Activation code copied to clipboard: " + codeText);
        }).catch(function(err) {
            var tempInput = document.createElement("input");
            tempInput.value = codeText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Activation code copied to clipboard: " + codeText);
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/activation-controller.blade.php ENDPATH**/ ?>
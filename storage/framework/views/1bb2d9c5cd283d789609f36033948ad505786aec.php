<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid py-4 px-4">
                
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="page-h-content font-weight-bold text-white mb-0">
                        <i class="fas fa-history text-warning mr-2"></i>My Package History &amp; Activation Codes
                    </h3>
                    <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-shopping-cart mr-1"></i>Buy Package Codes
                    </a>
                </div>

                
                <div class="card bg-dark text-white border-secondary mb-4 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block small">Current Active Account Package</span>
                            <span class="fs-4 font-weight-bold text-success"><?php echo e(strtoupper($user->has_paid_package ?? 'Free')); ?></span>
                        </div>
                        <div>
                            <a href="<?php echo e(route('user.dashboard.activate')); ?>" class="btn btn-outline-info btn-sm font-weight-bold">
                                <i class="fas fa-key mr-1"></i>Activate Code Page
                            </a>
                        </div>
                    </div>         
                </div>

                
                <div class="card shadow-sm border-0 mb-4" style="background:#1F2937; color:#fff; border-radius:10px;">
                    <div class="card-header bg-dark text-white font-weight-bold py-3 d-flex justify-content-between align-items-center">
                        <span style="font-size:1.1rem;"><i class="fas fa-list text-warning mr-2"></i>Purchased Activation Codes List</span>
                        <span class="badge badge-warning px-3 py-2 font-weight-bold text-dark"><?php echo e(count($myCodes ?? [])); ?> Codes</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if(empty($myCodes) || count($myCodes) === 0): ?>
                            <div class="text-center p-4 text-muted">
                                <i class="fas fa-ticket-alt text-secondary mb-2 d-block" style="font-size:2rem;"></i>
                                No activation codes found for this account.
                                <div class="mt-2">
                                    <a href="<?php echo e(route('investment-package')); ?>" class="btn btn-sm btn-warning font-weight-bold text-dark">
                                        Browse &amp; Purchase Package Codes
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
                                            <th class="py-3">Status</th>
                                            <th class="py-3">Action / Share</th>
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
                                                    <code class="text-warning font-weight-bold bg-black px-2 py-1 rounded" id="hist-code-<?php echo e($actCode->id); ?>"><?php echo e($actCode->code); ?></code>
                                                    <button type="button" class="btn btn-xs btn-outline-warning ml-2 font-weight-bold" onclick="copyHistCode('hist-code-<?php echo e($actCode->id); ?>')">
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
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script>
    function copyHistCode(elementId) {
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
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/package-history.blade.php ENDPATH**/ ?>
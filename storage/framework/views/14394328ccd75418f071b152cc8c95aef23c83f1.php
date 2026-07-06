<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper" style="background:#f4f6f9; min-height:100vh;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1>My Contract</h1>
                    <p class="text-muted mb-0">Bifonex Contract</p>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if(!$contract): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            No signed Bifonex contract was found for your account yet. Once you sign the contract, it will appear here.
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">Bifonex Contract</h3>
                            <small class="text-muted">Signed on <?php echo e($contract->created_at ? $contract->created_at->format('Y-m-d H:i') : '—'); ?></small>
                        </div>
                        <span class="badge badge-success">SIGNED</span>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            You can view your signed contract here inside the system. Downloading is not provided for user accounts.
                        </div>
                        <div style="border:1px solid #d1d5db; border-radius:10px; overflow:hidden; background:#111827;">
                            <iframe
                                title="Bifonex Contract PDF"
                                src="data:application/pdf;base64,<?php echo e($pdfBase64); ?>#toolbar=0&navpanes=0&scrollbar=1"
                                style="width:100%; height:82vh; border:0; background:#fff;"
                                sandbox="allow-same-origin"
                                oncontextmenu="return false;">
                            </iframe>
                        </div>
                        <div class="small text-muted mt-2">
                            Contract ID: #<?php echo e($contract->id); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/contracts/user-bifonex-contract.blade.php ENDPATH**/ ?>
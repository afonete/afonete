

<div class="wrapper">
 <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
body {
  font-family: "Poppins", sans-serif;
  font-weight: 400;
  font-style: normal;
  }

</style>
 <div class="content-wrapper">
    <div class="container-fluid">
        <div class="row mb-2">

           <div class="col-md-12">

            <div class="card-header bg-white my-3 ">
                <h3 class="text-dark">Deposits Transactions</h3>
            </div>
                <div class="table-responsive bg-white rounded shadow-sm">
<a class="btn btn-primary  m-2 text-light" href="<?php echo e(route("user.payment.deposits")); ?>" style="color:white !important">New Deposit</a>
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deposit Amount</th>
                                <th>Method</th>
                                <th>Deposit Date</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($deposit->amount_deposited); ?></td>
                                    <td><?php echo e($deposit->deposit_method); ?></td>
                                    <td><?php echo e($deposit->created_at->format('Y-m-d h:i A')); ?></td>

                                    <td>
                                        <?php if($deposit->status == 'pending'): ?>
                                        <span class="badge badge-warning px-2 py-1 text-white"><?php echo e(ucfirst($deposit->status)); ?></span>
                                    <?php elseif($deposit->status == 'approved'): ?>
                                        <span class="badge badge-success px-2 py-1"><?php echo e(ucfirst($deposit->status)); ?></span>
                                    <?php elseif($deposit->status == 'rejected'): ?>
                                        <span class="badge badge-danger px-2 py-1"><?php echo e(ucfirst($deposit->status)); ?></span>

                                    <?php elseif($deposit->status == 'cancelled'): ?>
                                    <span class="badge badge-danger px-2 py-1"><?php echo e(ucfirst($deposit->status)); ?></span>
                                <?php else: ?>

                                        <span class="badge badge-secondary px-2 py-1"><?php echo e(ucfirst($deposit->status)); ?></span>
                                    <?php endif; ?>
                                    </td>

                                    <td>
                                        <button class="btn btn-danger btn-sm">Report Issue</button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>


                </div>
           </div>

        <div>
     </div>
 </div>




<script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/sparklines/sparkline.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('assets/a/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/adminlte.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/adminlte.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard2.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/tree.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/jquery-knob/jquery.knob.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard3.js')); ?>"></script>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/Mypayments.blade.php ENDPATH**/ ?>
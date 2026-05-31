

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

                <div class="min-vh-100 d-flex align-items-center justify-content-center">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h1 class="text-info">FREE ACCOUNT</h1>
                            <h2 class="card-title text-danger">Unauthorized Access</h2>

                            <p class="card-text text-muted py-2">
                                You have a free account and cannot view this page due to membership restrictions.
                            </p>
                            <div class="btn btn-group">
                                <a href="javascript:history.back()" class="btn btn-primary mt-3">
                                    Go Back
                                </a>
                                <a href="<?php echo e(route('user.venture')); ?>" class="btn btn-success mx-2 mt-3">
                                    Upgrade Account
                                </a>
                            </div>
                        </div>
                    </div>
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
<?php /**PATH C:\xampp\htdocs\KANANI\BIFONEX\mcu.focoin.eu\resources\views/user/unauthorized-free-account.blade.php ENDPATH**/ ?>
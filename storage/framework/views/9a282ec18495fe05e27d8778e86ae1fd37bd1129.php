

<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
 <div class="container-fluid">
      <div class="tabs tab_links" >
         <span class="links_tabs d-flex ">
           <a href="<?php echo e(route('user.dashboard.payclick')); ?>" class="fomoLink" id="tabs"> <i class="fa-solid fa-hand-point-up"></i> Pay clicks</a>
           <a href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="fomoLink"><i class="fa-solid fa-video"></i> Watch Videos</a>
         </span>

         <a  href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="fomoLink" ><i class="fa-solid fa-download"></i> Download App <i style="color: red">Coming soon</i></a>
         <a  href="<?php echo e(route('user.dashboard.payvideo.invoice')); ?>"
         class="fomoLink" ><i class="fa-solid fa-book"></i> Invoices </a>


     </div>

 </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
 <div class="container-fluid">

   <div class="row">
    <div class="card p-4 mx-auto my-5 shadow-lg" style="min-width: 600px;">
        <small class="fw-bold ">Answer this question related to this ads</small>
        <h1 class="h4 font-weight-bold mb-4 text-left"> <?php echo e($question); ?>?</h1>



            <?php if(session('error')): ?>
                <div style="background: brown;padding:5px;border-radius:5px; color:aliceblue">
                    <strong><?php echo e(session('error')); ?></strong>
                </div>
             <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <strong><?php echo e(session('success')); ?></strong>
            </div>
         <?php endif; ?>
        <form method="post" id="form" action="<?php echo e(route('user.claim.reward')); ?>">
            <?php echo csrf_field(); ?>




            <div class="form-group">
                <label for="answer">Your Answerd:</label>
                <input type="hidden" name="id" value="<?php echo e($id); ?>">
                <input type="text" id="answer" name="answer" placeholder="Enter your answer" class="form-control" required>
            </div>

            <div class="d-flex flex-column justify-content-between">
                <button type="submit" class="btn btn-primary w-100">Submit Answer</button>
                <small class="text-muted mt-2">Earn money by viewing advertisements</small>
            </div>
        </form>

    </div>


   </div>
   <!-- /.row -->

     <!-- Main row -->
 </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.row -->

</div>
</div>


<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/fearloss/asking.blade.php ENDPATH**/ ?>
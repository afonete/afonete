

     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Content Wrapper. Contains page content -->
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
                    <?php

if (count($results)) {
  foreach ($results as $row) {


        ?>
            <div class="col-md-4">
            <div class="card card-primary card-outline">
            <a href="">
                     <div class="card-header">
                <h5 class="m-0">  <?php if($row->url!=NULL): ?>
                 <a style="color: #000; font-weight:bold;" target="__blank"  href="<?php echo e(route('user.url')); ?>?url=<?php echo e($row->url); ?>&ads=<?php echo e($row->id); ?>"><?php echo e($row->tittle); ?></a>
                  <?php endif; ?>
                  <!--  -->
                <?php if($row->url==NULL): ?>
                <a style="color: #007bff" target="__blank" href="<?php echo e(route('user.click')); ?>?ads=<?php echo e($row->id); ?>"><?php echo e($row->tittle); ?></a>

                  <?php endif; ?>

                 </h5>
                    </div>



            <div class="overflow-auto" style="height: 220px">
              <?php if($row->banner!=NULL ): ?>

               <!-- <div class="card-body click-title" id="mmm"> -->
                <?php
                    echo "<div class='card-body click-title' style='height: 100%;background-repeat: round;background:url(".asset('ads/'.$row->banner).")'>";
                 ?>
            <span>
                <?php endif; ?>

                     <?php if($row->banner==NULL ): ?>
                     <div class="card-body click-title h-100 d-flex justify-content-center align-items-center ">
                     <div style="height: 80%;width:100%; font-size: 18px" class="d-flex align-items-center justify-content-center"><?php echo e($row->description); ?></div>
                <?php endif; ?>
             </span>
           </div>
      </div>

       <h5 class="text-center py-3">
            <?php if($row->url!=NULL): ?>
                     <a style="color: #000" target="__blank" href="<?php echo e(route('user.url')); ?>?url=<?php echo e($row->url); ?>&ads=<?php echo e($row->id); ?>">View for 10 sec and Earn free $ </a>
              <?php endif; ?>
            <?php if($row->url==NULL): ?>
              <a style="color: #000" target="__blank" href="<?php echo e(route('user.click')); ?>?ads=<?php echo e($row->id); ?>">
                View for 10 sec and Earn free $ </a>
            <?php endif; ?>
        </h5>

                        </a>

            </div>

          </div>
       <?php

      }
}
else{
    echo "<span class='btn btn-primary'>No Ads Availlable. come back later</span>";
}
         ?>           <!-- END OF click column -->



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
<?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/user/fearloss/payclick.blade.php ENDPATH**/ ?>
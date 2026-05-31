   <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;


    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
    ?>

 <div>
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Content Wrapper. Cont
    <!-- Content Header (Page header) -->
    <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="tabs tab_links" >
              <span class="links_tabs d-flex ">
                <a href="<?php echo e(route('user.dashboard.payclick')); ?>" class="fomoLink" id="tabs"> <i class="fa-solid fa-hand-point-up"></i> Pay clicks</a>
                <a href="#" class="fomoLink"><i class="fa-solid fa-video"></i> Watch Videos</a>
              </span>
             
              <a  href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="fomoLink" ><i class="fa-solid fa-download"></i> Download App <i style="color: red">Coming soon</i></a>

              <!-- <ul class="nav nav-tabs">
              <li class="active"><a  href="<?php echo e(route('user.dashboard.payclick')); ?>">Pay clicks</a></li>
              <li><a  href="<?php echo e(route('user.dashboard.payvideo')); ?>">Watch Videos</a></li>
              <li><a  href="#">Download App <i style="color: red">Coming soon</i></a></li>
               -->
          </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" >
      <div class="container">
      <div class="row" >
        <?php 

$results=db::SELECT("SELECT * from videos where status='approved' order by id desc");
if (count($results)) {
  foreach ($results as $row) {
        ?>
        <div class="col-md-4" >
           
            <div class="card-body " >
              <div class="info card-header ">
  
                  <center>
                    <div class="text-center ">
                      <h4 class="font-size-20 font-weight-600">

 <a style="color: #007bff" target="__blank" href="<?php echo e(route('user.watch')); ?>?video=<?php echo e($row->id); ?>"><?php echo e($row->tittle); ?></a>
  </h4>
                 <p style="color:black;">
                    <?php 
$view=db::SELECT("SELECT * from videos where id='$row->id' ");

                 ?><br>
                   
                 </p>
             
              </div>
                    <hr>
                   
                 
                <!-- /.info-box-content -->
              <div style="height: 150;width: 100%;">

                  <?php 
                  echo " <video src='".asset('video/'.$row->video)."' style='width:100%;height:100%;' style='margin-top:-80px' >";
                  ?>     
                 <!-- <p> <?php echo e($row->description); ?> </p> -->
                 
              
              </div>

                <div class="bg-info container-fluid">
                  <div class="col-md-12">
                    <center>
    <!-- <br>       <div class="alert alert-info"> -->
<!-- <a href="<?php echo e(route('user.dashboard.ptc')); ?>?id=<?php echo e($row->id); ?>"> View</a> -->
<br>
  <a style="color:white;text-decoration: underline;" href="<?php echo e(route('user.watch')); ?>?video=<?php echo e($row->id); ?>">
  Click to watch this video for <?php echo e($row->time); ?> sec  and get paid 0.1$</a>
  
        <!-- </div> -->
</center>
                    <br>
                  </div>
                </div>
              </div>
            </div>
         
         
        </div>
        <?php

      }
}
else{
    echo "<span class='btn btn-primary'>No videos availlable. come back later  </span>";
}
         ?>

         <script>
    
  </script>
<!-- end of col -->

       <div>
        </div>    
      </div>  
    </div> 
        </div>  


<!-- /.container-fluid -->
      </div>
    </div>
    <!-- /.content -->
    <!-- /.row -->

  </div>
  </div>
 </div>
  <?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/fearloss/videos.blade.php ENDPATH**/ ?>
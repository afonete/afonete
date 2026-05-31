   <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;


    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
    ?>
     <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
         <ul class="nav nav-tabs">
            <li ><a  href="<?php echo e(route('user.dashboard.ptc')); ?>">Create new Click View</a></li>
            <li class="active"><a  href="<?php echo e(route('user.dashboard.myptc')); ?>">My Click view</a></li>
            <li ><a  href="<?php echo e(route('user.dashboard')); ?>">Deposit</a></li>
          </ul>
          
          <!-- <center>
          <h3>My Click View </h3></center> -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
      <div class="row" >
        <?php 
          $results=db::SELECT("SELECT * from ads where user='$name'");
          if (count($results)) {
            foreach ($results as $row) {
        ?>
        <div class="col-md-4 border shadow rounded" >
           
            <div class="" >
              <div class="">
                <div class="text-center ">
                  <h4 class="font-size-20 font-weight-600">
                     <?php if($row->url!=NULL): ?>
                      <a style="color: #1565c0" href=" <?php echo e($row->url); ?>"><?php echo e($row->tittle); ?> </a>
                     <?php endif; ?> 
                     <?php if($row->url==NULL): ?>
                      <a style="color: yellow" href="?ads=<?php echo e($row->id); ?>">
                        <?php echo e($row->tittle); ?></a>
                    <?php endif; ?>   
                   </h4>
                   <p style="color:black;">
                      <?php 
                      $view=db::SELECT("SELECT * from ads where id='$row->id' ");
                        foreach ($view as $views) {
                      $view=$views->reached_views>0?$views->reached_views:'0';
                          // $remaining=$views->targeted_views-$views->reached_views;
                      echo "Targeted_view: $views->targeted_views reached_view: ".$view;
                      echo "<br>Created at $views->created_at";
                      } 
                      global $status;
                      if($row->status=='pending'){
                          $status="Pending, Waiting for approval";
                      }
                       elseif($row->status=='approved'){
                          $status="Approved, Enjoy your ads";
                      }
                      else{
                          $status="Declained, your ads does not match with our standard policy";
                      }
                      
                         ?><br>
                         <span class="bg bg-secondary">Status: <?php echo e($status); ?></span>
                       </p>
                   
                    </div>
         
                      <!-- /.info-box-content -->
                    <div style="height: 200px;width: 100%; font-size: 18px;" class="d-flex justify-content-center align-items-center bg-secondary">

                      <?php if($row->banner!=NULL ): ?>
                          <?php 
                          echo " <img src='".asset('ads/'.$row->banner)."' style='width:100%;height:100%;' style='margin-top:-20px'>";
                          ?>     
                         <!-- <p> <?php echo e($row->description); ?> </p> -->
                         
                          <?php endif; ?>
                          <?php if($row->banner==NULL): ?>

                              <p><?php echo e($row->description); ?></p>

                      <?php endif; ?>
                    </div>

      <div class="bg-info p-1 d-flex justify-content-center">
          <div class="btn btn-warning">
            <!-- <a href="<?php echo e(route('user.dashboard.ptc')); ?>?id=<?php echo e($row->id); ?>"> View</a> -->
            <?php if($row->url!=NULL): ?>
              <a href=" <?php echo e(route('user.url')); ?>?url=<?php echo e($row->url); ?>">View
            </a>
              <?php endif; ?> 
              <?php if($row->url==NULL): ?>
              <a href="<?php echo e(route('user.click')); ?>?ads=<?php echo e($row->id); ?>">
                View</a>
                <?php endif; ?>
          </div>
          <div class="btn btn-danger mx-2" >
                <a href="<?php echo e(route('user.dashboard.ptc')); ?>?ads=<?php echo e($row->id); ?>"> Edit</a>
          </div>
    </div>
  </div>
</div>

         
        </div>
            <?php

              }
                }
                else{
                    echo "<span class='btn btn-primary'>No videos you have uploaded. click to upload </span>";
                }
             ?>
          <div>
        </div>    
      </div>  
    </div> 
  </div>  


<!-- /.container-fluid -->
</div><?php /**PATH /home/cwkwkueb/test.focoin.eu/resources/views/user/project/myptc.blade.php ENDPATH**/ ?>
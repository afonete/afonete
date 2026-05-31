   <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;


    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
    ?>

 <div>
     @include('user.user-dashboard-base')


        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  style="width: 80%;">
    <br><br>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
     <ul class="nav nav-tabs">
             <li class="active"><a  href="{{route('user.dashboard.video')}}">Create new Video ads||</a></li>
            <li ><a  href="{{route('user.dashboard.video')}}">My Video ads||</a></li>
            <li ><a  href="{{route('user.dashboard')}}">Deposit</a></li>
          </ul>
          
          </ul> 
          <!-- <center>
          <h3>My Click View </h3></center> -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" style="margin-left: 50px;">
      <div class="container">
      <div class="row" >
        <?php 

$results=db::SELECT("SELECT * from videos where user='$name'");
if (count($results)) {
  foreach ($results as $row) {
   
  
        ?>
        <div class="col-md-4" >
           
            <div class="card-body " >
              <div class="info bg-secondary ">
  
                  <center>
                    <div class="text-center ">
                      <h4 class="font-size-20 font-weight-600">

  <a style="color: yellow" href="?ads={{$row->id}}">
    {{$row->tittle}}</a>
  </h4>
                 <p style="color:black;">
                    <?php 
$view=db::SELECT("SELECT * from videos where id='$row->id' ");
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
                   <span class="bg bg-secondary">Status: {{$status}}</span>
                 </p>
             
              </div>
                    <hr>
                   
                 
                <!-- /.info-box-content -->
              <div style="height: 200px;width: 100%;">

                  <?php 
                  echo " <video src='".asset('video/'.$row->video)."' style='width:100%;height:100%;' style='margin-top:-20px' controls>";
                  ?>     
                 <!-- <p> {{$row->description}} </p> -->
                 
              
              </div>

                <div class="bg-info container-fluid">
                  <div class="col-md-12">
                    <center>
                        <div class="btn btn-warning">
<!-- <a href="{{route('user.dashboard.ptc')}}?id={{$row->id}}"> View</a> -->


  <a href="?ads={{$row->id}}">
    View</a>
  
        </div>&nbsp;&nbsp;&nbsp;<div class="btn btn-danger">
<a href="{{route('user.dashboard.video')}}?video={{$row->id}}"> Edit</a>
        </div>
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
    echo "<span class='btn btn-primary'>No videos you have uploaded. click to upload </span>";
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
   <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;


    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
    ?>

 <div>
     @include('admin.admin-base')


        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"  style="width: 82%;">
    <br><br>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
    <ul class="nav nav-tabs">
          <li class="active"><a  href="{{route('admin.ads')}}">Manage ads</a></li>
        <li><a  href="{{route('admin.videos')}}">Manage videos</a></li>
        <li><a  href="#">Manage download</a></li>
        <li><a  href="{{route('admin.video.create')}}">Add Videos</a></li>
             
          <!-- <center>
          <h3>My Click View </h3></center> -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content" style="margin-left: -200px;">
      <div class="container">
      <div class="row" >
        <?php 

$results=db::SELECT("SELECT * from videos order by status asc");
if (count($results)) {
  foreach ($results as $row) {
   
  
        ?>
        <div class="col-md-4" >
           
            <div class="card-body " >
              <div class="info card-header ">
  
                  <center>
                    <div class="text-center ">
                      <h4 class="font-size-20 font-weight-600">

 <a style="color: #007bff" target="__blank" href="{{route('admin.watch')}}?video={{$row->id}}">{{$row->tittle}}</a>
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
                  echo " <video src='".asset('video/'.$row->video)."' style='width:100%;height:100%;' controls style='margin-top:-80px' >";
                  ?>     
                 <!-- <p> {{$row->description}} </p> -->
                 
              
              </div>

                <div class="bg-info container-fluid">
                  <div class="col-md-12">
                    <center>
    <!-- <br>       <div class="alert alert-info"> -->
<!-- <a href="{{route('user.dashboard.ptc')}}?id={{$row->id}}"> View</a> -->
<br>
  <a style="color:white;text-decoration: underline;" href="{{route('admin.watch')}}?video={{$row->id}}">
  View </a>
 &nbsp;&nbsp;&nbsp;<span class="bg bg-primary">{{$row->status}}</span>
                       
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
else {
    echo '<span class="bg bg-secondary">No Videos have uploaded </span>';
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
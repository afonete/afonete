   <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;


    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
    ?>

 <div>
     @include('admin.admin-base')
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        .content-wrapper {
            position: relative;
        }
    </style>

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
    <!--<ul class="nav nav-tabs">-->
    <!--      <li class="active"><a  href="{{route('admin.ads')}}">Manage ads</a></li>-->
    <!--    <li><a  href="{{route('admin.videos')}}">Manage videos</a></li>-->
    <!--    <li><a  href="#">Manage download</a></li>-->
    <!--    <li><a  href="{{route('admin.video.create')}}">Add Videos</a></li>-->
             
             <div class="tabs tab_links mt-2">
                <span class="links_tabs d-flex ">
                    <!--<a href="{{ route('admin.ads') }}" class="fomoLink" id="tabs"> <i-->
                    <!--        class="fa-solid fa-rectangle-ad"></i> Manage ads </a>-->
                    <a href="{{ route('admin.videos') }}" class="fomoLink"><i class="fa-solid fa-video"></i> Manage
                        videos</a>
                    <a href="{{route('admin.video.create')}}" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                        Videos </a>

                </span>

                <!--<a href="{{ route('user.dashboard.payvideo') }}" class="fomoLink"><i class="fa-solid fa-download"></i>-->
                <!--    Manage download <i style="color: red">Coming soon</i></a>-->
            </div>
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
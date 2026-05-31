    
    <?php

    use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;
   
     if (!$id) {
        header("location:admin.payclick");
            }
    ?>

     @include('admin.admin-base')
     <style type="text/css">
       #form{
        display: none;
       }
     </style>
     <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper" style="width:82%">
    <!-- Content Header (Page header) -->
    <br><br>
    <div class="content-header">
      <div class="container-fluid">
        <ul class="nav nav-tabs">
          <li class="active">
<span id="msg" style="color: red;">
 Checking ads <a href="{{route('admin.ads.approve')}}?ads={{$id}}" class="btn btn-primary">approve</a>
 {{$message??''}}
</span>
         </li>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      
        <div class="row">
                    <?php 
$results=db::SELECT("SELECT * from ads where id='$id' order by id desc");
  foreach ($results as $row) {
   
  
        ?>
            <div class="col-md-8">
            <div class="card card-primary card-outline">
            <center>
       <h4>   {{$row->tittle}}
        <br></h4>
        <style type="text/css">
   /* #mmm{
        background: url("{{asset('ads/'.$row->banner)}}");
        height: 165px;
        background-repeat: no-repeat;
width: 800px;

    }*/
</style>
       <div id="mmm">
<img src="{{asset('ads/'.$row->banner)}}" width="100%" style="height: 300px;width: 60%;" class="img click-img" alt="click adds">
         
       </div>
       <p>{{$row->description}} <!-- 
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum. -->
<!--  -->
       </p>
         
           </center>
        </div>
       

      </div> 
 <?php }?>
 

  </div>
</div>
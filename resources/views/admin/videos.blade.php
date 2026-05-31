   <?php


    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use App\Models\video;

    use App\Models\User;
    $user = Auth::user();

    $name = $user->user;

    $video = video::orderByRaw("FIELD(status, 'pending', 'rejected', 'approved')")->get();
global $status;

  $accepted = video::where('status','approved')->count();
    $rejected=video::where('status','rejected')->count();
    ?>
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
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="tabs tab_links mt-2">
            <span class="links_tabs d-flex ">
                <a href="{{ route('admin.videos') }}" class="fomoLink"><i class="fa-solid fa-video"></i> Manage
                    videos</a>
                <a href="{{ route('admin.video.upload') }}" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                    videos </a>
            </span>


        </div>

        <div class="row my-2">
            <div href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon all">
                    <i class="nav-icon fa-solid fa-video"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total Videos
                    </div>


                    <div class="members" style="font-size: 13px">Videos: {{count($video)}}</span></div>
                </div>
            </div>
            <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon accepted">
                    <i class="nav-icon fa-solid fa-video"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Accepted Videos
                    </div>

                    <div class="members" style="font-size: 13px">Videos: {{$accepted}}</div>
                </div>
            </a>

            <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon accepted reject-icon">
                    <i class="nav-icon fa-solid fa-video"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Rejected Videos
                    </div>
                    <?php $joined = DB::SELECT('SELECT * from members where status=1'); ?>
                    <div class="members" style="font-size: 13px">Videos: {{$rejected}}</div>
                </div>
            </a>


        </div>

        {{-- table for all adds --}}
        <div class="tables my-2">
            <div class="table-container rounded ">
                <div class="pt-3 border">
                    <p class="text-center font-weight-bold table-title">All Videos <span class="text-success">{{count($video)}}</span>
                    </p>
                </div>

                <div class="tb">
                    @if(count($video))
                    <table class="table table-striped " style="text-align: center;">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <!-- <th>Description</th> -->
                                <th> Status</th>
                                <th>Owner</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php $i = 1;
                                foreach( $video as $eachVideo) {
switch ($eachVideo->status) {
    case 'pending':
        $status = "<span class='bg bg-primary'>$eachVideo->status</span>";
        break;
    case 'rejected':
        $status = "<span class='bg bg-danger'>$eachVideo->status</span>";
        break;
    case 'approved':
        $status = "<span class='bg bg-success'>$eachVideo->status</span>";
        break;
    default:
        $status = "<span class='bg bg-dark'>no status found</span>";
        break;
} ?>
                            <a href="{{ route('admin.ads') }}" class="">
                                <tr>
                                   <td>{{ $i++ }}</td>
                                        <td>{{$eachVideo->tittle}}</td>
                                        <!-- <td>{{$eachVideo->description}}</td> -->
                                        <td><?php echo $status ?></td>
                                        <td>{{$eachVideo->user}}</td>
                                    <td class=" tracking-wide text-center text-2xl">

                                        <div class="action drop-btn ellipsMore">
                                            <i class="las la-ellipsis-v cursor-pointer"></i>
                                        </div>
                                        <div class=" absolute right-0 w-48 py-2 mt-2 bg-white bg-gray-100 rounded-md shadow-xl"
                                            id="drop-content">
                                            <a href="{{ route('admin.videoDetail') }}?id={{$eachVideo->id}}"
                                                class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">view</a>
                                            <a href="#?id=<?php echo $i; ?>"
                                                class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </a>
                            <?php } ?>

                        </tbody>
                        @else
                        <span class="bg bg-secondary">No ads have uploaded </span>
                        @endif
                    </table>
                </div>

            </div>
        </div>
        {{-- end of container fuluid --}}

        <!-- Main content -->


        </div><!-- /.container-fluid -->

        <div class="reject bg-white rounded-xl shadow-lg py-2 px-4 " id="reject">
            <div
                class="delIcon rounded-full bg-gray-100 h-14  w-14 d-flex justify-content-center align-items-center my-1">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="font-bold text-lg my-2">left reason you reject ads</div>
            <div class="text-center">
                <form action="POST">
                    <input type="text"
                        class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="reason">
                </form>
            </div>
            <div class="actions">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1" id="cancel">Cancel</button>
                <button class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Reject</button>
            </div>
        </div>
    </div>
</div>

<script>
    // ellipse more
    let ellipsMore = document.getElementsByClassName("ellipsMore");

    for (let i = 0; i < ellipsMore.length; i++) {
        const elem = ellipsMore[i];
        elem.addEventListener("click", () => {
            let panel = elem.nextElementSibling;
            console.log('next: ', panel);
            if (panel.style.display === "block") {
                panel.style.display = "none"
            } else {
                panel.style.display = "block"
            }
        })
    }
    // reject model
    const reject = document.getElementsByClassName("rejectBtn");
    const cancel = document.getElementById("cancel");
    const rejModel = document.getElementById("reject");

    for (let i = 0; i < reject.length; i++) {
        const element = reject[i];
        element.addEventListener("click", () => {
            rejModel.style.display = "block";
        })
    }
    cancel.addEventListener("click", () => {
        rejModel.style.display = "none";
    })
</script>
@include('user.footer')





























{{-- <?php

//    use Illuminate\Support\Facades\Auth;
//    use Illuminate\Support\Facades\DB;

//    use App\Models\User;
//    $user = Auth::user();

//    $name = $user->user;
?>

   <div>
       @include('admin.admin-base')


       <!-- Content Wrapper. Contains page content -->
       <div class="content-wrapper" style="width: 82%;">
           <br><br>

           <!-- Content Header (Page header) -->
           <div class="content-header">
               <div class="container-fluid">
                   <ul class="nav nav-tabs">
                       <li class="active"><a href="{{ route('admin.ads') }}">Manage ads</a></li>
                       <li><a href="{{ route('admin.videos') }}">Manage videos</a></li>
                       <li><a href="#">Manage download</a></li>
                       <li><a href="{{ route('admin.video.create') }}">Add Videos</a></li>

               </div><!-- /.container-fluid -->
           </div>
           <!-- /.content-header -->

           <!-- Main content -->
           <div class="content" style="margin-left: -200px;">
               <div class="container">
                   <div class="row">
                       <?php

                       // $results=db::SELECT("SELECT * from videos order by status asc");
                       // if (count($results)) {
                       //   foreach ($results as $row) {
                       ?>
                       <div class="col-md-4">

                           <div class="card-body ">
                               <div class="info card-header ">

                                   <center>
                                       <div class="text-center ">
                                           <h4 class="font-size-20 font-weight-600">

                                               <a style="color: #007bff" target="__blank"
                                                   href="{{ route('admin.watch') }}?video={{ $row->id }}">{{ $row->tittle }}</a>
                                           </h4>
                                           <p style="color:black;">
                                               <?php
                                               $view = db::SELECT("SELECT * from videos where id='$row->id' ");

                                               ?><br>

                                           </p>

                                       </div>
                                       <hr>


                                       <!-- /.info-box-content -->
                                       <div style="height: 150;width: 100%;">

                                           <?php
                                           //    echo " <video src='" . asset('video/' . $row->video) . "' style='width:100%;height:100%;' controls style='margin-top:-80px' >";
                                           ?>
                                           <!-- <p> {{ $row->description }} </p> -->


                                       </div>

                                       <div class="bg-info container-fluid">
                                           <div class="col-md-12">
                                               <center>
                                                   <!-- <br>       <div class="alert alert-info"> -->
                                                   <!-- <a href="{{ route('user.dashboard.ptc') }}?id={{ $row->id }}"> View</a> -->
                                                   <br>
                                                   <a style="color:white;text-decoration: underline;"
                                                       href="{{ route('admin.watch') }}?video={{ $row->id }}">
                                                       View </a>
                                                   &nbsp;&nbsp;&nbsp;<span
                                                       class="bg bg-primary">{{ $row->status }}</span>

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

                       <script></script>
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
   </div> --}}

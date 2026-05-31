
<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->name??'';
?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Fonepo Affiliate</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="{{asset('assets/a/dist/js/adminlte.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
     <link rel="icon" href="{{asset('assets/a/img/big-logo.png')}}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('assets/a/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/a/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/a/dist/css/tree_style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/a/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet"
        href="{{asset('assets/a/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/a/dist/css/adminlte.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/a/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/a/dist/css/proje.css')}}">
    <link rel="stylesheet" href="{{asset('assets/a/css/style.css')}}">

</head>

<body class="hold-transition sidebar-mini">
    <div class="">
        <!-- Navbar -->

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav menu-bar-icon">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" ><i class="fas fa-bars"></i></a>
                </li>
            </ul>

    
               <div class="account"> 
                     <div class="user-panel d-flex justify-content-end" id="drop-btn" data-widget="control-sidebar" data-slide="true" role="button">
                        <div class="info">
                            <a href="#" class="d-block">{{$name}}</a>
                        </div>
                        <div class="user-img">
                           <img src="{{asset('assets/a/img/user.png')}}" class="" alt="User Image">
                        </div>
                    </div>
                    
            
                </div>
                </div> 
                

            </nav>
    </div>

    </li>
    </ul>
    </nav>
    <!-- /.navbar -->

    <!---- left navigation menu start ---->
<style type="text/css">
    ul li img{
        width: 25px;
        height: 25px;
    }
</style>
   
    <!-- Main Sidebar Container -->
       <aside class="main-sidebar sidebar-dark-info elevation-4">
        <!-- Brand Logo -->
        <a href="" class="brand-link">
            <img src="{{asset('assets/a/img/big-logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">Admin</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-fixed" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item has-treeview ">
                        <a href="{{route('admin.dashboard')}}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                <p>
                                    Dashboard
                                </p>
                            </p>
                        </a>

                    </li>
                         <!-- <li class="nav-item has-treeview ">
                        <a href="{{route('admin.add_user')}}" class="nav-link ">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                <p>
                                    Add user
                                </p>
                            </p>
                        </a>

                    </li>
 -->

                    <li class="nav-item">
                        <a href="{{route('admin.requested')}}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                requested Users
                               
                            </p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{route('admin.contacted')}}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Contacted
                                <sup><span class="right badge badge-success"></span></sup>
                                
                               
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.ads')}}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Manage ads
                                <sup><span class="right badge badge-success"></span></sup>
                                
                               
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.videos')}}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Manage Videos
                                <sup><span class="right badge badge-success"></span></sup>
                                
                               
                            </p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ route('admin.clubs') }}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Manage Clubs
                                <sup><span class="right badge badge-success"></span></sup>
                            </p>
                        </a>
                    </li>
                    {{-- END FO PROFILE SETTINGS --}}
                    <li class="nav-item">
                        <a href="{{route('admin.subscribe')}}" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Subscribed

                            </p>
                        </a>
                    </li>
               <li> <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                <img src="https://cdn-icons-png.flaticon.com/128/4436/4436954.png" width="25px" height="25px">  
 Log out</a><form id="logout-form" action="{{ route('logout') }}" method="POST"> @csrf                
                     </form>
                    </li>

                </ul>
            </nav><br><br>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- REQUIRED SCRIPTS -->
    </div>

     <aside class="control-sidebar control-sidebar-dark profile-drop">
            <div class="p-2 ml-3">
                <h3 id="prof"></h3>

                <p><i class="las la-id-card mr-2"></i><a  href="{{route('admin.profile.edit')}}">Profile</a></p>
                <p> <i class="fas fa-cog mr-2"></i> <a href="">Settings</a></p>
                <hr id="hrs">

                <!-- <p><i class="las la-question-circle mr-2"></i> <a href="">Need Help</a></p> -->
                <p>
                    <i class="las la-sign-out-alt text-danger"></i>
                    <a class="text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        Logout</a></p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"> @csrf
                </form>
            </div>
    </aside>

    <!-- /.control-sidebar -->


    <script type="text/javascript">

        // hide and show setting box
        document.getElementById('drop-btn').addEventListener('click', function() {
          var dropdownContent = this.nextElementSibling;
          if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
          } else {
            dropdownContent.style.display = 'block';
          }
        });
        //dashboard menu
        document.getElementById('menu').addEventListener('click', function() {
            let navbar = document.getElementById('topnav');
            navbar.classList.toggle("topnav")
        });

    </script>
</body>

</html>
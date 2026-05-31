
<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Position;

$user = Auth::user();
$name = $user->user;
$isFree = $user->has_free_package;

?>
<!DOCTYPE html>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64af5f0194cf5d49dc633c00/1h56gm8bh';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>

<script>


    </script>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>{{ strtoupper(env('APP_NAME')) }} Affiliate</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<!-- <script src="https://cdn.tailwindcss.com"></script> -->
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- sdsssd -->

</head>

<style>

/* Customized Alert Styles */
.alert {
  position: fixed;
  top: 50px;
  left: 700px;
  width: 300px;
  transform: translateX(-50%);
  padding: 15px;
  background-color: #f2f2f2;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
  display:none;

}

.alert p {
  margin: 0;
}

.close-btn {
  cursor: pointer;
  float: right;
  font-size: 18px;
  font-weight: bold;
}

.alert.show {
  display: block;
  animation: fadeIn 9s;
}

@keyframes fadeIn {
  from {
    opacity: 12;
  }
  to {
    opacity: 20;
  }
}

  </style>



  <div id="customAlert" class="alert">
    <span class="close-btn" onclick="hideAlert()">&times;</span>
    <p>Only FC are targeted, Comming soon</p>
  </div>



 <script type="text/javascript">
   function showCustomAlert() {
  const customAlert = document.getElementById("customAlert");
  customAlert.classList.add("show");

  setTimeout(function() {
    hideAlert();
  }, 2000);
}

function hideAlert() {
  const customAlert = document.getElementById("customAlert");
  customAlert.classList.remove("show");
}

document.getElementById("showAlertBtn").addEventListener("click", showCustomAlert);

 </script>



        <div class="">


        <!-- Navbar -->
        {{-- <nav class="main-header navbar navbar-expand navbar-white navbar-light d-flex justify-content-between">
            <!-- Left navbar links -->
            <ul class="navbar-nav bg-danger">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" ><i class="fas fa-bars"></i></a>
                </li>
            </ul>


            <div class="flex-1 d-flex bg-success" >

                     <div class="user-panel d-flex justify-content-end" id="drop-btn" data-widget="control-sidebar" data-slide="true" role="button">
                        <div class="info">
                            <a href="#" class="d-block">{{$name}}</a>
                        </div>
                        <div class="user-img">
                           <img src="{{asset('assets/a/img/user.png')}}" class="" alt="User Image">
                        </div>
                    </div>


                </div>


        </nav> --}}

            <nav class="main-header navbar navbar-expand-lg navbar-white ">

                <button class="btn btn-sm btn-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" ><i class="fas fa-bars"></i></a>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav mr-auto">


                  </ul>
                  <div>
                    <button class="btn btn-primary rounded-pill btn-sm mx-2 " >
                        Become An Affiliate
                    </button>
                  </div>
                  <div>
                    <button  class="btn btn-warning rounded btn-sm mx-2 " onclick="handleOpen()">
                        Chat Online
                    </button>
                  </div>

                  <div>
                    <a href="https://t.me/+dDtOAg1RwTg5NDFk"
                target="_blank" class="btn btn-warning   rounded btn-sm mx-2 ">
                        Chat On Telegram
                  </a>
                  </div>
                  <div class="bg-white shadow-sm   border border-light rounded px-2 py-1">
                         <small class="d-block">
                            <a href="#">FOCOIN</a>
                         </small>
                  </div>

                  <div class="flex-1 d-flex " >

                    <div class="user-panel d-flex justify-content-end" id="drop-btn" data-widget="control-sidebar" data-slide="true" role="button">
                       <div class="info">
                           <a href="#" class="d-block">{{$name}}</a>
                       </div>
                       <div class="user-img">
                          <img src="{{asset('assets/a/img/user.png')}}" class="" alt="User Image">
                       </div>
                   </div>


                </div>
              </nav>

    <!-- /.navbar -->


<style type="text/css">
    ul li img{
        width: 25px;
        height: 25px;
    }
</style>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-info elevation-4">

        <!-- Brand Logo -->
        <a href="{{route('user.dashboard')}}" class="brand-link">
            <img src="{{asset('assets/a/img/big-logo.png')}}" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">{{ strtoupper(env('APP_NAME')) }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-fixed" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                  <li class="p-0">
                    <div class="py-3 px-0">

                          <div class="d-flex flex-column align-items-center text-center">
                            <div class="d-flex align-items-center justify-between pb-3">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle bg-white" width="160">
                                <h5 class="mx-2 text-white">{{$user->name}}</h5>
                            </div>

                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex border border-danger align-items-center  rounded-pill p-1 bg-white ">
                                   <span></span>
                                   <span class="text-xs">Banary status:  </span>
                                   <span class="px-1 text-danger text-sm" style=" !important; font-weight:700">inactive</span>
                                </div>

                                <div class="d-flex border border-success align-items-center  rounded-pill p-1 bg-white ">
                                   <span></span>
                                   <span class="text-sm">KCY status <strong>None</strong> :</span>

                                   <a href="#" class="px-1 text-primary text-sm underlined" style="color:dodgerblue !important; text-style:underlined;
                                     font-weight:700">apply now</a>
                                </div>

                            </div>
                          </div>

                    </div>

                 </li>
                    <li class="nav-item has-treeview ">
                        <a href="{{route('user.dashboard')}}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                <p>
                                    Dashboard
                                </p>
                            </p>
                        </a>

                    </li>

                    {{-- <!-- End of nav-item {{route('user.dashboard.payclick')}}--> --}}
                   {{-- @if ($isFree == 'yes' || $isFree == 1) --}}
                   <li class="nav-item has-treeview ">
                    <a href="{{route('user.buypackage')}}" class="nav-link">
                        <i class="nav-icon fas fa-share"></i>
                        <p>

                                Buy Package


                        </p>
                    </a>

                </li>
                   {{-- @endif --}}


                    <li class="nav-item has-treeview ">
                        <a href="{{route('user.dashboard.payclick')}}" class="nav-link">
                            <i class="nav-icon fas fa-share"></i>
                            <p>
                                <p>
                                    FOMO
                                </p>

                            </p>
                        </a>

                    </li>


                    <!-- End of nav-item  {{route('user.dashboard.balance')}}-->

                    <li class="nav-item has-treeview">
                        <a href="{{route('user.dashboard.balance')}}" class="nav-link">
                            <i class="nav-icon fas fa-signal"></i>
                            <p>
                                Balance
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{route('overview')}}" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>
                                Finance & Wallet
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        @if($user->has_paid_package == 'free' ||$user->has_free_package == 'yes')
                        <a href="{{route('user.dashboard.freeaccount-restricted')}}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style="display:inline-block;vertical-align:middle">
                                 <span class="push" style="display:inline-block;vertical-align:middle">  Investment Package</span>
                          </a>
                        @else
                        <a href="{{route('investment-package')}}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style="display:inline-block;vertical-align:middle">
                                 <span class="push" style="display:inline-block;vertical-align:middle">  Investment Package</span>
                          </a>
                        @endif
                    </li>

                    <li class="nav-item has-treeview">

                        @if($user->has_paid_package == 'free' || $user->has_free_package == 'yes')
                        <a href="{{route('user.dashboard.freeaccount-restricted')}}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style="display:inline-block;vertical-align:middle">
                                 <span class="push" style="display:inline-block;vertical-align:middle">  Staker Package</span>
                          </a>
                        @else
                        <a href="{{ route('staker-package') }}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style=" display:inline-block; vertical-align:middle;">
                            <span class="push" style="display:inline-block; vertical-align:middle;">Staker Package</span>
                        </a>
                        @endif
                    </li>



                    <!-- End of nav-item  -->

                    <li class="nav-item has-treeview">
                        <a  href="/user/deposit" class="nav-link">
                            <i class="nav-icon fas fa-university "></i>
                            {{-- <img src="https://cdn-icons-png.flaticon.com/128/2626/2626618.png"> --}}

                               <p>Deposit</p>

                        </a>
                    </li>
                    {{-- {{route('user.dashboard.deposit')}} --}}
                    <li class="nav-item has-treeview">
                        <a  href="/user/dashboard/withdraw/user" class="nav-link">
                            <i class="nav-icon fas fa-university "></i>
                               <p>Withdraw</p>

                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{route('user.dashboard.payments')}}"  class="nav-link">
                            <i class="nav-icon fas fa-road"></i>
                            <p>
                                Internal Exchange
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" onclick="soon()" class="nav-link">
                         <img src="https://cdn-icons-png.flaticon.com/128/999/999346.png" >
                          <p>
                                Featured
                                   <span class="right badge badge-danger">coming soon</span>

                            </p>

                        </a>
                    </li>
                    <!-- End of nav-item -->
                    <li class="nav-item has-treeview">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-star"></i>
                            <p>
                                Upgrade Package
                            </p>
                        </a>
                    </li>

                   <li> <a class="nav-link"  href="{{route('user.dashboard.project')}}">
                            <img src="https://cdn-icons-png.flaticon.com/128/4595/4595164.png" style="display:inline-block;vertical-align:middle">
                            <span class="push" style="display:inline-block;vertical-align:middle">  Upload Project</span>

                        </a>
                    </li>


<?php //{{route('user.dashboard.create')}} ?>

                    <li class="nav-item has-treeview">

                        @if($user->has_free_package == 'yes' ||$user->has_free_package == 'yes')
                        <a href="{{route('user.dashboard.freeaccount-restricted')}}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style="display:inline-block;vertical-align:middle">
                                 <span class="push" style="display:inline-block;vertical-align:middle">  Club Building</span>
                          </a>
                        @else
                        <a href="{{route('user.dashboard.create')}}"   class="nav-link ">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                club Building

                            </p>
                        </a>
                        @endif

                    </li>
                    <li class="nav-item has-treeview">
                        <a href="{{route('teambuilding')}}" class="nav-link ">  <!-- teambuilding -->
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                team Building
                            </p>
                        </a>

                    </li>
                    <!-- start my link-->
                     <li class="nav-item has-treeview">
                        <a href="{{route('user.referral.show')}}" class="nav-link ">
                            <i class="nav-icon fas fa-link"></i>
                            <p>
                                My Links

                            </p>
                        </a>

                    </li>
                     <!-- end my link-->

                    <!-- End of nav-item -->

                    <li class="nav-item has-treeview">


                        @if($user->has_paid_package == 'free' ||$user->has_free_package == 'yes')
                        <a href="{{route('user.dashboard.freeaccount-restricted')}}" class="nav-link">
                            <img src="https://cdn-icons-png.flaticon.com/256/7544/7544957.png" style="display:inline-block;vertical-align:middle">
                                 <span class="push" style="display:inline-block;vertical-align:middle">  Education</span>
                          </a>
                        @else
                        <a href="/user/education" class="nav-link ">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Education
                            </p>
                        </a>
                        @endif

                    </li>

                    <!-- End of nav-item -->
                     <!-- start Rewards center -->
                     <li class="nav-item has-treeview">
                        <a href="/user/rewards"  class="nav-link ">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Reward Center
                                 <span class="right badge badge-success">New</span>
                            </p>
                        </a>

                    </li>
                     <!-- end Rewards center-->

                    <li class="nav-item has-treeview">
                        @if($user->has_free_account || $isFree)
                           <a href="{{route('user.dashboard.freeaccount-restricted')}}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Exchange
                            </p>
                            @else

                        <a href="https://exchange.focoin.eu/" target="_blank" class="nav-link ">
                            <i class="nav-icon fas fa-home"></i>
                            <p>
                                Exchange
                            </p>
                        </a>
                        @endif
                    </li>

                    {{-- <!-- End of nav-item -->
          <li class="nav-item has-treeview">
            <a href="#" onclick="soon()" class="nav-link ">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        Marchants
                    </p>
                    </a>
                    </li> --}}

                    <!-- End of nav-item -->

                    <li class="nav-item has-treeview">
                        <a href="#"  class="nav-link">
                            <i class="nav-icon fas fa-plus"></i>
                            <p>
                                Products
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: blanchedalmond">
                            <li class="nav-item">
                                <a href="#" class="nav-link" target=__blank onclick="soon()">
                                    <i class="fas fa-shopping-cart nav-icon" style="color: black"></i>
                                    <p style="color: black">Our Shop</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link " target="__blank" onclick="soon()">
                                    <i class="fas fa-home nav-icon" style="color: black"></i>
                                    <p style="color: black">Our Booking</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" onclick="soon()" class="nav-link" onclick="soon()">
                                    <i class="far fa-circle nav-icon" style="color: black"></i>
                                    <p style="color: black">Other Products</p>
                                </a>
                            </li>
                        </ul>
                    </li> <!-- End of nav-item -->
                     <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon las la-cart-arrow-down"></i>
                            <p>
                                Eshop
                            </p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="https://loan.focoin.eu/" target="_blank" class="nav-link">
                            <i class="nav-icon las la-hourglass-half"></i>
                            <p>
                                Loan
                            </p>
                        </a>
                    </li>
                      <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link ">
                            <i class="nav-icon fas fa-money"></i>
                            <p>
                                Bonos
                                   <span class="right badge badge-danger">coming soon</span>

                            </p>
                        </a>

                    </li>

                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link ">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Refer & Earn
                            </p>
                        </a>

                    </li>

                    <!-- End of nav-item -->
                        <li class="nav-item">
                        <a  class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Inbox
                                <sup><span class="right badge badge-success">0</span></sup>


                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/user/user-invitation" class="nav-link">
                            <i class="fa-duotone fa-location-arrow"></i>
                            <p>
                                My Invitations

                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                My Invetees
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                My Team
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Team Summary
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Leaderboard
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Marketing campaigns
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" onclick="soon()" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Testimonials
                            </p>
                        </a>
                    </li>
                    {{-- END FO PROFILE SETTINGS --}}
                    <li class="nav-item">
                        <a href="/user/faq" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                FAQ
                            </p>
                        </a>
                    </li>
                    <li>
                         <p>
                    <i class="las la-sign-out-alt text-danger"></i>
                    <a class="text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        Logout</a></p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"> @csrf
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
                <div class="mainDropdown">
                    <p id="dropBtn"><i class="las la-users-cog"></i><a href="#"> Join Management Team</a></p>
                    <div class="dropdown " id="myDropdown">
                    <style>
                        .custom_select select{
                            display: none;
                        }
                        .drop-item {
                            display: none;
                            /* Add your styling for the drop-item div here */
                        }


                    </style>


                       <?php //check if user have been applied
                       $applied=Position::where('user',$name)->first();
                         ?>
                        <form  method="POST" action="{{route('user.position.apply')}}">
                            @csrf
                             @if($applied)
                         <div id="dropBtn text-dark">
                            <div class="p-1 text-dark text-center" style="border-bottom: 1px solid black;">
                                <span> {{$applied->position}}
                            </div>
                            <div class="d-flex justify-content-around">
                                <div class="text-dark p-1"> reward: <span class="font-weight-bold">{{$applied->award}}$ </span> </div>
                                <div class="text-dark p-1"> Status: <span class="font-weight-bold"> {{$applied->status}} </span></div>
                            </div>

                         </div>
                    @else
                            <select class="custom-select" id="position-select" required name="position">
                                <option selected="true" disabled="disabled">Apply position</option>

                                <option data-position="team-leader">Team Leader</option>
                                <option data-position="developer">Country Leader</option>
                                <option data-position="support">Social media content &support</option>
                                <option data-position="training">Training & Marketing</option>
                                <option data-position="ceo">CEO 5</option>
                                <option data-position="ceo">CEO 6</option>
                                <option data-position="ceo">CEO 7</option>
                                <option data-position="ceo">CEO 8</option>
                                <option data-position="ceo">CEO 9</option>
                                <option data-position="ceo">CEO 10</option>
                                <!-- Add more options as needed -->
                            </select>
                            <input type="hidden" id="hiddenInput" name="award">

                                <div class="drop-item" id="team-leader-details">
                                <div class="d-flex justify-content-around">
                                    <div>reward:</div>
                                    <div class="font-weight-bold prize">$1000 </div>
                                    <div><button type="submit" class="applyBtn btn btn-primary btn-sm">Apply</button></div>
                    </div>
                                </div>

                                <div class="drop-item" id="developer-details">
                                <div class="d-flex justify-content-around">
                                     <div>reward:</div>
                                    <div class="font-weight-bold prize">$2000 </div>
                                    <div><button type="submit" class="applyBtn btn btn-primary btn-sm">Apply</button></div>
                    </div>
                                </div>

                                <div class="drop-item" id="support-details">
                                <div class="d-flex justify-content-around">
                                        <div>reward:</div>
                                        <div class="font-weight-bold prize">$500 </div>
                                        <div><button type="submit" class="applyBtn btn btn-primary btn-sm">Apply</button></div>
                                    </div>

                                </div>

                                <div class="drop-item " id="training-details">
                                <div class="d-flex justify-content-around">
                                    <div>reward:</div>
                                    <div class="font-weight-bold prize">$500 </div>
                                    <div><button type="submit" class="applyBtn btn btn-primary btn-sm">Apply</button></div>
                    </div>
                                </div>
                                <div class="drop-item" id="ceo-details">
                                    <div class="d-flex justify-content-around">
                                        <div>reward:</div>
                                        <div class="font-weight-bold prize">$3000 </div>
                                        <div><button type="submit" class="applyBtn btn btn-primary btn-sm ">Apply</button></div>
                                    </div>

                                </div>
                        </select>
                        @endif</form>

                    </div>
                </div>
                <p><i class="las la-id-card mr-2"></i><a href="{{route('profile.edit')}}">Profile</a></p>
                <p> <i class="fas fa-cog mr-2"></i> <a href="#" onclick="soon()">Settings</a></p>
                <hr id="hrs">

                <p><i class="las la-question-circle mr-2"></i> <a >Need Help</a></p>
                <p>
                    <i class="las la-sign-out-alt text-danger"></i>
                    <a class="text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        Logout</a></p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"> @csrf
                </form>
            </div>
    </aside>
    <!-- asdasd -->


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
    function soon(){
        alert(' This phase is targeting only FC user,  cooming soon')
    }


    // team management dropdown
    const dropBtn = document.querySelector("#dropBtn");
    const dropdown = document.getElementById("myDropdown");

    dropBtn.addEventListener("click", ()=>{
        dropdown.classList.toggle("showDropdown");
    })

    const positionSelect = document.getElementById('position-select');
    const teamLeaderDetails = document.getElementById('team-leader-details');
    const developerDetails = document.getElementById('developer-details');
    const supportDetails = document.getElementById('support-details');
    const trainingDetails = document.getElementById('training-details');
    const ceoDetails = document.getElementById('ceo-details');
    const hiddenInput = document.getElementById('hiddenInput');

    positionSelect.addEventListener('change', function () {
    const selectedValue = positionSelect.value;
     const selectedOption = positionSelect.options[positionSelect.selectedIndex];
    const selectedPosition = selectedOption.getAttribute("data-position");


    // Hide all drop-item elements
    teamLeaderDetails.style.display = 'none';
    developerDetails.style.display = 'none';
    supportDetails.style.display = 'none';
    trainingDetails.style.display = 'none';
    ceoDetails.style.display = 'none';

    // Show the corresponding drop-item based on the selected option
    if (selectedPosition === 'team-leader') {
        hiddenInput.value = 1000;
        teamLeaderDetails.style.display = 'block';
    } else if (selectedPosition === 'developer') {
        hiddenInput.value = 2000;
        developerDetails.style.display = 'block';
    }else if (selectedPosition === 'support'){
        hiddenInput.value = 500;
        supportDetails.style.display = 'block';
    }else if (selectedPosition === 'training'){
        hiddenInput.value = 500;
        trainingDetails.style.display = 'block';
    }
    else if (selectedPosition === 'ceo'){
        hiddenInput.value = 3000;
        ceoDetails.style.display = 'block';
    }
    });



    </script>

</html>


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

    <title><?php echo e(strtoupper(env('APP_NAME'))); ?> Affiliate</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="<?php echo e(asset('assets/a/dist/js/adminlte.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
     <link rel="icon" href="<?php echo e(asset('assets/a/img/big-logo.png')); ?>">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/plugins/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/dist/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/dist/css/tree_style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/plugins/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet"
        href="<?php echo e(asset('assets/a/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/dist/css/adminlte.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('assets/a/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/dist/css/proje.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/css/style.css')); ?>">
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

@keyframes  fadeIn {
  from {
    opacity: 12;
  }
  to {
    opacity: 20;
  }
}



/* Modern grouped user sidebar — calmer colors + clearer active states */
.main-sidebar .nav-sidebar > .nav-item { margin: 4px 8px; }
.main-sidebar .nav-sidebar .nav-link {
    border-radius: 8px;
    border-left: 4px solid transparent;
    transition: background .18s ease, color .18s ease, border-color .18s ease, transform .18s ease;
}

/* Clear but not too bright hover */
.main-sidebar .nav-sidebar .nav-link:hover {
    background: rgba(59,130,246,.22) !important;
    color: #ffffff !important;
    border-left-color: #60a5fa;
    transform: translateX(2px);
}

/* Parent menu when opened/active */
.main-sidebar .nav-sidebar > .nav-item > .nav-link.active,
.main-sidebar .nav-sidebar > .nav-item.menu-open > .nav-link {
    color: #fff !important;
    background: #1f2937 !important;
    border-left-color: #38bdf8;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
}

.main-sidebar .nav-sidebar .nav-treeview {
    display: none;
    margin: 5px 0 10px 0;
    padding: 6px 0;
    background: #111827;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 10px;
}
.main-sidebar .nav-sidebar .nav-item.menu-open > .nav-treeview { display: block; }
.main-sidebar .nav-treeview .nav-link {
    margin: 3px 8px;
    padding-left: 18px;
    color: #d1d5db !important;
    background: transparent !important;
    border-left-color: transparent;
}

/* Sub-menu hover */
.main-sidebar .nav-treeview .nav-link:hover {
    background: rgba(14,165,233,.16) !important;
    color: #ffffff !important;
    border-left-color: #38bdf8;
}

/* Clicked/current sub-menu item */
.main-sidebar .nav-treeview .nav-link.active {
    background: #0f766e !important;
    color: #ffffff !important;
    border-left-color: #2dd4bf;
    font-weight: 700;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.08);
}
.main-sidebar .nav-treeview .nav-link.active .nav-icon,
.main-sidebar .nav-treeview .nav-link.active p { color: #ffffff !important; }

.sidebar-group-toggle .right { transition: transform .18s ease; }
.menu-open > .sidebar-group-toggle .right,
.menu-open > a .right { transform: rotate(-90deg); }

/* Group headers: dark base with a colored left border instead of loud gradients */
.sidebar-group-toggle.group-packages { background:#1f2937 !important; border-left-color:#a78bfa; }
.sidebar-group-toggle.group-wallets  { background:#1f2937 !important; border-left-color:#34d399; }
.sidebar-group-toggle.group-tokens   { background:#1f2937 !important; border-left-color:#fbbf24; }
.sidebar-group-toggle.group-team     { background:#1f2937 !important; border-left-color:#60a5fa; }
.sidebar-group-toggle.group-tasks    { background:#1f2937 !important; border-left-color:#fb7185; }
.sidebar-group-toggle.group-other    { background:#1f2937 !important; border-left-color:#9ca3af; }
.sidebar-group-toggle.group-legacy   { background:#111827 !important; border-left-color:#6b7280; opacity:.92; }
.nav-header.text-muted { color:#9ca3af !important; letter-spacing:.08em; font-size:.72rem; margin: 10px 12px 4px; }

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
                           <a href="#" class="d-block"><?php echo e($name); ?></a>
                       </div>
                       <div class="user-img">
                          <img src="<?php echo e(asset('assets/a/img/user.png')); ?>" class="" alt="User Image">
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
        <a href="<?php echo e(route('user.dashboard')); ?>" class="brand-link">
            <img src="<?php echo e(asset('assets/a/img/big-logo.png')); ?>" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light"><?php echo e(strtoupper(env('APP_NAME'))); ?></span>
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
                                <h5 class="mx-2 text-white"><?php echo e($user->name); ?></h5>
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
                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.buypackage','investment-package','staker-package','user.investments*','packageRenew') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-packages <?php echo e(request()->routeIs('user.buypackage','investment-package','staker-package','user.investments*','packageRenew') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Packages <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.buypackage')); ?>" class="nav-link <?php echo e(request()->routeIs('user.buypackage') ? 'active' : ''); ?>">
                                    <i class="fas fa-shopping-cart nav-icon"></i><p>Buy Package</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <?php if($user->has_paid_package == 'free' || $user->has_free_package == 'yes'): ?>
                                    <a href="<?php echo e(route('user.dashboard.freeaccount-restricted')); ?>" class="nav-link">
                                        <i class="fas fa-chart-line nav-icon"></i><p>Investment Package</p>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('investment-package')); ?>" class="nav-link <?php echo e(request()->routeIs('investment-package') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i><p>Investment Package</p>
                                    </a>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item">
                                <?php if($user->has_paid_package == 'free' || $user->has_free_package == 'yes'): ?>
                                    <a href="<?php echo e(route('user.dashboard.freeaccount-restricted')); ?>" class="nav-link">
                                        <i class="fas fa-layer-group nav-icon"></i><p>Staker Package</p>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('staker-package')); ?>" class="nav-link <?php echo e(request()->routeIs('staker-package') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i><p>Staker Package</p>
                                    </a>
                                <?php endif; ?>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.investments')); ?>" class="nav-link <?php echo e(request()->routeIs('user.investments*') ? 'active' : ''); ?>">
                                    <i class="fas fa-briefcase nav-icon"></i><p>My Investments</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('packageRenew')); ?>" class="nav-link <?php echo e(request()->routeIs('packageRenew') ? 'active' : ''); ?>">
                                    <i class="fas fa-redo-alt nav-icon"></i><p>Package Renewal</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.dashboard.balance','user.dashboard.deposit','user.dashboard.withdraw','user.dashboard.payments') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-wallets <?php echo e(request()->routeIs('user.dashboard.balance','user.dashboard.deposit','user.dashboard.withdraw','user.dashboard.payments') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>Wallets <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.dashboard.balance')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.balance') ? 'active' : ''); ?>">
                                    <i class="fas fa-scale-balanced nav-icon"></i><p>Balance</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.dashboard.deposit')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.deposit') ? 'active' : ''); ?>">
                                    <i class="fas fa-arrow-down nav-icon"></i><p>Deposit</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.dashboard.withdraw')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.withdraw') ? 'active' : ''); ?>">
                                    <i class="fas fa-arrow-up nav-icon"></i><p>Withdraw</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.dashboard.payments')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.payments') ? 'active' : ''); ?>">
                                    <i class="fas fa-exchange-alt nav-icon"></i><p>Internal Exchange</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.token.*') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-tokens <?php echo e(request()->routeIs('user.token.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-coins"></i>
                            <p>Token Wallets <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="<?php echo e(route('user.token.locked')); ?>" class="nav-link <?php echo e(request()->routeIs('user.token.locked') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Locked Token</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.token.available')); ?>" class="nav-link <?php echo e(request()->routeIs('user.token.available') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Available Token</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.token.transfer')); ?>" class="nav-link <?php echo e(request()->routeIs('user.token.transfer') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Transfer Token</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.token.swap')); ?>" class="nav-link <?php echo e(request()->routeIs('user.token.swap') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Swap Token</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.token.withdraw')); ?>" class="nav-link <?php echo e(request()->routeIs('user.token.withdraw') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Withdraw Token</p></a></li>
                        </ul>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('teambuilding','user.referral.show','user.referral.*','downline') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-team <?php echo e(request()->routeIs('teambuilding','user.referral.show','user.referral.*','downline') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Team &amp; Referrals <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="<?php echo e(route('teambuilding')); ?>" class="nav-link <?php echo e(request()->routeIs('teambuilding') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Team Building</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.referral.show')); ?>" class="nav-link <?php echo e(request()->routeIs('user.referral.show') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>My Links</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.referral.bonus')); ?>" class="nav-link <?php echo e(request()->routeIs('user.referral.bonus') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Referral Bonus</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.referral.downline')); ?>" class="nav-link <?php echo e(request()->routeIs('user.referral.downline') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Downline</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.referral.rank')); ?>" class="nav-link <?php echo e(request()->routeIs('user.referral.rank') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Ranks &amp; Rewards</p></a></li>
                        </ul>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.dashboard.payclick','user.dashboard.project','user.dashboard.payvideo') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-tasks <?php echo e(request()->routeIs('user.dashboard.payclick','user.dashboard.project','user.dashboard.payvideo') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>FOMO / Tasks <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="<?php echo e(route('user.dashboard.payclick')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.payclick') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>FOMO</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.dashboard.project')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.project') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Upload Project</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.dashboard.payvideo')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.payvideo') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Paid Ads / Videos</p></a></li>
                        </ul>
                    </li>

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('profile.edit','password.show') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-other <?php echo e(request()->routeIs('profile.edit','password.show') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-ellipsis-h"></i>
                            <p>Other <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if($user->has_paid_package == 'free' || $user->has_free_package == 'yes'): ?>
                                <li class="nav-item"><a href="<?php echo e(route('user.dashboard.freeaccount-restricted')); ?>" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Education</p></a></li>
                            <?php else: ?>
                                <li class="nav-item"><a href="/user/education" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Education</p></a></li>
                            <?php endif; ?>
                            <li class="nav-item"><a href="/user/rewards" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Reward Center <span class="right badge badge-success">New</span></p></a></li>
                            <li class="nav-item"><a href="/user/faq" class="nav-link"><i class="far fa-circle nav-icon"></i><p>FAQ</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('profile.edit')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.edit') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Profile</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('password.show')); ?>" class="nav-link <?php echo e(request()->routeIs('password.show') ? 'active' : ''); ?>"><i class="far fa-circle nav-icon"></i><p>Password</p></a></li>
                        </ul>
                    </li>

                    
                    <li class="nav-header text-uppercase text-muted">Not in use now</li>
                    <li class="nav-item has-treeview">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-legacy">
                            <i class="nav-icon fas fa-archive"></i>
                            <p>Legacy / Coming Soon <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item"><a href="<?php echo e(route('overview')); ?>" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Finance &amp; Wallet</p></a></li>
                            <li class="nav-item"><a href="/user/deposit" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Deposit Old Route</p></a></li>
                            <li class="nav-item"><a href="/user/dashboard/withdraw/user" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Withdraw Old Route</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Featured <span class="right badge badge-danger">soon</span></p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Upgrade Package</p></a></li>
                            <li class="nav-item"><a href="<?php echo e(route('user.dashboard.create')); ?>" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Club Building</p></a></li>
                            <li class="nav-item"><a href="https://exchange.focoin.eu/" target="_blank" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Exchange</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Products</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Eshop</p></a></li>
                            <li class="nav-item"><a href="https://loan.focoin.eu/" target="_blank" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Loan</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Bonus</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Refer &amp; Earn</p></a></li>
                            <li class="nav-item"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Inbox <sup><span class="right badge badge-success">0</span></sup></p></a></li>
                            <li class="nav-item"><a href="/user/user-invitation" class="nav-link"><i class="far fa-circle nav-icon"></i><p>My Invitations</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>My Invitees</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>My Team</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Team Summary</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Leaderboard</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Marketing Campaigns</p></a></li>
                            <li class="nav-item"><a href="#" onclick="soon()" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Testimonials</p></a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                            <i class="nav-icon las la-sign-out-alt text-danger"></i><p>Logout</p>
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"> <?php echo csrf_field(); ?> </form>
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
                        <form  method="POST" action="<?php echo e(route('user.position.apply')); ?>">
                            <?php echo csrf_field(); ?>
                             <?php if($applied): ?>
                         <div id="dropBtn text-dark">
                            <div class="p-1 text-dark text-center" style="border-bottom: 1px solid black;">
                                <span> <?php echo e($applied->position); ?>

                            </div>
                            <div class="d-flex justify-content-around">
                                <div class="text-dark p-1"> reward: <span class="font-weight-bold"><?php echo e($applied->award); ?>$ </span> </div>
                                <div class="text-dark p-1"> Status: <span class="font-weight-bold"> <?php echo e($applied->status); ?> </span></div>
                            </div>

                         </div>
                    <?php else: ?>
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
                        <?php endif; ?></form>

                    </div>
                </div>
                <p><i class="las la-id-card mr-2"></i><a href="<?php echo e(route('profile.edit')); ?>">Profile</a></p>
                <p> <i class="fas fa-cog mr-2"></i> <a href="#" onclick="soon()">Settings</a></p>
                <hr id="hrs">

                <p><i class="las la-question-circle mr-2"></i> <a >Need Help</a></p>
                <p>
                    <i class="las la-sign-out-alt text-danger"></i>
                    <a class="text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        Logout</a></p>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"> <?php echo csrf_field(); ?>
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


    // Custom sidebar accordion fallback.
    // This does not depend on AdminLTE treeview JS, so Packages/Wallets/etc always open.
    document.querySelectorAll('.sidebar-group-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var item = this.closest('.nav-item.has-treeview');
            if (!item) return;

            var isOpen = item.classList.contains('menu-open');

            // Close sibling groups for cleaner navigation.
            var list = item.parentElement;
            if (list) {
                Array.prototype.forEach.call(list.children, function(openItem) {
                    if (openItem !== item && openItem.classList && openItem.classList.contains('has-treeview')) {
                        openItem.classList.remove('menu-open');
                    }
                });
            }

            if (isOpen) {
                item.classList.remove('menu-open');
            } else {
                item.classList.add('menu-open');
            }
        });
    });


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

    if (positionSelect) {
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
    }



    </script>

</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/user-dashboard-base.blade.php ENDPATH**/ ?>
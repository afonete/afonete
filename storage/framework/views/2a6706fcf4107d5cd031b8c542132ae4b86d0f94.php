
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
#customAlert {
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

#customAlert p {
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

/* No hover fill/background — keep sidebar calm */
.main-sidebar .nav-sidebar .nav-link:hover {
    background: transparent !important;
    color: #ffffff !important;
    border-left-color: transparent;
    transform: none;
}
.sidebar-group-toggle:hover {
    background: #1f2937 !important;
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
    background: transparent !important;
    background-color: transparent !important;
    border: 0 !important;
    border-radius: 0;
}
.main-sidebar .nav-sidebar .nav-item.menu-open > .nav-treeview { display: block; }
.main-sidebar .nav-treeview .nav-link {
    margin: 3px 8px;
    padding-left: 18px;
    color: #d1d5db !important;
    background: transparent !important;
    border-left-color: transparent;
}

/* Sub-menu hover: no fill/background */
.main-sidebar .nav-treeview .nav-link:hover {
    background: transparent !important;
    color: #ffffff !important;
    border-left-color: transparent;
}

/* Clicked/current sub-menu item: no fill, just text + small accent */
.main-sidebar .nav-treeview .nav-link.active {
    background: transparent !important;
    color: #ffffff !important;
    border-left-color: #2dd4bf;
    font-weight: 700;
    box-shadow: none;
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


/* Remove all AdminLTE/Bootstrap blue hover/active blocks inside sidebar submenus */
.main-sidebar .nav-sidebar .nav-treeview,
.sidebar-dark-info .nav-sidebar > .nav-item > .nav-treeview,
.sidebar-dark-info .nav-treeview,
.nav-sidebar .nav-treeview {
    background: transparent !important;
    background-color: transparent !important;
    box-shadow: none !important;
}
.main-sidebar .nav-treeview .nav-link,
.main-sidebar .nav-treeview .nav-link:hover,
.main-sidebar .nav-treeview .nav-link:focus,
.main-sidebar .nav-treeview .nav-link.active,
.sidebar-dark-info .nav-treeview > .nav-item > .nav-link,
.sidebar-dark-info .nav-treeview > .nav-item > .nav-link:hover,
.sidebar-dark-info .nav-treeview > .nav-item > .nav-link:focus,
.sidebar-dark-info .nav-treeview > .nav-item > .nav-link.active,
.nav-pills .nav-treeview .nav-link.active,
.nav-pills .nav-treeview .show > .nav-link {
    background: transparent !important;
    background-color: transparent !important;
    box-shadow: none !important;
}
/* Keep a subtle active indication without a filled background */
.main-sidebar .nav-treeview .nav-link.active,
.sidebar-dark-info .nav-treeview > .nav-item > .nav-link.active {
    color: #ffffff !important;
    border-left-color: #2dd4bf !important;
    font-weight: 700;
}

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

const showAlertBtn = document.getElementById("showAlertBtn");
if (showAlertBtn) {
  showAlertBtn.addEventListener("click", showCustomAlert);
}

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

                    <div class="user-panel d-flex justify-content-end" id="drop-btn" role="button">
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
    <aside class="main-sidebar sidebar-dark-info elevation-4 custom-user-sidebar">

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
                            <div class="sidebar-user-profile d-flex flex-column align-items-center text-center pb-3">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="User Avatar" class="rounded-circle bg-white sidebar-user-avatar">
                                <h5 class="sidebar-user-name text-white mt-2 mb-0" title="<?php echo e($user->activation); ?>"><?php echo e($user->activation); ?></h5>
                                <small class="sidebar-user-username text-muted" title="<?php echo e($user->user); ?>"><?php echo e($user->user); ?></small>
                            </div>

                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex border border-danger align-items-center  rounded-pill p-1 bg-white ">
                                   <span></span>
                                   <span class="text-xs">Binary status:  </span>
                                   <span class="px-1 text-danger text-sm" style=" !important; font-weight:700">inactive</span>
                                </div>

                                <div class="d-flex border border-success align-items-center  rounded-pill p-1 bg-white ">
                                   <span></span>
                                   <span class="text-sm">KCY status <strong>None</strong> :</span>

                                   <a href="#" class="px-1 text-primary text-sm underlined" style="color:dodgerblue !important; text-style:underlined;
                                     font-weight:700">Apply Now</a>
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
                                    <i class="fas fa-shopping-cart nav-icon"></i><p>Buy UVP AI License</p>
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

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.dashboard.balance','user.dashboard.deposit','user.dashboard.userwithdraw','user.dashboard.payments') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-wallets <?php echo e(request()->routeIs('user.dashboard.balance','user.dashboard.deposit','user.dashboard.userwithdraw','user.dashboard.payments') ? 'active' : ''); ?>">
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
                                <a href="<?php echo e(route('user.dashboard.userwithdraw')); ?>" class="nav-link <?php echo e(request()->routeIs('user.dashboard.userwithdraw') ? 'active' : ''); ?>">
                                    <i class="fas fa-arrow-up nav-icon"></i><p>Withdraw</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.withdrawal-history')); ?>" class="nav-link <?php echo e(request()->routeIs('user.withdrawal-history') ? 'active' : ''); ?>">
                                    <i class="fas fa-history nav-icon"></i><p>Withdrawal History</p>
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

                    
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('user.contracts.*') ? 'menu-open' : ''); ?>">
                        <a href="javascript:void(0)" class="nav-link sidebar-group-toggle group-other <?php echo e(request()->routeIs('user.contracts.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file-signature"></i>
                            <p>My contract <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('user.contracts.bifonex')); ?>" class="nav-link <?php echo e(request()->routeIs('user.contracts.bifonex') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Bifonex Contract</p>
                                </a>
                            </li>
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
                         <div class="text-dark">
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

        // Profile/control-sidebar toggle.
        // AdminLTE control-sidebar JS is not reliable on small screens in this layout,
        // so we manually toggle the same body class plus our own fallback class.
        const profileToggle = document.getElementById('drop-btn');
        const profileSidebar = document.querySelector('.control-sidebar.profile-drop');
        if (profileToggle && profileSidebar) {
            profileToggle.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                document.body.classList.toggle('profile-menu-open');
            });
        }

        // Click outside closes profile menu on small screens.
        document.addEventListener('click', function(event) {
            if (!document.body.classList.contains('profile-menu-open')) return;
            if (profileSidebar && profileSidebar.contains(event.target)) return;
            if (profileToggle && profileToggle.contains(event.target)) return;
            document.body.classList.remove('control-sidebar-slide-open');
            document.body.classList.remove('control-sidebar-open');
            document.body.classList.remove('profile-menu-open');
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

    if (dropBtn && dropdown) {
        dropBtn.addEventListener("click", (event)=>{
            event.preventDefault();
            dropdown.classList.toggle("showDropdown");
        });
    }


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

    <style id="final-sidebar-hover-fix">
        /* FINAL SIDEBAR HOVER FIX
           Removes the blue/blurred block that appears when hovering submenu items.
           This is placed at the bottom so it overrides AdminLTE/Bootstrap. */
        body .main-sidebar .nav-sidebar .nav-treeview,
        body .main-sidebar .nav-sidebar .nav-treeview:hover,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-item,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-item:hover {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
            box-shadow: none !important;
        }

        body .main-sidebar .nav-sidebar .nav-treeview .nav-link,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-link:hover,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-link:focus,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-link:active,
        body .main-sidebar .nav-sidebar .nav-treeview .nav-link.active,
        body .sidebar-dark-info .nav-sidebar .nav-treeview > .nav-item > .nav-link,
        body .sidebar-dark-info .nav-sidebar .nav-treeview > .nav-item > .nav-link:hover,
        body .sidebar-dark-info .nav-sidebar .nav-treeview > .nav-item > .nav-link:focus,
        body .sidebar-dark-info .nav-sidebar .nav-treeview > .nav-item > .nav-link:active,
        body .sidebar-dark-info .nav-sidebar .nav-treeview > .nav-item > .nav-link.active,
        body .nav-pills .nav-treeview .nav-link:hover,
        body .nav-pills .nav-treeview .nav-link:focus,
        body .nav-pills .nav-treeview .nav-link.active,
        body .nav-pills .nav-treeview .show > .nav-link {
            background: transparent !important;
            background-color: transparent !important;
            background-image: none !important;
            box-shadow: none !important;
        }

        /* Parent group headers should also not change to a blue hover block. */
        body .main-sidebar .nav-sidebar > .nav-item > .sidebar-group-toggle:hover,
        body .main-sidebar .nav-sidebar > .nav-item > .sidebar-group-toggle:focus,
        body .main-sidebar .nav-sidebar > .nav-item.menu-open > .sidebar-group-toggle,
        body .main-sidebar .nav-sidebar > .nav-item.menu-open > .sidebar-group-toggle:hover {
            background: #1f2937 !important;
            background-color: #1f2937 !important;
            background-image: none !important;
            box-shadow: none !important;
        }

        /* Active child stays visible only by text weight + left line, no filled rectangle. */
        body .main-sidebar .nav-sidebar .nav-treeview .nav-link.active {
            color: #ffffff !important;
            font-weight: 700 !important;
            border-left: 4px solid #2dd4bf !important;
        }
    </style>

    <style id="final-sidebar-fit-fix">
        /* FINAL SIDEBAR FIT FIX
           Prevents horizontal scrolling caused by long labels/email and keeps sidebar items inside the sidebar width. */
        html, body {
            max-width: 100%;
            overflow-x: hidden !important;
        }

        body .custom-user-sidebar,
        body .custom-user-sidebar .sidebar,
        body .custom-user-sidebar .nav-sidebar,
        body .custom-user-sidebar .nav-item,
        body .custom-user-sidebar .nav-link,
        body .custom-user-sidebar .nav-treeview {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        body .custom-user-sidebar .brand-link,
        body .custom-user-sidebar .brand-text,
        body .custom-user-sidebar .user-panel,
        body .custom-user-sidebar .user-panel .info,
        body .custom-user-sidebar .user-panel .info a,
        body .custom-user-sidebar h5,
        body .custom-user-sidebar .nav-link p,
        body .custom-user-sidebar .nav-link span {
            max-width: 100% !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        body .custom-user-sidebar .nav-link {
            width: auto !important;
            min-width: 0 !important;
            padding-right: 10px !important;
            display: flex !important;
            align-items: center !important;
        }

        body .custom-user-sidebar .nav-link .nav-icon {
            flex: 0 0 22px !important;
            width: 22px !important;
            min-width: 22px !important;
            margin-right: 8px !important;
            text-align: center !important;
        }

        body .custom-user-sidebar .nav-link p {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            margin: 0 !important;
        }

        body .custom-user-sidebar .nav-treeview .nav-link {
            padding-left: 14px !important;
            margin-left: 4px !important;
            margin-right: 4px !important;
        }

        body .custom-user-sidebar .nav-treeview .nav-link .nav-icon {
            flex-basis: 18px !important;
            width: 18px !important;
            min-width: 18px !important;
            margin-right: 7px !important;
        }

        /* Keep sidebar usable on smaller screens without forcing horizontal scroll. */
        /* Sidebar profile/top area above Dashboard: prevent long email/name from widening sidebar */
        body .custom-user-sidebar .sidebar > .p-0,
        body .custom-user-sidebar .sidebar .py-3,
        body .custom-user-sidebar .sidebar .d-flex,
        body .custom-user-sidebar .sidebar .text-center,
        body .custom-user-sidebar .sidebar .align-items-center {
            max-width: 100% !important;
            min-width: 0 !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        body .custom-user-sidebar .sidebar h5,
        body .custom-user-sidebar .sidebar .mx-2,
        body .custom-user-sidebar .sidebar .text-white,
        body .custom-user-sidebar .sidebar .user-panel .info a {
            max-width: 185px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            display: block !important;
        }

        body .custom-user-sidebar .sidebar img.rounded-circle,
        body .custom-user-sidebar .sidebar .sidebar-user-avatar {
            width: 50px !important;
            height: 50px !important;
            min-width: 50px !important;
            max-width: 50px !important;
            object-fit: cover !important;
        }

        body .custom-user-sidebar .sidebar .sidebar-user-profile {
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            box-sizing: border-box !important;
        }

        body .custom-user-sidebar .sidebar .sidebar-user-name,
        body .custom-user-sidebar .sidebar .sidebar-user-username {
            width: 100% !important;
            max-width: 210px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            display: block !important;
            text-align: center !important;
            line-height: 1.2 !important;
        }

        body .custom-user-sidebar .sidebar .sidebar-user-name {
            font-size: 14px !important;
            font-weight: 700 !important;
        }

        body .custom-user-sidebar .sidebar .sidebar-user-username {
            font-size: 11px !important;
            color: #cbd5e1 !important;
        }

        body .custom-user-sidebar .sidebar .rounded-pill {
            max-width: 215px !important;
            overflow: hidden !important;
            white-space: nowrap !important;
            font-size: 12px !important;
        }

        /* Top navbar also must not force horizontal page scrolling */
        body .main-header.navbar,
        body .main-header .navbar-collapse,
        body .main-header .navbar-nav,
        body .main-header .user-panel,
        body .main-header .info,
        body .main-header .info a {
            max-width: 100% !important;
            min-width: 0 !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        body .main-header .info a {
            max-width: 160px !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }


        /* Works on all screen sizes. We do not rely on AdminLTE control-sidebar JS. */
        body.profile-menu-open .control-sidebar.profile-drop {
            display: block !important;
            right: 0 !important;
            z-index: 30000 !important;
        }
        body.profile-menu-open .control-sidebar.profile-drop::before {
            display: block !important;
            right: 0 !important;
        }

        /* Profile/control sidebar menu must be visible and scrollable on small screens. */
        #drop-btn {
            cursor: pointer !important;
            pointer-events: auto !important;
        }

        body .profile-drop {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            z-index: 20000 !important;
        }

        body .profile-drop .profile-menu-panel {
            padding: 16px !important;
            margin-left: 0 !important;
            min-width: 0 !important;
            max-width: 100% !important;
        }

        body .profile-drop .profile-menu-title {
            color: #ffffff !important;
            font-size: 15px !important;
            border-bottom: 1px solid rgba(255,255,255,.15);
            padding-bottom: 10px;
        }

        body .profile-drop .profile-action-link {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 0 12px 0 !important;
            padding: 8px 6px !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }

        body .profile-drop .profile-action-link i {
            flex: 0 0 26px !important;
            width: 26px !important;
            min-width: 26px !important;
            text-align: center !important;
        }

        body .profile-drop .profile-action-link a,
        body .profile-drop .profile-action-link span {
            min-width: 0 !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
            color: #ffffff;
            font-weight: 600;
        }

        body .profile-drop .dropdown {
            position: static !important;
            float: none !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }

        body .profile-drop .dropdown.showDropdown {
            display: block !important;
        }

        @media (max-width: 767.98px) {
            body .control-sidebar.profile-drop {
                position: fixed !important;
                top: 0 !important;
                right: -280px !important;
                bottom: 0 !important;
                width: 280px !important;
                max-width: 86vw !important;
                height: 100vh !important;
                padding-top: 10px !important;
                background: #050052 !important;
                display: block !important;
                transition: right .25s ease-in-out !important;
            }

            body.control-sidebar-slide-open .control-sidebar.profile-drop,
            body.control-sidebar-open .control-sidebar.profile-drop,
            body.profile-menu-open .control-sidebar.profile-drop {
                right: 0 !important;
            }

            body .profile-drop .profile-menu-panel {
                padding: 16px 14px 24px !important;
            }

            body .profile-drop .profile-action-link {
                font-size: 14px !important;
            }

            body .profile-drop .profile-action-link i {
                font-size: 21px !important;
            }

            body .custom-user-sidebar {
                width: 250px !important;
                max-width: 250px !important;
                overflow-x: hidden !important;
            }
            body .custom-user-sidebar .nav-link {
                font-size: 13px !important;
                padding-left: 10px !important;
                padding-right: 8px !important;
            }
            body .custom-user-sidebar .nav-treeview .nav-link {
                font-size: 12px !important;
                padding-left: 12px !important;
            }
            body .custom-user-sidebar .sidebar h5,
            body .custom-user-sidebar .sidebar .mx-2,
            body .custom-user-sidebar .sidebar .text-white,
            body .custom-user-sidebar .sidebar .user-panel .info a {
                max-width: 160px !important;
            }
            body .custom-user-sidebar .sidebar .rounded-pill {
                max-width: 200px !important;
            }
            body .content-wrapper,
            body .main-header,
            body .main-footer {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            body .main-header.navbar {
                flex-wrap: nowrap !important;
            }
            body .main-header .navbar-collapse {
                overflow: hidden !important;
            }
            body .main-header .btn {
                font-size: 11px !important;
                padding: 5px 8px !important;
                white-space: nowrap !important;
            }
        }
    </style>


    <style id="final-profile-mobile-visibility-fix">
        /* On small screens the expanded top navbar can cover the top of the profile/control sidebar.
           Hide that expanded navbar only while the profile sidebar is open, and put the sidebar above it. */
        @media (max-width: 767.98px) {
            body.control-sidebar-slide-open #navbarSupportedContent,
            body.control-sidebar-open #navbarSupportedContent,
            body.profile-menu-open #navbarSupportedContent {
                display: none !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            body .control-sidebar.profile-drop {
                position: fixed !important;
                top: 0 !important;
                right: -260px !important;
                bottom: 0 !important;
                width: 250px !important;
                max-width: 86vw !important;
                height: 100vh !important;
                z-index: 30000 !important;
                display: block !important;
                padding-top: 14px !important;
                overflow-y: auto !important;
                overflow-x: hidden !important;
                background: #050052 !important;
                transition: right .25s ease-in-out !important;
            }

            body.control-sidebar-slide-open .control-sidebar.profile-drop,
            body.control-sidebar-open .control-sidebar.profile-drop,
            body.profile-menu-open .control-sidebar.profile-drop {
                right: 0 !important;
            }

            body .control-sidebar.profile-drop > .p-2 {
                margin-left: 0 !important;
                padding: 14px !important;
            }

            body .control-sidebar.profile-drop p {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                margin: 0 0 13px 0 !important;
                padding: 2px 0 !important;
                max-width: 100% !important;
                overflow: hidden !important;
            }

            body .control-sidebar.profile-drop p i {
                flex: 0 0 26px !important;
                width: 26px !important;
                min-width: 26px !important;
                text-align: center !important;
                font-size: 22px !important;
            }

            body .control-sidebar.profile-drop p a,
            body .control-sidebar.profile-drop p span {
                min-width: 0 !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                font-size: 14px !important;
                font-weight: 700 !important;
            }

            body .control-sidebar.profile-drop .dropdown {
                position: static !important;
                float: none !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            body .control-sidebar.profile-drop .dropdown.showDropdown {
                display: block !important;
            }
        }
    </style>

    <style id="final-sidebar-hover-style">
        /* Clean sidebar hover: visible without the large blue/blur background */
        body .custom-user-sidebar .nav-sidebar > .nav-item > .nav-link:hover,
        body .custom-user-sidebar .nav-sidebar > .nav-item > .sidebar-group-toggle:hover {
            background: #243044 !important;
            background-color: #243044 !important;
            color: #ffffff !important;
            border-left-color: #38bdf8 !important;
            box-shadow: none !important;
            transform: translateX(2px);
        }

        body .custom-user-sidebar .nav-sidebar > .nav-item > .nav-link:hover .nav-icon,
        body .custom-user-sidebar .nav-sidebar > .nav-item > .nav-link:hover p,
        body .custom-user-sidebar .nav-sidebar > .nav-item > .sidebar-group-toggle:hover .nav-icon,
        body .custom-user-sidebar .nav-sidebar > .nav-item > .sidebar-group-toggle:hover p {
            color: #ffffff !important;
        }

        /* Submenu hover: text/icon highlight only; no filled rectangle */
        body .custom-user-sidebar .nav-treeview .nav-link:hover,
        body .custom-user-sidebar .nav-treeview .nav-link:focus {
            background: transparent !important;
            background-color: transparent !important;
            color: #ffffff !important;
            border-left-color: #38bdf8 !important;
            box-shadow: none !important;
            transform: translateX(2px);
        }

        body .custom-user-sidebar .nav-treeview .nav-link:hover .nav-icon,
        body .custom-user-sidebar .nav-treeview .nav-link:hover p,
        body .custom-user-sidebar .nav-treeview .nav-link:focus .nav-icon,
        body .custom-user-sidebar .nav-treeview .nav-link:focus p {
            color: #ffffff !important;
        }

        /* Keep the currently selected submenu readable */
        body .custom-user-sidebar .nav-treeview .nav-link.active {
            background: transparent !important;
            background-color: transparent !important;
            color: #ffffff !important;
            border-left-color: #2dd4bf !important;
            font-weight: 700 !important;
        }
    </style>

</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/user-dashboard-base.blade.php ENDPATH**/ ?>
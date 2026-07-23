


<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\User;


$user = Auth::user();
$name = $user->name;
$userr = $user->user;
$email = $user->email;
$package = $user->has_paid_package;
$package1 = $user->has_paid_package;
switch ($package) {
    case 'ft':
        $package = 'FT';
        break;
    case 'fc1':
        $package = 'FC $100';
        break;
    case 'fc2':
        $package = 'FC $200';
        break;
}
$ref_code = $user->activation;

$jsonString = file_get_contents(resource_path('dummydata/task.json'));
$data = json_decode($jsonString);

$totalSum = 0;




$deposit = db::SELECT("SELECT user, SUM(deposit) as deposit FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);
?>

<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row mb-2">
                <div style="padding: 10px 0;height:100%;">
                    <div>

                        <style>
                            .form-control-smaller {
                                outline: none;
                                font-size: 12px;

                            }

                            .cc .col {
                                background-color: #fff5aa;
                                border-radius: 12px;
                                /* Add desired background color */
                                border: 1px solid #ccc;
                                /* Add desired border style */
                                display: flex;
                                width: 12px;
                                border: 4px solid white;
                                height: 100px;
                                text-align: center;
                                left: 40px;
                                padding-top: 30px;
                                padding-left: 25px;
                            }

                            #founder {
                                background-color: lightgreen;
                                border-raius: 1px solid #ccc;
                                display: flex;
                                width: 80px;
                                height: 100px;
                                text-align: center;
                                left: 80px;
                                padding-top: 30px;
                                padding-left: 45px;
                            }

                            .coming .info-box {
                                display: flex;
                                width: 100%;
                                height: 100%;
                                justify-content: center;
                                text-align: center;
                                float: right;
                                padding-top: 5px;
                            }


                            @media (max-width: 768px),

                            @media  screen and (min-width: 768px) {
                                .cc .col {
                                    /*  border: 1px solid #ccc; /* Add desired border style */
                                    */ background-color: red;
                                    width: 100%;
                                    flex: 0 0 60%;
                                    border: 4px solid white;
                                    height: 100px;
                                    text-align: center;
                                    left: 40px;
                                    padding-top: 30px;
                                    padding-left: 25px;
                                }

                                #hide {
                                    display: none;
                                }
                            }


                            .coming {
                                display: flex;
                                width: 100%;
                                height: 120px;
                                justify-content: center;
                            }
                        </style>
                        <!-- Content Wrapper. Contains page content -->


                        <marquee behavior="" direction=""><i style="color: brown">
                                Welcome To Millionaire Site, We're Here for you , Money is always eager ,
                                now you can make money online , Better choice solutions for your future finance And
                                don't Hesitate to Contact us

                            </i>
                        </marquee>


                        <div>
                            <div class="ad-main-box mt-3 mb-3">
                                <!-- <div class="" style="background-color: white;" id="hide"></div> -->
                                <div class="ad-sub-box1 ">
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-info" id="not"><a class="nav-icon fas fa-window-restore"
                                                                                href="#"><br>Acount stutus<br/><b>*******</b></a>
                                        </div>
                                        <div class="ad-box bg-success"><a href="<?php echo e(route('user.dashboard.activate')); ?>"
                                                                          class="nav-icon fas fa-window-restore"><br>Activate
                                                <br> package</a></div>
                                    </div>
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-primary"><a class="nav-icon fas fa-wallet" href="#"><br>Cash&deposit
                                                <br>0:
                                                <?php
                                                if (count($deposit)) {
                                                    foreach ($deposit as $deposited) {
                                                        echo "0";
                                                    }
                                                } else {
                                                    echo "<br> 0";
                                                }
                                                ?></a></a> </div>
                                        <div class="ad-box bg-danger"><a class="nav-icon fas fa-bookmark" href="#"> <br>Cash
                                                out <br>0:0</a></div>
                                    </div>

                                </div>

                                <div class="ad-sub-box2">
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-warning mr-2 ml-1"><a class="nav-icon fas fa-money"
                                                                                    href="#"> <br>Total Direct
                                                <br/>0</a></div>
                                        <div class="ad-box bg-info mr-2 ml-1"><a class="nav-icon fas fa-gift" href='#'>
                                                <br>Total working team <br>0</a></div>
                                    </div>
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-success col"><a class="nav-icon fas fa-gift" href='#'>
                                                <br>Reserved_ads<br>0</a></div>
                                        <br>
                                    </div>


                                </div>


                            </div>
                            <?php


                            $reserved = db::SELECT("SELECT user, SUM(reserved_token) as token FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);
                            ?>

                            <div class="ad-main-box mt-3 mb-3">
                                <!-- <div class="" style="background-color: white;" id="hide"></div> -->
                                <div class="ad-sub-box1 ">
                                    <div class="sub-box-1">
                                        <div class=" ad-box bg-success "><a class="nav-icon fas fa-gear"
                                                                            href="<?php echo e(route('user.package.history')); ?>"><br>My
                                                package</a></div>
                                        <div class="ad-box bg-primary"><a class="nav-icon fas fa-user" href="#"><br>My
                                                invetees</a></div>
                                    </div>
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-danger"><a class="nav-icon fas fa-users" href="#"><br>My
                                                Team</a></div>
                                        <div class="ad-box bg-warning "><a class="nav-icon fas fa-gift" href="#"><br>Coin
                                                <br> coming soon</a></div>
                                    </div>

                                </div>

                                <div class="ad-sub-box2">
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-info mr-2 ml-1"><a class="nav-icon fas fa-gift"
                                                                                 href="#"><br>Total <br> Balance</a>
                                        </div>
                                        <div class="ad-box bg-success mr-2 ml-1"><a class="nav-icon fas fa-gift"
                                                                                    href="#"><br>Reseved_token

                                                <?php
                                                if (count($reserved)) {
                                                    foreach ($reserved as $token) {
                                                        echo "<br>" . $token->token;
                                                    }
                                                } else {
                                                    echo "<br> 0";
                                                }
                                                ?></a>
                                        </div>
                                    </div>
                                    <div class="sub-box-1">
                                        <div class="ad-box bg-info col"><a class="nav-icon fas fa-gift" href="#"><br>Wallet
                                                Balance <br> coming soon</a></div>
                                    </div>


                                </div>


                            </div>
                        </div>

                        <!-- Content Header (Page header) -->
                        <div class="content-header">
                            <div class="container-fluid">
                                <!-- /.row -->

                                <!-- /.content-header -->
                                <div class="ref row">
                                    <div class="col-md-9 mx-div">
                                        <div class="row row1">
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-info elevation-1"><i
                                                                class="fas fa-list"></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Clicks</span>
                                                            <span class="info-box-number">

                             </span>
                                                        </div>

                                                    </div>
                                                </a>

                                            </div>
                                            <!-- /.col -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="<?php echo e(route('user.referral.show')); ?>">
                                                    <div class="info-box mb-3">
                                                        <span class="info-box-icon bg-danger elevation-1"><i
                                                                class="fas fa-users"></i></span>
                                                        <?php
                                                        $referrals = db::SELECT("SELECT * from users where referee_id= :ref", ['ref' => $ref_code]);

                                                        $earning = db::SELECT("SELECT user, SUM(earning) as earn FROM balances WHERE user = :user GROUP BY user", ['user' => $userr]);




                                                        $task = db::SELECT("SELECT * FROM users join activations on(users.email=activations.email) join tasks ON(activations.code=tasks.code)  WHERE users.has_paid_package='ft' and  activations.package='ft' and users.email='$email'");
                                                        ?>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Referrals</span>
                                                            <span class="info-box-number"><?php echo e(count($referrals)); ?></span>
                                                        </div>

                                                    </div>
                                                </a>

                                            </div>
                                            <!-- /.col -->

                                            <!-- fix for small devices only -->
                                            <div class="clearfix hidden-md-up"></div>

                                            <div class="col-12 col-sm-6 col-md-3">
                                                <a href="<?php echo e(route('user.task.show')); ?>">
                                                    <div class="info-box mb-3">
                                                        <span class="info-box-icon bg-success elevation-1"><i
                                                                class="fas fa-list"></i></span>

                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Tasks</span>
                                                            <span class="info-box-number"><?php echo e(count($task)); ?></span>
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>

                                            <!-- /.col -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <div class="info-box mb-3">
                                                    <span class="info-box-icon bg-warning elevation-1"><i
                                                            class="fas fa-users"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Earnings</span>
                                                        <span class="info-box-number"><?php
                                                                                      if (count($earning)) {
                                                                                          foreach ($earning as $earned) {
                                                                                              echo $earned->earn;
                                                                                          }
                                                                                      } else {
                                                                                          echo "0";
                                                                                      }
                                                                                      ?></span>
                                                    </div>

                                                </div>

                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </div>

                                    <div class="row2 col-md-3 mx-div zoom">
                                        <span style="color:#dc3545; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;coming soon</span>
                                        <div class="subZoom row">
                                            <div class="img col-2">
                                                <div class="zoomImg">
                                                    <img src="<?php echo e(asset('assets/a/img/zoom.png')); ?>" alt="">
                                                </div>
                                            </div>
                                            <div class="col-10 zoom-content">

                                                <div>
                                                    <span class="text-warning">  we are on zoom</span><br>
                                                    <span class="font-weight-bold"><a
                                                            href="#">CLICK  HERE  TO JOIN US</a></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.container-fluid -->
                            </div>
                            <!-- /.row ENDING RIGHT HERE AND BE READY TO START -->
                            <!-- Main content -->
                            <div class="content">
                                <div class="container-fluid ">

                                    <div class="row ">
                                        <div class="card card-primary card-outline col-lg-4">

                                            <h5 class="card-title">
                                                Refferral id:
                                                <button class="btn btn-default font-weight-bold" type="submit"
                                                        id="btn2">
                                                    <i class="las la-link" style="font-size: 20px;"></i>
                                                    <input type="hidden" value="<?php echo e($ref_code); ?>" id="link1">
                                                    <a href="#" style="color:red;text-decoration:none;">
                                                        <?php if($package!='standard'): ?>
                                                            <span style="visibility: visible;">*********</span>
                                                        <?php else: ?>
                                                            <span style="visibility: visible;">*********</span>
                                                        <?php endif; ?>
                                                    </a>
                                            </h5>
                                            <br>
                                            </button>


                                            <div class="input-group">
                                                <?php if($package!='standard'): ?>
                                                    <input type="text" id="link"
                                                           value="http://fonepo.com/register?referral=*********"
                                                           class="form-control" readonly class="form-control">
                                                    <button class="btn btn-default" type="submit" id="btn">
                                                        <i class="las la-link" style="font-size: 20px;"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <input type="text" id="link"
                                                           value="http://fonepo.com/register?referral=*******"
                                                           class="form-control" readonly class="form-control">
                                                <?php endif; ?>

                                            </div>

                                            <script type="text/javascript">
                                                function getreferral() {
                                                    var package = "<?php echo $package ?>";
                                                    if (package == "standard") {
                                                        alert('Activate package to be allowed for this feature')
                                                    } else {
                                                        var codeinput = document.getElementById('link1');
                                                        var newcode = codeinput.value;
                                                        var tempInput = document.createElement("input");
                                                        tempInput.value = newcode;
                                                        document.body.appendChild(tempInput);
                                                        tempInput.select();
                                                        document.execCommand("copy");
                                                        document.body.removeChild(tempInput);
                                                        alert('Referral code copied to clickboard ');
                                                    }
                                                }

                                                var copybtn = document.getElementById('btn2');
                                                copybtn.addEventListener("click", getreferral);

                                            </script>

                                            <br>
                                            <p class="card-text">
                                                <i class="fas fa-arrow-circle-right"></i> Share this link and get 500
                                                coin when they activate account!
                                            </p>
                                            <!-- <a href="#" id="btn">  <img src="https://cdn-icons-png.flaticon.com/128/455/455691.png" style="width:20px;height: 20px;float: left;">
                                        &nbsp;&nbsp;Copy Link</a></i><br> -->

                                        </div>
                                        <div class="col-lg-5">

                                            <div class="col-sm-md-4">
                                                <!-- Map card -->
                                                <div class="card bg-gradient-primary">
                                                    <div class="card-header border-0 " style="position: relative;">
                                                        <h3 class="card-title">
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Survey <i
                                                                style="color:#dc3545; font-size: 15px; position: absolute; right:20px;">comming
                                                                soon</i>
                                                        </h3>

                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <div class="card-body"
                                                         style="background-color:white;height: 180px; width: 100%; ">
                                                        <center><a href="#">
                                                                <h4>Earn $30 By SURVEYS & APPS</h4>
                                                                <i class="fas fa-share "></i>
                                                            </a>
                                                        </center>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $fcuser = db::SELECT("SELECT * from users where has_paid_package= 'fc1' or has_paid_package= 'fc2'");
                                        $user = db::SELECT("SELECT * from users");


                                        ?>
                                        <div class="col-lg-3" style="margin-right:0px;">
                                            <div class="">
                                                <div class="info-box mb-3 w-100" style="width: 250px;height: 70px;">
                                                    <span class="info-box-icon  elevation-1"><i
                                                            class="fas fa-users text-primary"></i></span>

                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Total Founders</span>
                                                        <span class="info-box-number"><?php echo e(count($fcuser)+50); ?></span>
                                                    </div>

                                                </div>
                                                <div class="info-box mb-3 w-100 top-users"
                                                     style="width: 250px;height: 130px;">
                                                    <div class="topUser_title">Top Users</div>
                                                    <div class="soon">comming soon</div>
                                                    <div class="topUser_img">
                                                        <img src="<?php echo e(asset('assets/a/img/team.png')); ?>" alt="">
                                                    </div>
                                                    <div class="Usersmsg">
                                                        <span class=""> No users joined yet</span>


                                                    </div>

                                                </div>

                                            </div>
                                            <script type="text/javascript">
                                                function copylink() {
                                                    var package = "<?php echo $package ?>";
                                                    if (package == "standard") {
                                                        alert('Activate package to be allowed for this feature')
                                                    } else {
                                                        var linkinput = document.getElementById('link');
                                                        linkinput.select();
                                                        document.execCommand("copy");
                                                        alert("Link copied to clipbord");
                                                    }
                                                }

                                                var copybtn = document.getElementById('btn');
                                                copybtn.addEventListener("click", copylink);
                                            </script>


                                            
                                        </div>
                                        <span style="color: blue;font-size: 20px;"><b>Upcoming Projects</b> </span>

                                        <div class="row">
                                            <div class="comingSoon col-md-5">
                                                <div class="row my-2">

                                                    <div class="col-12 col-sm-6 col-md-6">
                                                        <div class="info-box main-upcoming">
                                                            <div class="upcoming ">
                                                                <div>
                                                                    <img src="<?php echo e(asset('assets/a/img/upcoming.png')); ?>"
                                                                         alt="">
                                                                </div>
                                                                <div class="info-box-content text-center">
                                                                    <b></b>Future shoop
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-12 col-sm-6 col-md-6">

                                                        <div class="info-box mb-3  main-upcoming">
                                                            <div class="upcoming">
                                                                <div>
                                                                    <img src="<?php echo e(asset('assets/a/img/booking.png')); ?>"
                                                                         alt="">
                                                                </div>
                                                                <div class="info-box-content text-center">
                                                                    <b>booking system</b>
                                                                    <!--Your fovorite booking site-->
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <!-- /.col -->

                                                    <!-- fix for small devices only -->
                                                    <div class="clearfix hidden-md-up"></div>

                                                    <div class="col-12 col-sm-6 col-md-6">

                                                        <div class="info-box mb-3  main-upcoming">
                                                            <div class="upcoming">
                                                                <div>
                                                                    <img src="<?php echo e(asset('assets/a/img/shopping.png')); ?>"
                                                                         alt="">
                                                                </div>
                                                                <div class="info-box-content text-center">
                                                                    <b>Market Place</b>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <!-- /.col -->
                                                    <div class="col-12 col-sm-6 col-md-6">

                                                        <div class="info-box mb-3  main-upcoming">
                                                            <div class="upcoming">
                                                                <div>
                                                                    <img src="<?php echo e(asset('assets/a/img/crypto.png')); ?>"
                                                                         alt="">
                                                                </div>
                                                                <div class="info-box-content text-center">
                                                                    <b>crypto loans </b>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>


                                            <div class="clickEvents col-md-7 py-2">
                                                <div class="container-fluid">
                                                    <span style="color:#dc3545; font-size: 15px;">comming soon</span>
                                                    <div class="main_comingBox row d-flex justify-content-between ">
                                                        <div class="comingBox col-12 col-sm-6 col-md-5">

                                                            <div class="row">
                                                                <div class="col-8 zoom-content">
                                                                    <div>
                                                                        <span><a
                                                                                href="#">CLICK  HERE  TO </a></span><br>
                                                                        <span>  STAKE YOUR TOKENS</span><br>
                                                                    </div>

                                                                </div>

                                                                <div
                                                                    class=" col-3 d-flex justify-content-end align-items-center">
                                                                    <div class="icon">
                                                                        <i class="las la-external-link-alt"></i>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="comingBox col-12 col-sm-6 col-md-5">
                                                            <div class="row">
                                                                <div class="col-8 zoom-content">
                                                                    <div>
                                                                        <span><a
                                                                                href="#">CLICK  HERE  TO </a></span><br>
                                                                        <span> UPGRADE MEMBERSHIP</span><br>
                                                                    </div>

                                                                </div>

                                                                <div
                                                                    class=" col-3 d-flex justify-content-end align-items-center">
                                                                    <div class="icon">
                                                                        <i class="las la-external-link-alt"></i>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="main_comingBox row d-flex justify-content-between ">
                                                        <div class="comingBox col-12 col-sm-6 col-md-5">
                                                            <div class="row">
                                                                <div class="col-8 zoom-content">
                                                                    <div>
                                                                        <span><a
                                                                                href="#">CLICK  HERE  TO </a></span><br>
                                                                        <span> UNSTAKE </span><br>
                                                                    </div>

                                                                </div>

                                                                <div
                                                                    class=" col-3 d-flex justify-content-end align-items-center">
                                                                    <div class="icon">
                                                                        <i class="las la-external-link-alt text-black "></i>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="comingBox col-12 col-sm-6 col-md-5">
                                                            <div class="row">
                                                                <div class="col-8 zoom-content">
                                                                    <div>
                                                                        <span><a
                                                                                href="#">CLICK  HERE  TO </a></span><br>
                                                                        <span>TOKEN EARNINGS </span><br>
                                                                    </div>

                                                                </div>

                                                                <div
                                                                    class=" col-3 d-flex justify-content-end align-items-center">
                                                                    <div class="icon">
                                                                        <i class="las la-external-link-alt text-white "></i>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="findUs p-3">
                                                        <div class="text-white text-center">Add Your self to our <a
                                                                href="#"> <i class="lab la-facebook-f"></i>FaceBook page</a>
                                                            <a href="#"><i
                                                                    class="lab la-telegram-plane"></i>Telegram</a></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row w-100" style="margin-top:15px">
                                            <div class="col-md-6">
                                                <div class="card direct-chat direct-chat-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title">LEADERBOARD</h3>

                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="collapse">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-tool"
                                                                    data-toggle="tooltip" title="Contacts"
                                                                    data-widget="chat-pane-toggle">
                                                                <i class="fas fa-comments"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-tool"
                                                                    data-card-widget="remove"><i
                                                                    class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="direct-chat-messages">

                                                            <table class="table">
                                                                <thead class="bg-gradient-primary">
                                                                <tr>
                                                                    <th>Username</th>
                                                                    <th></th>
                                                                    <th>Total Earned</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <!--         <td>1. John</td>
                                                                            <td></td>
                                                                            <td>$0</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2. Mary</td>
                                                                            <td></td>
                                                                            <td>$0</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3. July</td>
                                                                            <td></td>
                                                                            <td>$0</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4. July</td>
                                                                            <td></td>
                                                                            <td>$0</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5. JackMan
                                                                            </td>
                                                                            <td></td>
                                                                            <td>$0</td>
                                                                        </tr> -->
                                                                </tbody>
                                                            </table>


                                                        </div>


                                                    </div>

                                                </div>

                                                


                                            </div>
                                            <div class="col-md-6">

                                                <div class="col-md-12">
                                                    <div class="card direct-chat direct-chat-primary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Social Media Share</h3>

                                                            <div class="card-tools">
                                                                <button type="button" class="btn btn-tool"
                                                                        data-card-widget="collapse">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-tool"
                                                                        data-card-widget="remove"><i
                                                                        class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-header -->
                                                        <div class="card-body">
                                                            <div class="direct-chat-messages">
                                                                <!-- Add font awesome icons -->
                                                                <div
                                                                    class="px-3 d-flex justify-content-between align-items-center ">
                                                                    <div class="link-icon">
                                                                        <div><a href="#" onclick="shareOnFacebook()"
                                                                                class="links media_fb"><i
                                                                                    class=" lab la-facebook-f "></i></a>
                                                                        </div>
                                                                        <div class="">Facebook</div>
                                                                    </div>
                                                                    <div>
                                                                        <button class=" btn btn-success"
                                                                                onclick="shareOnFacebook()">share link
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <div
                                                                    class="px-3 d-flex justify-content-between align-items-center">
                                                                    <div class="link-icon">
                                                                        <div><a href="#" class="links media_tw"><i
                                                                                    class=" lab la-twitter"></i></a>
                                                                        </div>
                                                                        <div class="">Twitter</div>
                                                                    </div>
                                                                    <div>
                                                                        <button class=" btn btn-success"
                                                                                onclick="shareOnTwitter()">share link
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <div
                                                                    class="px-3 d-flex justify-content-between align-items-center">
                                                                    <div class="link-icon">
                                                                        <div><a href="#" class="links media_wt"><i
                                                                                    class=" lab la-whatsapp"></i></a>
                                                                        </div>
                                                                        <div class="">Whatsapp</div>
                                                                    </div>
                                                                    <div>
                                                                        <button class=" btn btn-success"
                                                                                onclick="shareOnWhatsApp()">share link
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <!--<hr>-->

                                                                <!--<div class="px-3 d-flex justify-content-between align-items-center">  -->
                                                                <!--       <div class="link-icon">  -->
                                                                <!--           <div><a href="#" class="links media_in"><i class=" lab la-instagram"></i></a></div> -->
                                                                <!--           <div class="">Instagram</div>-->
                                                                <!--       </div> -->
                                                                <!--   <div>-->
                                                                <!--       <button class=" btn btn-success" onclick="shareOnInstagram()">share link</button>-->
                                                                <!--   </div>-->
                                                                <!--</div>-->
                                                                <!--<hr>-->
                                                                <!--<div class="px-3 d-flex justify-content-between align-items-center">  -->
                                                                <!--       <div class="link-icon">  -->
                                                                <!--           <div><a href="#" class="links media_pi"><i class=" lab la-pinterest"></i></a></div> -->
                                                                <!--           <div class="">Pinterest</div>-->
                                                                <!--       </div> -->
                                                                <!--   <div>-->
                                                                <!--       <button class=" btn btn-success" onclick="shareOnInstagram()">share link</button>-->
                                                                <!--   </div>-->
                                                                <!--</div>-->
                                                                <hr>

                                                            </div>


                                                        </div>
                                                    </div>

                                                </div>


                                            </div>


                                            <!-- js share logic -->
                                            <script async defer crossorigin="anonymous"
                                                    src="https://connect.facebook.net/en_US/sdk.js"></script>

                                            <script
                                                src="https://cdn.jsdelivr.net/npm/whatsapp-web@1.9.0/dist/browser.js"></script>
                                            <script async src="https://platform.twitter.com/widgets.js"
                                                    charset="utf-8"></script>


                                            <!-- Add your own JavaScript code -->
                                            <script>

                                                // document.getElementById('facebook-share-button').addEventListener('click', shareOnFacebook);
                                                document.getElementById('whatsapp-share-button').addEventListener('click', shareOnWhatsApp);
                                                document.getElementById('twitter-share-button').addEventListener('click', shareOnTwitter);


                                                // Function to handle the Facebook sharing
                                                function shareOnFacebook() {
// Replace "YOUR_SHARE_URL" with the URL you want to share
                                                    var shareUrl = 'https://www.fonepo.com/register?referral=<?php echo e($ref_code); ?>';

// Open the Facebook share dialog
                                                    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl), '_blank');
                                                }

                                                // Function to handle the WhatsApp sharing
                                                function shareOnWhatsApp() {
                                                    // Replace 'YOUR_SHARE_TEXT' with the desired text to share
                                                    var shareText = encodeURIComponent('Infinite earning: https:www.fonepo.com/register?referral=<?php echo e($ref_code); ?>');
                                                    var whatsappURL = 'https://api.whatsapp.com/send?text=' + shareText;
                                                    window.open(whatsappURL, '_blank');
                                                }

                                                // Function to handle the Twitter sharing
                                                function shareOnTwitter() {
                                                    // Replace 'YOUR_SHARE_TEXT' with the desired text to share
                                                    var shareText = 'Infinite earning: https:www.fonepo.com/register?referral=<?php echo e($ref_code); ?>';

                                                    // Open the Twitter share popup
                                                    window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(shareText), '_blank');
                                                }

                                                // Add an event listener to the button

                                            </script>


                                            <!-- /.row -->

                                        </div>


                                        <div class="col-lg-12">
                                            <!-- Map card -->
                                            <div class="card " style="background-color:#000050; width: 100%; ">
                                                <div class="card-header " style="background-color:gray;">
                                                    <h3 class="card-title" style="color: white">
                                                        Earn Type
                                                    </h3>


                                                    <!-- /.card-tools -->
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <h3 style="color: white">Tasks</h3>
                                                        </div>
                                                        <div class="col-md-3">

                                                            <h3 style="color: white">Earn</h3>
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="row" style="margin-top:10px;">
                                                        <div class="col-md-5">
                                                            <li style="color: white">Create YouTube videos</li>
                                                        </div>
                                                        <div class="col-md-4 pt-2">
                                                            <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                                        </div>
                                                        <div class="col-md-3 btn btn-success">
                                                            50$
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="row" style="margin-top:10px;">
                                                        <div class="col-md-5">
                                                            <li style="color: white">Complete surveys</li>
                                                        </div>
                                                        <div class="col-md-4 pt-2">
                                                            <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                                        </div>
                                                        <div class="col-md-3 btn btn-success">
                                                            30$
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="row" style="margin-top:10px;">
                                                        <div class="col-md-5">
                                                            <li style="color: white">Download apps</li>
                                                        </div>
                                                        <div class="col-md-4 pt-2">
                                                            <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                                        </div>
                                                        <div class="col-md-3 btn btn-success">
                                                            20$
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="row" style="margin-top:10px;">
                                                        <div class="col-md-5">
                                                            <li style="color: white">Refer friends</li>
                                                        </div>
                                                        <div class="col-md-4 pt-2">
                                                            <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                                        </div>
                                                        <div class="col-md-3 btn btn-success">
                                                            10$
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                    <div class="row" style="margin-top:10px;">
                                                        <div class="col-md-5">
                                                            <li style="color: white">Get clicks</li>
                                                        </div>
                                                        <div class="col-md-4 pt-2">
                                                            <i style="color:#dc3545; font-size: 15px;">comming soon</i>
                                                        </div>
                                                        <div class="col-md-3 btn btn-success">
                                                            2$
                                                        </div>
                                                    </div>
                                                    <!-- /.card -->
                                                </div>

                                            </div>
                                            <!-- /.card -->

                                            <!-- solid sales graph -->

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="card card-secondary card-outline">

                                                <div class="card-body card-warning">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            Total Volume
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <!-- Map card -->
                                                                    <div class="card bg-gradient-primary">

                                                                        <div class="card-body"
                                                                             style="background-color:white;color:black;height: 180px; width: 100%; ">
                                                                            <h5><b class="text-warning">Members:</b> 0
                                                                            </h5>

                                                                            <p>
                                                                            <h4>0 <small>Vp</small></h4>
                                                                            </p>


                                                                            <h3 class="text-success">
                                                                                <i class="fas fa-users mr-1"></i>
                                                                                Earn Bonus
                                                                            </h3>
                                                                            <hr>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- solid sales graph -->
                                                            <div class="row">

                                                                <div class="col-lg-12">
                                                                    <!-- Map card -->
                                                                    <div class="card bg-gradient-primary">

                                                                        <div class="card-body"
                                                                             style="background-color:white;color:black;height: 180px; width: 100%; ">
                                                                            <h5><b class="text-warning">Members:</b> 0
                                                                            </h5>

                                                                            <p>
                                                                            <h4>0 <small>Vp</small></h4>
                                                                            </p>

                                                                            <h3 class="text-success">
                                                                                <i class="fas fa-users mr-1"></i>
                                                                                Earn Bonus
                                                                            </h3>
                                                                            <hr>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- solid sales graph -->
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            Commissions
                                                            <div class="card">

                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6"></div>
                                                                        <div class="col-md-6">
                                                                            <select name="" id=""
                                                                                    class="form-control float-sm-right float-md-right">
                                                                                <option value="">This week</option>
                                                                            </select>

                                                                        </div>


                                                                    </div>
                                                                    <br><br>
                                                                    <div class="row">
                                                                        <div class="col-md-4"></div>
                                                                        <!-- /.col -->
                                                                        <div class="col-md-6">
                                                                            <div class="col-6 text-center">
                                                                                <input type="text" class="knob" readonly
                                                                                       value="0"
                                                                                       data-width="90" data-height="90"
                                                                                       data-fgColor="#39CCCC">

                                                                                <!--    </div>-->

                                                                                <!--    <b> Week 27 July - 02 August</b>-->

                                                                                <!--</div>-->
                                                                                <div class="col-md-3">

                                                                                </div>
                                                                                <!-- /.col -->
                                                                            </div>
                                                                            <br>

                                                                            <div class="row">
                                                                                <div class="col-sm-3 col-6">
                                                                                    <div
                                                                                        class="description-block border-right">
                                                                                        <span
                                                                                            class="description-text"><b>Earn vp</b></span><br>
                                                                                        <h5 class="description-header">0
                                                                                            Vp</h5>

                                                                                    </div>
                                                                                    <!-- /.description-block -->
                                                                                </div>
                                                                                <!-- /.col -->
                                                                                <div class="col-sm-6 col-6">
                                                                                    <div
                                                                                        class="description-block border-right">
                                                                                        <span class="description-text"> <b>Volume Bonus</b></span><br>
                                                                                        <h5 class="description-header">0
                                                                                            EUR</h5>

                                                                                    </div>
                                                                                    <!-- /.description-block -->
                                                                                </div>
                                                                                <!-- /.col -->
                                                                                <div class="col-sm-3 col-6">
                                                                                    <div class="description-block">
                                                                                        <span
                                                                                            class="description-text"><b>Earn vp</b></span><br>
                                                                                        <h5 class="description-header">0
                                                                                            Vp</h5>

                                                                                    </div>
                                                                                    <!-- /.description-block -->
                                                                                </div>


                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                    <!-- END OF CARD -->

                                                                </div>

                                                            </div>

                                                        </div>
                                                        <!-- END OF CARD -->
                                                    </div>
                                                    <!-- /.col-md-6 -->
                                                </div>


                                            </div>


                                        </div>
                                    </div>
                                    <?php if($package=='FT'): ?>

                                        <div class="myTask">
                                            <div>
                                                <h1 class="task-head">Task progress</h1>

                                                <div class="clock">
                                                    <div class="task-content">
                                                        <div class="perc"></div>
                                                        <div class="taskText"> Task Progress</div>
                                                    </div>
                                                    <!-- this one is that yellow background indicating progress -->
                                                    <div class="indicator">
                                                        <div class="bcindicator indicatorTask"></div>
                                                    </div>
                                                    <!-- this below div contain ticks of task don't delete it -->
                                                    <div class="task-ticks">
                                                    </div>
                                                    <div class="task-ticks-miror-one"></div>
                                                    <div class="task-ticks-miror"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="jsonDataContainer">

                                        </div>
                                        <script type="text/javascript">
                                            //  all variables
                                            let perc = document.querySelector('.perc');
                                            let taskIndic = document.querySelector(".indicator");
                                            let taskIndicLine = document.querySelector(".indicatorTask");
                                            let task_content = document.querySelector(".task-content");
                                            let task_ticks = document.querySelector(".task-ticks");
                                            let sec, totalTask, completedTask = <?php echo count($referrals) ?>;

                                            let taskData = <?php echo json_encode($data); ?>;
                                            totalTask = 30;

                                            // append task tiks to html element
                                            for (let i = 1; i <= 30; i++) {
                                                task_ticks.innerHTML += `
                <span style="--i:${i}; --total: ${totalTask}"></span>
            `;
                                            }

                                            /*taskData.forEach((data, index )=> {
                                                task_ticks.innerHTML += `
                                                    <span style="--i:${index + 1}; --total: ${totalTask}"></span>
                                                `;

                                                if(data.completed == "true"){
                                                    completedTask++
                                                }
                                            });*/

                                            setInterval(myFunct, 1000);

                                            function myFunct() {
                                                let percentage = completedTask / totalTask * 100;
                                                perc.innerHTML = Math.round(percentage) + "%";
                                                indicator(Math.round(percentage));

                                                function indicator(sec) {

                                                    let progress = 180 / totalTask * completedTask;
                                                    taskIndic.style.background = `conic-gradient(var(--yellow) ${progress}deg, #000 0deg)`
                                                    taskIndicLine.style.transform = `rotate(${progress}deg)`;

                                                    if (totalTask == completedTask) {
                                                        taskIndic.style.background = `conic-gradient(var(--green) ${progress}deg, #000 0deg)`
                                                        task_content.style.color = `var(--green)`;
                                                        taskIndicLine.style.setProperty('--yellow', 'var(--green)');
                                                    }
                                                }

                                            }


                                        </script>
                                    <?php endif; ?>


                                </div>

                            </div>

                        </div>

                        <script src="<?php echo e(asset('assets/a/plugins/jquery/jquery.min.js')); ?>"></script>
                        <!-- Bootstrap 4 -->
                        <script src="<?php echo e(asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/sparklines/sparkline.js')); ?>"></script>
                        <!-- AdminLTE App -->
                        <script src="<?php echo e(asset('assets/a/plugins/jquery-ui/jquery-ui.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/dist/js/adminlte.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/dist/js/adminlte.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard2.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/dist/js/tree.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/chart.js/Chart.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/jquery-knob/jquery.knob.min.js')); ?>"></script>

                        <script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/summernote/summernote-bs4.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/plugins/moment/moment.min.js')); ?>"></script>
                        <script src="<?php echo e(asset('assets/a/dist/js/pages/dashboard3.js')); ?>"></script>

                        <script>
                            $(document).ready(function () {
                                $("#showActivation").on("click", function () {

                                    $("#activationCode").show()
                                    $("#nonClosableModal").hide()

                                })

                                $("#btn-close").on("click", function () {
                                    // alert("sfsd")
                                    $("#activationCode").hide()
                                    $("#nonClosableModal").show()
                                })
                            })
                        </script>

                        <br>
                    </div>
                </div>
            </div>
        </div>


        <style type="text/css">
            .scrolling {
                overflow-y: scroll;
            }

            .mycard {
                background-color: white;
                /* width:200px; */
            }

            .mycard2 {
                background-color: yellow;
                /* width:200px; */
            }

            @media (max-width: 767.98px) {
                .modal-dialog {
                    max-width: 99%; /* Adjust this value as needed */
                }
            }

            .scrollable-text {
                height: 80px; /* Set the desired height */
                overflow-y: scroll;
                padding: 10px;
            }

        </style>


        <!-- Modal -->

        <div class="modal fade" id="activationCode" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title " id="exampleModalLabel ">Do you have Activation Code</h5>
                        <button type="button" class="btn btn-danger btn-close" data-dismiss="modal" aria-label="Close"
                                id="btn-close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <?php
                                $msg = session('message');
                                                             if($msg!=""){
                                                              echo "<strong class='text-danger'>".$msg."</strong>";
                                                             }




                            ?>

                            <form action="<?php echo e(route("validate")); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <label>Enter Activation Code you have: </label>
                                <input type="text" class="form-control my-1" placeholder="Code"
                                       placeholder="Enter Activation Code" name="code">
                                <button class="btn btn-primary d-block w-100 mt-3" value="200">SEND</button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="nonClosableModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content"
                     style=" background: linear-gradient(190deg, #2ecd71 60%, #27ae60 40.1%); color: black; margin: 0 auto;">


                    <div class="d-flex justify-content-between p-md-2 p-3  fc_container ">
                        <a href="/">
                            <button type="button" class="btn btn-outline-info text-white btn-primary"> Back</button>
                        </a>
                        <a class="float-right" href="/user/package">
                            <button type="button" class="btn btn-outline-info text-white btn-danger"> next</button>
                        </a>
                    </div>


                    <div class="modal-body">


                        <div class="mb-2">

                            <div class="scrollable-text bg-white ">
                                <h5 class="text-center">UNIQUE VENTURE PORTFOLIO</h5>
                                <!--<span style="font-size: 13px; color: black;">
                                    You can become a Royal investor (UVP) in our company and earn up to 300% daily for 600 days and your share will be converted to liquid cash for
                                    withdrawal. Also, through our affiliate program our members can earn with COMPANY. Only Royal investors (UVP) get paid daily venture pool of
                                    whatever you invested for 600 working days (Sat & Sun not inclusive) of which daily income of pool capital 80% and 20% gas fees and in pool
                                    capital 20% in cash to an Open Account which you can withdraw from every week on Mondays and 80% in Escrow to trading their buy token and 80%
                                    Trade Account and used to buy more token.
                                    During the pre-launch, you will get the unique opportunity to build a global business in a way that has never been possible before!! As a Royal
                                    investor or a pioneer, you’ll be able to take part of the company’s success and a greater part of all future income. A Royal investor is who
                                    wants to put money in this stage and you don't need to refer anybody for earn. In during this phase prelaunch we are targeting specification
                                    number of people needed on this first phase. Contract 200% If you don’t do anything and recruit anybody you double your money and get more
                                    through every total company’s revenue. This package available only pro launch but you can buy one or more.
                                </span>-->
                                <span style="font-size: 13px; color: black;">
                            You can  become a Royal investor  if you buy (UVP license ) in our company and earn up to 300% daily
                            income and your share will be converted to liquid cash for withdrawal Also, through our affiliate
                            program our members can earn with COMPANY . only  Royal investor has (UVP License )  You get
                            paid daily venture pool  of whatever you invested any duration working days (Sat & Sun not inclusive)
                             of which daily income based of pool capital  80%  and charges liquidity pool fees   20% on license and In
                              pool capital get   25%  in cashout
                            which you can withdraw from every week on Mondays and 75%  going a trading account their used
                            to buy more token to  Escrow.
                            During the pre-launch, you will get the unique opportunity to build a global business in
                            a way that never been possible before!! As
                            Royal investor or  a pioneer, you’ll be able to take part of the company’s success and a
                            greater part of all future income. A  Royal investor  you don't need to refer anybody for earn In during
                            this phase prelaunch and  we are targeting specification number of people needed  on this first phase.
                            If you don’t do anything and recruit any body and  double  your money  join now get more through every total
                            company’s revenue . This
                             package available only pro launch but you can buy one or more
                        </span>
                            </div>
                        </div>

                        <div class="">
                            <?php
                                function abbreviateNumber($number) {
                if ($number < 1000) {
                    return $number;
                } elseif ($number < 1000000) {
                    return round($number / 1000, 1) . 'k';
                } elseif ($number < 1000000000) {
                    return round($number / 1000000, 1) . 'M';
                } elseif ($number < 1000000000000) {
                    return round($number / 1000000000, 1) . 'B';
                } else {
                    return round($number / 1000000000000, 1) . 'T';
                }
            }


                            ?>

                            <div class="row  pb-3  " style="margin:-10px">
                                <?php $__currentLoopData = $Adventures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venture): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 mt-2">
                                        <div class="mycard rounded border border-dark">
                                            <div class="m-1">
                                                <p class="text-danger text-center"
                                                   style="fon-size:10px;"><?php echo e($venture->name); ?></p>
                                                <div class="d-flex justify-content-center">
                                                    <img
                                                        src="<?php echo e(asset('image/ai-package.png')); ?>"
                                                        style="width:40px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                                </div>
                                            </div>
                                            <div class="text-center" style="background-color:black;">
                                                <span style="font-size: 11px;  margin-bottom: -15%; "
                                                      class="text-danger"><?php echo e($venture->plan); ?> <?php echo e($venture->duration); ?> days</span>
                                                <br>
                                                <span style="font-size: 11px;" class="text-white">
                                                    <?php if(!empty($venture->percentage_range)): ?>
                                                        <?php echo e($venture->percentage_range); ?>

                                                    <?php else: ?>
                                                        <?php echo e($venture->percentage); ?> %
                                                    <?php endif; ?>
                                                </span>
                                            </div>

                                            <div class="mt-1 px-1 text-center ">

                        <span style="font-size: 10px; margin-bottom:-22px; font-weight:bold;"
                              class=" border-bottom border-2 border-bottom-dashed">Min $<?php echo e(abbreviateNumber($venture->min_amount)); ?>

                        -Max$<?php echo e(abbreviateNumber($venture->max_amount)); ?></span> <br>
                                                <span style="font-size: 10px; margin-to:-22px; font-weight:bold;"
                                                      class=" border-bottom border-2 border-bottom-dashed">Total return:<?php echo e($venture->total_return); ?>%</span>

                                                <div class="my-1 text-center">
                                                    <form action="<?php echo e(route('payment.directPackage')); ?>" method="post">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('POST'); ?>
                                                        <input type="hidden" name="package_type" value="VENTURE"/>
                                                        <input type="hidden" name="package_id" value="<?php echo e($venture->id); ?>"/>
                                                        <input type="hidden" name="network" value="TRC-20">

                                                        <input type="number" name="amount"
                                                               placeholder=" Enter Amount ($<?php echo e(abbreviateNumber($venture->min_amount)); ?> - $<?php echo e(abbreviateNumber($venture->max_amount)); ?>)"
                                                               class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded"
                                                               min="<?php echo e($venture->min_amount); ?>"
                                                               max="<?php echo e($venture->max_amount); ?>"
                                                               step="0.01"
                                                               required/>
                                                        <button class="btn btn-dark  px-2"
                                                                style="font-size: 10px; background-color:black;"
                                                                type="submit">
                                                            INVEST NOW
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <div class="col-md-12 col-12">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-4 mt-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <center class="bg-danger p-1 rounded">
                                                            <h3 class="text-white text-sm ">Do you have Activation
                                                                Code</h3>
                                                        </center>
                                                        <div class="text-center mt-3">
                                                            <?php
                                                                $msg = session('message');
                                                                if($msg!=""){
                                                                echo "<strong class='text-danger'>".$msg."</strong>";
                                                                }
                                                            ?>
                                                            <form action="<?php echo e(route("validate")); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>

                                                                <input type="text" class="form-control my-1"
                                                                       placeholder="Code"
                                                                       placeholder="Enter Activation Code" name="code">
                                                                <button class="btn btn-primary d-block w-100 mt-3"
                                                                        value="200">Submit
                                                                </button>
                                                            </form>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-4 d-flex align-items-center justify-content-center">

                                                <a href="<?php echo e(route('user.manual-deposit')); ?>"
                                                   class="btn btn-primary mx-2">
                                                    Deposit & Pay Later
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>


                        </div>


                        <!-- Bootstrap JS, Popper.js, and jQuery -->
                        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                        <!-- Custom JS to show modal automatically -->
                        <script>
                            $(document).ready(function () {
                                $('#nonClosableModal').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#nonClosableModal').modal('show');
                            });
                        </script>


                    </div>




<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/venture-package.blade.php ENDPATH**/ ?>
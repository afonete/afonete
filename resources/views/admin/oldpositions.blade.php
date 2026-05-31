<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\User;
$user = Auth::user();

$name = $user->user;

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

        <div class="row my-2">
            <div href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center justify-content-center col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon all">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total positions
                    </div>
                    <?php $members = DB::SELECT('SELECT * from members '); ?>

                    <div class="members" style="font-size: 13px">available : 10</span></div>
                </div>
            </div>
            {{-- <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon accepted">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">requested to create clubs
                    </div>
                    <?php $joined = DB::SELECT('SELECT * from members where status=1'); ?>
                    <div class="members" style="font-size: 13px">clubs: 0</div>
                </div>
            </a> --}}

            {{-- <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon reject">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                    <?php $requested = DB::SELECT('SELECT * from members where status=2'); ?>
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Rejected clubs
                    </div>
                    <div class="members" style="font-size: 13px">clubs: <span>0</span></div>
                </div>
            </a> --}}
        </div>


        {{-- table for all position --}}
        <div class="tables my-2 ">
            <div class="table-container rounded ">
                <div class="pt-3 border">
                    <p class="text-center font-weight-bold table-title">All Positions <span
                            class="text-success">10</span>
                    </p>
                </div>

                <div class="tb">
                    <table class="table table-striped " style="text-align: center;">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Reward</th>
                                <th>cashout</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                 $positions = array("TopL_leaders"=>"1000","Countries_leader"=>"2000", "Training&Marketing"=>"500", "Social_media_content&support " => "500", "CEO5" => "3000", "CEO6" => "3000","CEO7" => "3000", "CEO8" => "3000", "CEO9" => "3000","CEO10" => "3000" );
                                $no=1;
                                 foreach($positions as $pos => $pos_reward) { 
                                    
                            ?>
                            <a href="{{ route('admin.ads') }}" class="">
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td> {{ $pos }} </td>
                                    <td>{{ $pos_reward }}</td>
                                    <td class="cashout"> none </td>
                                    <td>
                                        <button class="btn btn-primary vMore" data-posName={{ $pos }}
                                            data-posRewd={{ $pos_reward }}>view</button>
                                    </td>
                                </tr>
                            </a>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>



        {{-- table for all users in positions --}}
        <div class="posContainer_table" id="positionTable">
            <div class="close cancel text-white text-4xl font-bold">&times;</div>
            <div class="tables my-2 posTable">
                <div class="table-container rounded ">
                    <div class="pt-3 border flex justify-between px-5">
                        <p class=" font-weight-bold table-title" id="curt-pos"></p>
                        <div class="d-flex">
                            <div class="font-semibold">Reward: </div>
                            <div style="color: #1565c0" class="font-bold pl-1" id="pos-reward"></div>
                        </div>
                    </div>
                    <h1 class="text-center font-semibold mt-3">Members</h1>
                    <div class="tb">
                        <table class="table table-striped " style="text-align: center;">
                            <thead class="bg-gradient-primary">
                                <tr>
                                    <th>#</th>
                                    <th>user</th>
                                    <th>Daily Received</th>
                                    <th> Daily Remain</th>
                                    <th>Status</th>
                                    <th>cashout</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i = 1; $i <= 5; $i++) { ?>
                                <a href="{{ route('admin.ads') }}" class="">
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>JohnDoe</td>
                                        <td>30$</td>
                                        <td>470$</td>
                                        <td>Waiting</td>
                                        <td>Not Allowed</td>
                                        <td class=" tracking-wide text-center text-2xl">
                                            <div class="action drop-btn ellipsMore">
                                                <i class="las la-ellipsis-v cursor-pointer"></i>
                                            </div>
                                            <div class=" absolute right-0 w-48 py-2 mt-2 bg-white bg-gray-100 rounded-md shadow-xl"
                                                id="drop-content">
                                                <a href="#"
                                                    class="dailyPopup block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                    Daily Award</a>
                                                <a href="#?id=<?php echo $i; ?>"
                                                    class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                    Approve </a>
                                                <a href="#?id=<?php echo $i; ?>"
                                                    class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                    Reject </a>
                                                <div>
                                                    <a href="#"
                                                        class=" action drop-btn ellipsMore block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                        Cashout
                                                        <div class="absolute right-0 w-48 mr-16 py-0 mt-0 bg-white bg-gray-100 rounded-md shadow-xl"
                                                            id="drop-content">
                                                            <a href="#?id=<?php echo $i; ?>"
                                                                class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                                allow</a>
                                                            <a href="#?id=<?php echo $i; ?>"
                                                                class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                                not allow </a>
                                                        </div>
                                                    </a>
                                                </div>


                                            </div>
                                        </td>
                                    </tr>
                                </a>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- start of popups --}}
        {{-- dailyreward --}}
        <div class="popup bg-white rounded-lg shadow-lg p-3" id="dailyModel">
            <div class="close cancel text-black text-3xl font-bold">&times;</div>
            <div class="font-bold text-lg text-center my-2">Daily Income</div>
            <div>
                <form action="">
                    <input type="text" class="form-controll rounded-md" placeholder="Enter Amount">
                    <input type="submit" class="btn btn-primary">
                </form>
            </div>
        </div>

        {{-- reject on --}}
        <div class="delete bg-white rounded-lg shadow-lg p-3 " id="delModel">
            <div
                class="delIcon rounded-full bg-gray-100 h-16  w-16 d-flex justify-content-center align-items-center my-2">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="font-bold text-lg text-center">Your are About to reject Item</div>
            <div class="text-center">This will reject your item Permanently <br> are you sure ? </div>
            <div class="actions">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1 cancel">Cancel</button>
                <button class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Yes, reject</button>
            </div>
        </div>
        {{-- end of popups --}}

        {{-- end of container fuluid --}}
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

    // delete model
    const del = document.getElementsByClassName("btnDelete");
    const close = document.getElementsByClassName("cancel");
    const delModel = document.getElementById("delModel");

    for (let i = 0; i < del.length; i++) {
        const element = del[i];
        element.addEventListener("click", () => {
            delModel.style.display = "block";
        })
    }

    // daily money model
    const dailyPopup = document.getElementsByClassName("dailyPopup");
    const dailyModel = document.getElementById("dailyModel");
    for (let i = 0; i < dailyPopup.length; i++) {
        const element = dailyPopup[i];
        element.addEventListener("click", () => {
            dailyModel.style.display = "block";
        })
    }


    // view position in popup

    const vPos = document.getElementsByClassName("vMore");
    const curtClickedPos = document.getElementById("curt-pos");
    const posTable = document.getElementById("positionTable");
    const pos_reward = document.getElementById("pos-reward");

    for (let i = 0; i < vPos.length; i++) {
        const elm = vPos[i];
        elm.addEventListener("click", (e) => {
            const target = event.target;
            const posName = target.getAttribute("data-posName");
            const posReward = target.getAttribute("data-posRewd");
            curtClickedPos.innerHTML = posName;
            pos_reward.innerHTML = posReward;
            posTable.style.display = "block";
        })

        // close pupup
        for (let i = 0; i < close.length; i++) {
            const elm = close[i];
            elm.addEventListener("click", () => {
                delModel.style.display = "none";
                dailyModel.style.display = "none";
                posTable.style.display = "none";
            })
        }

        // cashout 
        const cashout = document.getElementsByClassName("cashout");


    }
</script>
<script src="{{ asset('assets/a/js/custom.js') }}"></script>
@include('user.footer')

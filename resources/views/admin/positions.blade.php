<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Position;
$user = Auth::user();

$name = $user->user;
// $Positions=Position::all();
$Positions = Position::orderByRaw("
    CASE
        WHEN DATEDIFF(NOW(), updated_at) >= 30 THEN 1
        WHEN status = 'waiting' THEN 2
        ELSE 3
    END
")->get();
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
                 <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total positions
                    </div>
                    <?php $members = DB::SELECT('SELECT * from members '); ?>

                    <div class="members" style="font-size: 13px">available : 10</span></div>
                </div>
                 <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total positions
                    </div>
                    <?php $members = DB::SELECT('SELECT * from members '); ?>

                    <div class="members" style="font-size: 13px">available : 10</span></div>
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
                            class="text-success">10</span><br>
                            <span class="bg bg-gray-300">{{$message??''}}</span>
                    </p>
                </div>

                <div class="tb">
                    <table class="table table-striped " style="text-align: center;">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Package_Status</th>
                                <th>Position</th>
                                <th>General_task_FT</th>
                                <th>Available_Withdraw</th>
                                <th>Daily_Bonus</th>
                                <th>Reward</th>
                                <th>Approved_on</th>
                                <th>Working_days</th>
                                <th>requested_on</th>
                                <th>cashout</th>
                                <th>Duration</th>
                                <th>Unique_Task</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                              
                            $i=1;
                                foreach ( $Positions as $Position) { 
                                    $package=User::where('user',$Position->user)->first();
                                    switch ($package->has_paid_package) {
                                        case 'fc1':
                                            $userPackage="FC 100$";
                                            break;
                                           case 'fc2':
                                            $userPackage="FC 200$";
                                            break;
                                               case 'ft':
                                            $userPackage="FT";
                                            break;
                                        
                                    }
                            
        $requested = date("Y-m-d", strtotime($Position->created_at));
        $aproved= date("Y-m-d", strtotime($Position->updated_at));
        $approved = Carbon::parse($aproved);
        $working_days = Carbon::now()->diffInWeekdays($approved);
                       ?>
    <style>
    /* Keyframes for the animation */
    @keyframes colorCycle {
        0%, 100% { background-color: violet; } /* Default color */
        33% { background-color: violet; } /* Color 1 */
        66% { background-color: orangered; } /* Color 2 */
    }

    /* Styles for the tick-row class */
    .tick-row {
        animation: colorCycle 3s infinite; /* Repeat the animation indefinitely */
    }

    /* Add your custom styles for the tick icon here */
    .tick-icon {
        content: '\2713'; /* Unicode for checkmark icon */
        color: red; /* Color for the checkmark */
        font-size: 20px; /* Adjust the font size as needed */
        margin-right: 5px; /* Optional: Add margin to separate the icon from text */
    }
    .more{
        background-color: ;
    }
</style>


                           @if($working_days>=30)

                            <tr class="tick-row">

                                <td>{{ $i++}}</td>
                                <td>{{$Position->user}}</td>
                                <td>{{$userPackage}}</td>
                                <td>{{$Position->position}}</td>
                                <td>{{$Position->general_task}}</td>
                                <td>{{$Position->withdraw}}$</td>
                                <td>{{$Position->daily_bonus}}$</th>
                                <td>{{$Position->award}}$</td>
                                <td>{{$Position->status=='waiting'?'waiting for approval':$aproved}}</td>
                                <td><span class="tick-icon">&#x2713;</span>{{$working_days}} days</td>
                                <td>{{$requested}}</td>
                                <td>{{$Position->cashout}}</td>
                                <td>{{$Position->duration}}</td>
                                <td>{{$Position->unique_task}}</td>
                                    <td>{{$Position->status}}</td>

                                <td>
                                   @if($Position->status=='waiting')
                                    <a href="{{ route('admin.position.approve') }}?user={{$Position->user}}" class="btn btn-primary ">approve</button>
                                  @else
                                   <a href="{{ route('admin.pos_edit') }}?user={{$Position->user}}" class="btn btn-primary ">Edit</button>@endif
                                </td>
                            </tr>
                           
                            @else
                            <tr class="bg bg-secondary">

                                <td>{{ $i++}}</td>
                                <td>{{$Position->user}}</td>
                                <td>{{$userPackage}}</td>
                                <td>{{$Position->position}}</td>
                                <td>{{$Position->general_task}}</td>
                                <td>{{$Position->withdraw}}$</td>
                                <td>{{$Position->daily_bonus}}$</th>
                                <td>{{$Position->award}}$</td>
                                <td>{{$Position->status=='waiting'?'waiting for approval':$aproved}}</td>
                                <td>{{$working_days}} days</td>
                                <td>{{$requested}}</td>
                                <td>{{$Position->cashout}}</td>
                                <td>{{$Position->duration}}</td>
                                <td>{{$Position->unique_task}}</td>
                                    <td>{{$Position->status}}</td>

                                <td>
                                   @if($Position->status=='waiting')
                                    <a href="{{ route('admin.position.approve') }}?user={{$Position->user}}" class="btn btn-primary ">approve</button>
                                  @else
                                   <a href="{{ route('admin.pos_edit') }}?user={{$Position->user}}" class="btn btn-primary ">Edit</button>@endif
                                </td>
                            </tr>
                            @endif
                            <?php } ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>



        {{-- table for all users in positions --}}
        <div class="posContainer_table" id="positionTable">
            <div class="close  text-white text-4xl font-bold" id="closePosTb">&times;</div>
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
                                                <a href="#?id=<?php echo $i; ?>" data-approve="Approve"
                                                    class="btnApprove block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                    Approve </a>
                                                <a href="#?id=<?php echo $i; ?>" data-reject="Reject"
                                                    class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                    Reject </a>
                                                <div>
                                                    <a href="#"
                                                        class=" action drop-btn ellipsMore block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                        Cashout
                                                        <div class="absolute right-0 w-48 mr-16 py-0 mt-0 bg-white bg-gray-100 rounded-md shadow-xl"
                                                            id="drop-content">
                                                            <a href="#?id=<?php echo $i; ?>" data-approve="allow"
                                                                class="btnApprove block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">
                                                                allow</a>
                                                            <a href="#?id=<?php echo $i; ?>" data-reject="Disallow"
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

        {{-- approve --}}
        <div class="approve bg-white rounded-lg shadow-lg " id="appvPopup">
            <div
                class="delIcon rounded-full bg-gray-100 h-16  w-16 d-flex justify-content-center align-items-center my-2">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="font-bold text-lg text-center">Are you sure you need to <span class="appvAction"></span> </div>
            <div class="actions">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1 cancel">Cancel</button>
                <button class="bg-blue-500 hover:bg-blue-600 rounded-lg px-2 py-1 text-white"><span
                        class="appvAction"></span></button>
            </div>
        </div>

        {{-- reject on --}}
        <div class="delete bg-white rounded-lg shadow-lg p-3 " id="delModel">
            <div
                class="delIcon rounded-full bg-gray-100 h-16  w-16 d-flex justify-content-center align-items-center my-2">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="font-bold text-lg text-center">Your are About to <span class="rejAction"></span></div>
            <div class="text-center">This will <span class="rejAction"></span> Permanently <br> are you sure ? </div>
            <div class="actions">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1 cancel">Cancel</button>
                <button class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Yes, <span
                        class="rejAction"></span></button>
            </div>
        </div>

        {{-- end of popups --}}

        {{-- end of container fuluid --}}
    </div>
</div>
<script>
    // ellipse more
    let ellipsMore = document.getElementsByClassName("ellipsMore");
    // position
    const vPos = document.getElementsByClassName("vMore");
    const curtClickedPos = document.getElementById("curt-pos");
    const posTable = document.getElementById("positionTable");
    const pos_reward = document.getElementById("pos-reward");
    const close = document.getElementsByClassName("cancel");
    const closePosTb = document.getElementById("closePosTb");
    const dailyPopup = document.getElementsByClassName("dailyPopup");
    const dailyModel = document.getElementById("dailyModel");
    // popup
    const appvAction = document.getElementsByClassName("appvAction");
    const rejAction = document.getElementsByClassName("rejAction");
    const btnApprove = document.getElementsByClassName("btnApprove");
    const del = document.getElementsByClassName("btnDelete");
    const delModel = document.getElementById("delModel");
    const appvPopup = document.getElementById("appvPopup");


    for (let i = 0; i < ellipsMore.length; i++) {
        const elem = ellipsMore[i];
        elem.addEventListener("click", () => {
            let panel = elem.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none"
            } else {
                panel.style.display = "block"
            }
        })
    }

    // function display popup mode block and append value to it
    const popupFunct = (event, popupModel, divElm, btnName) => {
        popupModel.style.display = "block";
        const target = event.target;
        const posName = target.getAttribute(`${btnName}`);
        for (let i = 0; i < divElm.length; i++) {
            const element = divElm[i];
            element.innerHTML = posName;
        }
    }
    // btnApprove

    for (let i = 0; i < btnApprove.length; i++) {
        const btn = btnApprove[i];
        btn.addEventListener("click", (e) => {
            popupFunct(e, appvPopup, appvAction, "data-approve");
        })
    }

    // reject model

    for (let i = 0; i < del.length; i++) {
        const element = del[i];
        element.addEventListener("click", (e) => {
            popupFunct(e, delModel, rejAction, "data-reject")
        })
    }

    // daily money model

    for (let i = 0; i < dailyPopup.length; i++) {
        const element = dailyPopup[i];
        element.addEventListener("click", () => {
            dailyModel.style.display = "block";
        })
    }

    // view position in popup
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
    }

    // close popup
    for (let i = 0; i < close.length; i++) {
        const elm = close[i];
        elm.addEventListener("click", () => {
            delModel.style.display = "none";
            dailyModel.style.display = "none";
            appvPopup.style.display = "none";
            // posTable.style.display = "none";
        })
    }

    // close position
    closePosTb.addEventListener("click", () => {
        posTable.style.display = "none";
    })
</script>
<script src="{{ asset('assets/a/js/custom.js') }}"></script>
@include('user.footer')

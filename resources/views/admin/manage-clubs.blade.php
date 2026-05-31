<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Club;
use App\Models\member;
use Carbon\Carbon;


$user = Auth::user();
$username = $user->user;

$clubs=Club::all();
$totalClubs=Club::count();
$totalMembers=member::count();
 $today = Carbon::now()->startOfDay();
// Count club created today
    $clubsCreatedToday = Club::whereDate('created_at', $today)->count();
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
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon all">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total clubs
                    </div>
                    

                    <div class="members" style="font-size: 13px">clubs : {{$totalClubs}}</span></div>
                </div>
            </div>
            <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon accepted">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total members
                    </div>
                    
                    <div class="members" style="font-size: 13px">clubs: {{$totalMembers}}</div>
                </div>
            </a>

            <a href="#"
                class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                <div class="mx-3 club-icon reject">
                    <i class="lab la-cc-diners-club"></i>
                </div>
                <div class="mx-2">
                  
                    <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Clubs created to day
                    </div>
                    <div class="members" style="font-size: 13px">clubs: <span>{{$clubsCreatedToday}}</span></div>
                </div>
            </a>
        </div>

        {{-- table for all adds --}}
        <div class="tables my-2">
            <div class="table-container rounded ">
                <div class="pt-3 border">
                    <p class="text-center font-weight-bold table-title">All Clubs <span class="text-success">{{$totalClubs}}</span>
                    </p>
                </div>
 
                <div class="tb">
                    @if ($clubs->count() > 0)
                                        <table class="table table-striped " style="text-align: center;">
                        <thead class="bg-gradient-primary">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th> Club members</th>
                                <th>Owner</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i=1; foreach($clubs as $club) { 

                            $clubSize=member::where('user',$club->user)->count();


                                ?>
                            <a href="#" class="">
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{$club->tittle}}</td>
                                    <td>{{$club->desc}}</td>
                                    <td><span>{{$clubSize}}</span> Members</td>
                                    <td>{{$club->user}}</td>
                             <td class=" tracking-wide text-center text-2xl">
                                        <div class="action drop-btn ellipsMore">
                                            <i class="las la-ellipsis-v cursor-pointer"></i>
                                        </div>
                                        <div class=" absolute right-0 w-48 py-2 mt-2 bg-white bg-gray-100 rounded-md shadow-xl"
                                            id="drop-content">
                                            <a href="#?id=<?php echo $i; ?>"
                                                class="btnDelete block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white"><i
                                                    class="las la-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            </a>
                            <?php } ?>

                        </tbody>
                    </table>
                    @else
                    <span>No clubs found</span>
                    @endif
                </div>

            </div>
        </div>

        <div class="delete bg-white rounded-lg shadow-lg p-3 " id="delModel">
            <div
                class="delIcon rounded-full bg-gray-100 h-16  w-16 d-flex justify-content-center align-items-center my-2">
                <i class="las la-trash text-red-500"></i>
            </div>
            <div class="font-bold text-lg text-center">Your are About to delete Item</div>
            <div class="text-center">This will delete your item Permanently <br> are you sure ? </div>
            <div class="actions">
                <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1" id="cancel">Cancel</button>
                <button class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Yes, Delete</button>
            </div>
        </div>
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
    const cancel = document.getElementById("cancel");
    const delModel = document.getElementById("delModel");

    for (let i = 0; i < del.length; i++) {
        const element = del[i];
        element.addEventListener("click", () => {
            delModel.style.display = "block";
        })
    }
    cancel.addEventListener("click", () => {
        delModel.style.display = "none";
    })
</script>
<script src="{{ asset('assets/a/js/custom.js') }}"></script>
@include('user.footer')

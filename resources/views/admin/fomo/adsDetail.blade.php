<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
    use App\Models\ads;
    use App\Models\views;
use App\Models\User;
$user = Auth::user();
$name = $user->user;

$adsId=request()->query('id');
if (!$adsId) {
    $adsId=$id;
}
  $ads = ads::where('id',$adsId)->first();
$owner=User::where('user',$ads->user)->first();

 $respondedWell = views::where('ads', $adsId)->where('has_answer', 'well')->count();

$respondedBad = views::where('ads', $adsId)->where('has_answer', 'wrong')->count();
$paidToResponse=($ads->user_paid/$ads->targeted_views)*$respondedWell;
$unpaidLeft=$ads->user_paid-$paidToResponse;
$paidToSystem=($ads->total_paid*30)/100;
$paidToFc=($ads->total_paid*20)/100;
?>
@include('admin.admin-base')
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('css/app.css') }}" rel="stylesheet">


<style>
    * {
        font-size: 14px
    }
</style>
<div class="content-wrapper">
    <div class="container">
        {{-- <ul class="nav nav-tabs">
              <li class="active"><a  href="{{route('admin.ads')}}">Manage ads</a></li>
              <li><a  href="{{route('admin.videos')}}">Manage videos</a></li>
              <li><a  href="#">Manage download</a></li>
              <li><a  href="{{route('admin.ads.create')}}">Add ads</a></li>
            </ul> --}}
        <div class="tabs tab_links mt-2">
            <span class="links_tabs d-flex ">
                <a href="{{ route('admin.ads') }}" class="fomoLink" id="tabs"> <i
                        class="fa-solid fa-rectangle-ad"></i> Manage ads </a>
                <a href="{{ route('admin.ads.create') }}" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                    ads </a>

            </span>
        </div>

        <div class="card-container my-2">
            <div class="mainCard card1">
                <div class="title">
                    <h3 class="font-weight-bold text-3xl">{{$ads->tittle}}</h3>
                </div>
                <div class="app-rej">
                    
                    <button class="btn btn-danger rejectBtn">reject</button>
                    <a href="{{route('admin.ads.approve')}}?id={{$adsId}}" class="btn btn-primary">approve</a>
                        <span class="bg bg-success">{{$rejected}}</span>
                        <span class="bg bg-success">{{$message}}</span>
                                      </div>
                <div class="img">
                    <img src="{{ asset('ads/'.$ads->banner) }}" alt="">
                </div>  
                <div class="descp">
                    {{$ads->description}}
                </div>
            </div>
            <div class="mainCard card2">
                <div class="d-flex justify-content-between align-items-end ">
                    <h3 class="sub-head">Owner</h3>
                    <p class="text-primary font-semibold">{{$ads->user}}</p>
                </div>
                <div class="owner d-flex align-items-center border-b border-gray-200 pb-4">
                    <div class="img mr-2">
                        <img src="{{ asset('assets/a/img/user.png') }}" alt="">
                    </div>
                    <div class="">
                        <div class="name">
                            {{$owner->name}}
                        </div>
                        <div class="email font-weight-bold">
                             {{$owner->email}}
                        </div>
                    </div>

                </div>
                <p class="role"></p>
                      <div class="paidMoney d-flex justify-between align-center my-2">
                    <div class="font-semibold">Ads Status</div>
                    <div class="text-blue-500 font-semibold">{{$ads->status}}</div>
                </div>
                    @if($ads->status=='rejected')
                <h3 class="sub-head text-center">Rejected Reason</h3>
                <p class="qtn text-center">{{$ads->rejected_reason}}</p>
                <hr>@endif
                <h3 class="sub-head text-center">Ads Question</h3>
                <p class="qtn text-center">{{$ads->question}}</p>

@if($ads->url)
                <div class="bg-gray-200 py-4 px-2">
                    <div class="url bg-white shadow-md d-flex align-items-center rounded-lg">
                        <div class=" text-gray-500 link">{{$ads->url}}</div>
                        <div class="copy bg-blue-600 hover:bg-blue-700 rounded-md ">
                            <i class="las la-copy text-white font-bold"></i>
                        </div>
                    </div>
                </div>
@endif
                <div class="views d-flex justify-content-between">
                    <div class="target d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Targeted Views</div>
                    <div class="view-icon font-weight-bold"><i class="lar la-eye"></i> {{$ads->targeted_views}}</div>
                    </div>
                    <div class="reacted d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Reached Views</div>
                    <div class="view-icon font-weight-bold"><i class="lar la-eye"></i> {{$ads->reached_views==NULL?0:$ads->reached_views}}</div>

                    </div>
                </div>
                <div class="views d-flex justify-content-between">
                    <div class="target d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Responded Well</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-reply"></i>{{$respondedWell}}</div>
                    </div>
                    <div class="reacted d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Responded bad</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-reply"></i> {{$respondedBad}}</div>

                    </div>
                </div>
                <div class="views d-flex justify-content-between">
                    <div class="target d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Money Paid to <br> Response</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-money-bill"></i> {{$paidToResponse}}$</div>
                    </div>
                    <div class="reacted d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Money Left <br> (Unpaid)</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-money-bill"></i> {{$unpaidLeft}}$</div>

                    </div>
                </div>
               
                <div class="views d-flex justify-content-between">
                    <div class="target d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Money Paid to <br> System</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-money-bill"></i> {{$paidToSystem}}$</div>
                    </div>
                    <div class="reacted d-flex flex-column align-items-center">
                        <div class="font-semibold text-center">Money paid to <br> FC</div>
                        <div class="view-icon font-weight-bold"><i class="fa-solid fa-money-bill"></i> {{$paidToFc}}$</div>

                    </div>
                </div>
                 <div class="paidMoney d-flex justify-between align-center my-2">
                    <div class="font-semibold">Duration (Time)</div>
                    <div class="text-blue-500 font-semibold">{{$ads->targeted_duration}} days</div>
                </div>
                <div class="paidMoney d-flex justify-between align-center my-2">
                    <div class="font-semibold">Money Paid to ad</div>
                    <div class="text-blue-500 font-semibold">{{$ads->total_paid}}$</div>
                </div>
                <div class="paidMoney d-flex justify-between align-center my-2">
                    <div class="font-semibold">Location</div>
                    <div class="text-blue-500 font-semibold">{{$ads->location}}</div>
                </div>

            </div>
        </div>




    </div>
</div>
<div class="reject bg-white rounded-xl shadow-lg py-2 px-4 " id="reject">
                <div
                    class="delIcon rounded-full bg-gray-100 h-14  w-14 d-flex justify-content-center align-items-center my-1">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="font-bold text-lg my-2">left reason why you reject this ads</div>
                <div class="text-center">
                    <form method="post" action="{{route('admin.ads.reject')}}">
                        @csrf
                        <input type="text" name="reason" 
                            class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="reason" required>
                            <input type="hidden" name="ads" value="{{$adsId}}" >
                                   </div>
                <div class="actions">
                    <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1" id="cancel">Cancel</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Reject</button>
                </div>
                 </form>

            </div>
        </div>
    </div>

    <script>
   
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
</script>
@include('user.footer')

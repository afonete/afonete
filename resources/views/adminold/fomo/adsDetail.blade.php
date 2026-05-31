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
                <a href="{{ route('admin.videos') }}" class="fomoLink"><i class="fa-solid fa-video"></i> Manage
                    videos</a>
                <a href="{{ route('admin.ads.create') }}" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                    ads </a>

            </span>

            <a href="{{ route('user.dashboard.payvideo') }}" class="fomoLink"><i class="fa-solid fa-download"></i>
                Manage download <i style="color: red">Coming soon</i></a>
        </div>

        <div class="card-container my-2">
            <div class="mainCard card1">
                <div class="title">
                    <h3 class="font-weight-bold text-3xl">Lesson for children</h3>
                </div>
                <div class="img">
                    <img src="{{ asset('assets/a/img/click.png') }}" alt="">
                </div>
                <div class="descp">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Alias veniam aperiam cupiditate ad dolores
                    itaque veritatis, consectetur perspiciatis praesentium sed odit id illo doloremque ex atque corporis
                    in minima non.
                </div>
            </div>
            <div class="mainCard card2">
                <div class="d-flex justify-content-between align-items-end ">
                    <h3 class="sub-head">Owner</h3>
                    <p class="text-primary font-semibold">admin</p>
                </div>
                <div class="owner d-flex align-items-center border-b border-gray-200 pb-4">
                    <div class="img mr-2">
                        <img src="{{ asset('assets/a/img/user.png') }}" alt="">
                    </div>
                    <div class="">
                        <div class="name">
                            John Doe
                        </div>
                        <div class="email font-weight-bold">
                            johndoe@gmail.com
                        </div>
                    </div>

                </div>
                <p class="role"></p>

                <h3 class="sub-head ">Ads Question</h3>
                <p class="qtn">What is www?</p>
                <div class="views mt-2 d-flex justify-content-between">
                    <div class="target d-flex flex-column align-items-center">
                        <div class="sub-head">Targeted Views</div>
                        <div class="view-icon font-weight-bold"><i class="lar la-eye"></i> 1234</div>
                    </div>
                    <div class="reacted d-flex flex-column align-items-center">
                        <div class="sub-head">Reacted Views</div>
                        <div class="view-icon font-weight-bold"><i class="lar la-eye"></i> 1234</div>

                    </div>
                </div>

               
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
</script>
@include('user.footer')

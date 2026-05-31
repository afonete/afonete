    <?php
    
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Http\Request;
    use App\Models\ads;
    
    use App\Models\User;
    $user = Auth::user();
    
    $name = $user->user;
     $ads=ads::all();  
    
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
      
            <div class="tabs tab_links mt-2">
                <span class="links_tabs d-flex ">
                    
                    <a href="{{ route('admin.ads.create') }}" class="fomoLink"><i class="fa-solid fa-plus"></i> Add
                        ads </a>

                </span>

              
            </div>

            <div class="row my-2">
                <div  href="#" class="topBox bg-white shadow rounded p-3 d-flex align-items-center col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon all">
                         <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total Ads
                        </div>

                        <div class="members" style="font-size: 13px">advertisment: {{count($ads)}}</span></div>
                    </div>
                </div>
                <a href="#"
                    class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon accepted">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Accepted Ads
                        </div>
                        <?php $accepted = DB::SELECT("SELECT * from ads where status='approved'"); ?>
                        <div class="members" style="font-size: 13px">advertisment: {{count($accepted)}}</div>
                    </div>
                  </a>

                <a href="#"
                    class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon reject">
                         <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div class="mx-2">
                        <?php $rejected = DB::SELECT("SELECT * from ads where status='rejected'"); ?>
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Rejected Ads
                        </div>
                        <div class="members" style="font-size: 13px">advertisment: <span>{{count($rejected)}}</span></div>
                    </div>
                  </a>
            </div>

            {{-- table for all adds --}}
            <div class="tables my-2">
                <div class="table-container rounded ">
                    <div class="pt-3 border">
                        <p class="text-center font-weight-bold table-title">All advertisement <span
                                class="text-success">{{count($ads)}}</span></p>
                    </div>

                    <div class="tb">
                        <table class="table table-striped " style="text-align: center;">
                            <thead class="bg-gradient-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th> Status</th>
                                    <th>Uploaded_by</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach( $ads as $ad) { ?>
                                <a href="{{ route('admin.ads') }}" class="">
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{$ad->tittle}}</td>
                                        <td>{{$ad->description}}</td>
                                        <td>{{$ad->status}}</td>
                                        <td>{{$ad->user}}</td>
                                        <td class=" tracking-wide text-center text-2xl">

                                            <div class="action drop-btn ellipsMore">
                                                <i class="las la-ellipsis-v cursor-pointer"></i>
                                            </div>
                                            <div class=" absolute right-0 w-48 py-2 mt-2 bg-white bg-gray-100 rounded-md shadow-xl"
                                                id="drop-content">
                                                <a href="{{ route('admin.adsDetail') }}"
                                                    class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">view</a>
                                                <a href="#?id=<?php echo $i; ?>"
                                                    class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">approve</a>
                                                <a href="#?id=<?php echo $i; ?>"
                                                    class="rejectBtn block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">reject</a>
                                                <a href="#?id=<?php echo $i; ?>"
                                                    class="block px-4 py-2 text-sm text-gray-300 text-gray-700 hover:bg-gray-400 hover:text-white">Delete</a>
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
            {{-- end of container fuluid --}}

            <!-- Main content -->
            <div class="content">
                <div class="row">
                    <?php 

$results=db::SELECT("SELECT * from ads order by id desc");
if (!count($results)) {
  ?>
                    <?php
  foreach ($results as $row) {
   
  
        ?>
                    <div class="col-md-3">
                        <div class="card card-primary card-outline">
                            <a href="">
                                <div class="card-header">
                                    <h5 class="m-0">
                                        @if ($row->url != null)
                                            <a style="color: #007bff" target="__blank"
                                                href="{{ route('user.url') }}?url={{ $row->url }}">{{ $row->tittle }}

                                            </a>
                                        @endif
                                        <!--  -->
                                        @if ($row->url == null)
                                            <a style="color: #007bff" target="__blank"
                                                href="{{ route('clicks') }}?ads={{ $row->id }}">{{ $row->tittle }}</a>
                                        @endif

                                    </h5>
                                </div>




                                @if ($row->banner != null)
                                    <!-- <div class="card-body click-title" id="mmm"> -->
                                    <?php
                                    
                                    echo "<div class='card-body click-title' style='height: 180px;background-repeat: round;background:url(" . asset('ads/' . $row->banner) . ")'>";
                                    ?>
                                    <span>
                                @endif

                                @if ($row->banner == null)
                                    <div class="card-body click-title ">
                                        <div style="height: 80%;width:100%">{{ $row->description }}</div>
                                @endif
                                </span>

                        </div>
                        <hr>
                        <h5 class="text-center">

                            @if ($row->url != null)
                                <a style="color: #007bff" target="__blank" href="{{ $row->url }}">View

                                </a>
                            @endif
                            @if ($row->url == null)
                                <a style="color: #007bff" target="__blank"
                                    href="{{ route('clicks') }}?ads={{ $row->id }}">
                                    View </a>
                            @endif &nbsp;&nbsp;&nbsp;<span
                                class="bg bg-primary">{{ $row->status }}</span>
                        </h5>

                        </a>

                    </div>

                </div>
                <?php

      }
}
else {
    echo '<span class="bg bg-secondary">No ads have uploaded </span>';
}
         ?>
            </div><!-- /.container-fluid -->
            
            
            <div class="reject bg-white rounded-xl shadow-lg py-2 px-4 " id="reject">
                <div
                    class="delIcon rounded-full bg-gray-100 h-14  w-14 d-flex justify-content-center align-items-center my-1">
                    <i class="fa-solid fa-xmark"></i>
                </div>
                <div class="font-bold text-lg my-2">left reason you reject ads</div>
                <div class="text-center">
                    <form action="POST">
                        <input type="text"
                            class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="reason">
                    </form>
                </div>
                <div class="actions">
                    <button class="bg-gray-300 hover:bg-gray-400 rounded-lg px-2 py-1" id="cancel">Cancel</button>
                    <button class="bg-red-500 hover:bg-red-600 rounded-lg px-2 py-1 text-white">Reject</button>
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
    @include('user.footer')

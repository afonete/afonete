
<?php 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\member;
    $user = Auth::user();
    $name = $user->name;
    $userr=$user->user;
    $email = $user->email;
    $spons = $user->referee_id;
      $ref = $user->activation;
   
$i=1;
$a=1;
    $access=DB::SELECT("SELECT * from users where referee_id='$ref' and has_free_package ='yes'");
        $check=count($access);
$clubs=DB::SELECT("SELECT * from clubs where user='$userr'");
$memberIn=DB::SELECT("SELECT * from members where member='$userr'");
$requested=$memberIn[0]->status;
$invitees=$memberIn[0]->user;
?>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style type="text/css">
    .clubDesc{
        display: none;
    }
    /*#invite_form{*/
    /*    display: none;*/
    /*}*/
    .showClub{
        display: block;
    }
    .invite{
        display: none;
    }
</style>   


<body class="hold-transition sidebar-mini">
<div class="wrapper">
@include('user.user-dashboard-base')
 <div class="content-wrapper" id="center-side">

    <div class="content">
        <div class="container-fluid">

          <div class="tabs tab_links">
            <a href="#" class="" onclick="openTabContent(event, 'clubs')" id="defaultTab">MyClub</a>
            <a href="#" class="" onclick="openTabContent(event, 'myClub')" >Clubs</a>
       @if (count($memberIn))
       @if($requested==2)   <a class="btn btn-secondary">Your request to join club is still pending, waiting for acception</a>@endif
        @if($requested==0)  
        <a href="{{route('club.invite.accept')}}?invitee={{$invitees}}" class="btn btn-primary">You have club invitation, from {{$invitees}} click to accept it</a>@endif @endif
          </div>

       <div class="tabcontent my-3" id="myClub">
                <div class="all clubs row" >


                    <div class="populClubs col-md-6">
                        <div class="card direct-chat direct-chat-primary px-2 ">
                                 <div class="card-header d-flex flex-column align-items-center">
                                    <div class="club_main_icon">
                                        <i class="las la-users"></i>
                                    </div>
                                    <div class="club_main_title">
                                        <h3 class="card-title">Most Clubs</h3>
                                    </div>
                                    <div class="club_main_subHead">   
                                         <p class="text-center">The most Peaple have joined these clubs. Are u interesting? Join now</p>
                                    </div>
                                 </div>
                                 <?php 
// $most = DB::SELECT("SELECT c.id, c.user, COUNT(m.member) AS all_members 
//                   FROM clubs c 
//                   JOIN members m ON (c.user = m.user) 
//                   GROUP BY c.id, c.user 
//                   ORDER BY all_members DESC 
//                   LIMIT 10");
 $most=DB::SELECT("SELECT * from clubs"); 
$a=1;
?>
                             
                                 <!-- /.card-header -->
                                 <div class="card-body">
                                     <div class="direct-chat-messages">
                                         <!-- Add font awesome icons -->
                                         @if(count($most))
                                         @foreach($most as $club1)
                                            <div class="px-3 d-flex justify-content-start align-items-center ">  
                                                    <div class="ml-3 mr-3">
                                                        {{$a++}}
                                                    </div>
                                                    <div class="link-icon">  
                                                        <div><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                                                        <div class="ml-3">{{$club1->tittle}}</div>
                                                    </div>
                                             </div>
                                         
                                         <hr>
                                        
                                             @endforeach 
                                         @else
                                         <span class="btn btn-secondary">no most clubs are availlable right now.Join number of leaders</span>
                                         @endif
                                         

                                     </div>


                                 </div>
                             </div>
                    </div>



                    <div class="allClubs col-md-6">
                        <div class="card direct-chat direct-chat-primary px-2 ">
                                 <div class="card-header d-flex flex-column align-items-center">
                                    <div class="club_main_icon">
                                        <i class="las la-users"></i>
                                    </div>
                                     <?php
 $all=DB::SELECT("SELECT * from clubs"); 
$a=1;

?>

                                    <div class="club_main_title">
                                        <h3 class="card-title">All Clubs {{$size}}</h3>
                                    </div>
                                    <div class="club_main_subHead">   
                                         <p class="text-center">Here, the list of all created club. click to join </p>
                                    </div>
                                 </div>
                                 
                               <!-- /.card-header -->
                                 <div class="card-body">
                                     <div class="direct-chat-messages" id="listOfclubs">
                                     
 @if(count($all))
                                         <script>
                                         document.addEventListener("DOMContentLoaded", ()=>{
                                            
                                            let listOfclubs = document.getElementById("listOfclubs");
                                            const club_title =  document.getElementById('club_title');
                                            const club_descr =  document.getElementById('club_descr');
                                            const club_leader =  document.getElementById('leader');
                                            const club_size =  document.getElementById('size');
                                            const selectedClubCont = document.getElementById("club1");
                                            let link =  document.getElementById('link');
                                            
                                          listOfclubs.innerHTML = "";
                                            let clubData = [];
                                            let csize;
                                            let newArr;
                                            @foreach($all as $alls)
                                                csize = "{{ count(DB::select('SELECT * FROM members WHERE user = ?', [$alls->user])) }}";
                                            
                                                // Create the object directly
                                                newArr = {
                                                    id: "{{$alls->id}}",
                                                    name: "{{$alls->tittle}}",
                                                    descr: "{{$alls->desc}}",
                                                    leader: "{{$alls->user}}",
                                                    size: csize
                                                };
                                            
                                                clubData.push(newArr);
                                            @endforeach
                                            
                                             clubData.forEach((data)=>{
                                                listOfclubs.innerHTML  += `
                                                    <div class="px-3 d-flex justify-content-start align-items-center ">  
                                                            <div class="ml-3 mr-3">
                                                                ${data.id}
                                                            </div>
                                                            <div class="link-icon">  
                                                                <div><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                                                                <div class="ml-3"><a href="#club1" data-club-id=${data.id} class="view-more text-dark">${data.name}</a></div>
                                                            </div>
                                                     </div>
                                                     <hr>
                                                `
                                            });
                                             listOfclubs.addEventListener("click", event =>{
                                                    const target = event.target;

                                                    if(target.classList.contains("view-more")){
                                                        const clubId = target.getAttribute('data-club-id');
                                                        const selectedClub = clubData.find(club => club.id.toString() === clubId);

                                                        if(selectedClub){
                                                            // let club_size=selectedClub.size;
                                                        
                                                    
                                                            selectedClubCont.className = "showClub";
                                                            club_title.textContent = selectedClub.name;
                                                            club_descr.textContent = selectedClub.descr;
                                                             club_leader.textContent = selectedClub.leader;
                                                             club_size.textContent = selectedClub.size;
                                                            link.innerHTML = "<input type='hidden' name='leader' value='" + selectedClub.leader + "'>";
                                                        }
                                                    }else{
                                                        console.log('not contain view more')
                                                    }

                                                });   
                                            
                                         });    
                                        </script>
                           @else
                           <span class="btn btn-secondary">No club have been created before, be the first to create it</span>@endif   
                                       </div> </div>     

                                
                                     </div>
                    </div>


                </div>

                 <!-- club in details -->
                <div class="my-3 clubDesc" id="club1">
                    <div class="clubBox w-md-50">
                     
                        <div class="d-flex align-items-center flex-column">
                            <div class="clubImg"><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                            <div class="clubTitle" id="club_title"></div>
                            <div class="clubDetails" id="club_descr"></div>
                        </div>
                        

                        <div class=" rounded clubNums">
                            <div class="team_leader d-flex justify-content-between">
                                <div>Club Leader:</div>
                                <div id="leader"></div>
                            </div>
                             <div class="team_size d-flex justify-content-between">
                                <div>Team size:</div>
                                <div id="size"></div>
                            </div>
                        </div>

                        <div class="joinClub d-flex justify-content-center my-3">
                         <form method="post" action="{{route('club.request')}}">
                             @csrf
                            <!--<span id="submitl"></span>-->
                           <span id="link"></span>
                          
                                <button type="submit" id="link" class="btn btn-primary font-weight-bold  rounded-pill px-5">Join Club</button>
                         </form>
                        </div>
                       
                    </div>
                </div>

            

             <?php $members=DB::SELECT("SELECT * from clubs where user='$userr'"); ?>
                            @if(count($members)<1)
             <!-- create club -->
                <div class="createClub  my-4">
                      <div class="bg-white shadow py-4 d-flex flex-column align-items-center">
                            <div class="club_main_icon">
                                <i class="las la-plus-circle"></i>
                            </div>
                            <div class="club_main_title">
                                <h3 class="card-title">Create your own club</h3>
                            </div>
                            <div class="club_main_subHead">   
                                 <p class="text-center" id="access2"> You must have 5 direct referral to create club.</p>
                            </div>
                            <a href="#" class="btn btn-secondary rounded-pill" id="createClub">Create New Club</a>
                        </div>
                </div>

                <div class="createClubModel" id="clubModel" >
                     <span class="close">&times;</span>
                     <div class="createClubForm shadow modal-content">   
                        <h1>Create club </h1>
                        <form class="needs-validation " novalidate method="post" action="{{route('club.create')}}">
                           @csrf
                              <div class="form-group">
                                    <label for="teamTitle">Club Title</label>
                                    <input type="text" class="form-control" id="teamTitle" name="tittle"  placeholder="Enter title" required>
                                    <div class="invalid-feedback">
                                     Please provide a valid club title.
                                  </div>

                              </div>
                              <div class="form-group">
                                <label for="teamDetails">Club Description</label>
                                 <input type="text" class="form-control" id="teamDetails" name="desc" placeholder="Enter club Description" required>
                                <div class="invalid-feedback">
                                    Please provide a valid  Description.
                                </div>
                              </div>
                              <button type="submit" class="btn btn-primary w-100 font-weight-bold ">Submit</button>
                        </form>
                    </div>    
                </div>
         
@endif


         </div> 
                       @if(session('request'))
    <div class="btn btn-primary">
        {{ session('request') }}
    </div>
@endif
 <h3 class="text-center mt-3">@if($message) <span class="btn btn-success">{{$message}}</span>@else Club structure @endif
             @if(count($clubs)>0)
             
          <div class="club_struct tabcontent my-3" id="clubs">
            <div class="row">
                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total Member</div>
                        <?php $members=DB::SELECT("SELECT * from members "); ?>
                            
                        <div class="members" style="font-size: 13px">Members: <span>{{count($members)}}</span></div>
                    </div>
                </div>

                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Joined Member</div>
                         <?php $joined=DB::SELECT("SELECT * from members where status=1"); ?>
                        <div class="members" style="font-size: 13px">Members: <span>{{count($joined)}}</span></div>
                    </div>
                </div>

                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <?php $requested=DB::SELECT("SELECT * from members where status=2"); ?>
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Requested To join</div>
                        <div class="members" style="font-size: 13px">Members: <span>{{count($requested)}}</span></div>
                    </div>
                </div>
            </div>

    
              </h3>
                <div class="row">
                <div class="col-12 col-sm-6 col-md-12"> 
                    <div class="table-container rounded" style="overflow-x: scroll;">
                      <table class="table table-striped hist_table" style="text-align: center; " >
                            
                            <div class="d-flex justify-content-between align-items-center ml-3 mr-4" >
                                <p class="pt-3 pl-3 font-weight-bold my-3">Club Members</p>
                                <div class="inviteContainer">
                                    <button class="btn btn-primary" id="invite_btn">+ invite</button>
                                    <div class="invite" id="invite_form">
                                        <?php $allUsers=User::where('has_paid_package','!=','no')->get() ?>
                                         <?php foreach ($allUsers as $thisUser) { 
                                         $joinedUser=member::where('member',$thisUser->user)->first();
                                         if($joinedUser)
                                         {continue;}
                                          if($userr==$thisUser->user)
                                         {continue;}
                                        
                                         ?>
                                             <div class="d-flex align-items-center px-2 justify-content-between inviteUser">
                                                <div class="name">{{$thisUser->user}}</div>
                                                <a href="{{route('club.invite')}}?name={{$thisUser->user}}" class="btn btn-primary btn-sm inviteBtn font-weight-bold">invite</a>
                                            </div>
                                        <?php  } ?>
                                        <!--<form method="POST"  action="{{route('club.invite')}}"  class="fn-form">-->
                                        <!--    @csrf-->
                                        <!--    <input type="text" name="name" placeholder="User name" required class="form-control">-->
                                        <!--    <button type="submit" class="btn btn-primary inviteBtn font-weight-bold">invite</button>-->
                                        <!--</form>-->
                                     
                                    </div>
                                </div>
                              </div>
                              
                              <script>
                                  // toggle invite form 

                                    let invitBtn = document.getElementById('invite_btn');
                                    let invitForm = document.getElementById('invite_form')
                                
                                    invitBtn.addEventListener("click", ()=>{
                                        invitForm.classList.toggle("inviteShow");
                                    })
                              </script>
                        
                        <thead>
                             <?php $members=DB::SELECT("SELECT * from members where user='$userr'"); ?>
                            @if(count($members)>0)
                          <tr>
                            <th>#</th>
                            <th>USERNAME</th>
                            <th>ACTIVATION DATE</th>
                            <th>COUNTRY</th>
                            <th>LEADERSHIP RANK</th>
                            <th>SPONSORED BY</th>
                            <th>status</th>
                             <th>ACTION</th>
                            
                          
                          </tr>
                        </thead>
                    <tbody>
                    
                     
                      @foreach ($members as $joined)
                      
                       <?php $getUser=DB::SELECT("SELECT * from users where user='$joined->member'");
                       $id=$joined->id;$getId=$getUser[0]->referee_id;
                       $getEmail=$getUser[0]->email;
                       $sponsed= DB::SELECT("SELECT * from users where activation='$getId'");
                       
                       ?>
                     <?php $member=DB::SELECT("SELECT * from users where user='$joined->member'"); ?>
                      <?php $activation=DB::SELECT("SELECT * from activations where email='$getEmail'"); ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$joined->member}}</td>
                            <td>{{$activation[0]->created_at}}</td>
                            <td>{{$member[0]->country}}</td>
                            <td>Member</td>
                         
                            <td>{{$sponsed[0]->user==NULL?'system':$sponsed[0]->user}}</td> 
                            

                            <td>{{$joined->status==0?'invited': ($joined->status==1?'Joined':'requested')}}</td>
                          <td> @if($joined->status==2) <a href="{{route('club.accept')}}?id={{$id}}" class="btn btn-primary">accept</a>
                          @else <a class="btn btn-secondary">no action</a> @endif</td>
                        </tr>
                        @endforeach
                        @else 
                        <span class="btn btn-secondary">No members have joined your club</span>
                        @endif
                       
                    </tbody>
                  </table>
                
           
              @else 
                        <div class="createClub">
                      <div class="bg-white shadow py-4 d-flex flex-column align-items-center">
                            <div class="club_main_icon">
                                <i class="las la-plus-circle"></i>
                            </div>
                            <div class="club_main_title">
                                <h3 class="card-title">Create your own club</h3>
                                
                            </div>
                            <div class="club_main_subHead">   
                                 <p class="text-center" id="access"> You must have 5 direct referral to create club.</p>
                            </div>
                            <a href="#" class="btn btn-secondary rounded-pill" id="createClub1">Create New Club</a>
                        </div>
                </div>
                <div class="createClubModel" id="clubModel1" >
                     <span class="close">&times;</span>
                     
                     <div class="createClubForm shadow modal-content">   
                        <h1>Create club </h1>
                        <form class="needs-validation " novalidate method="post" action="{{route('club.create')}}">
                           @csrf
                              <div class="form-group">
                                    <label for="teamTitle">Club Title</label>
                                    <input type="text" class="form-control" id="teamTitle" name="tittle" max="16" max-length="16"  placeholder="Enter title" required>
                                    <div class="invalid-feedback">
                                     Please provide a valid club title.
                                  </div>

                              </div>
                              <div class="form-group">
                                <label for="teamDetails">Club Description</label>
                                 <input type="text" class="form-control" id="teamDetails" name="desc" max="30" max-length="30" placeholder="Enter club Description" required>
                                <div class="invalid-feedback">
                                    Please provide a valid  Description.
                                </div>
                              </div>
                              <button type="submit" class="btn btn-primary w-100 font-weight-bold ">Submit</button>
                        </form>
                    </div>    
                </div>
         

                        @endif
               </div></div>
            </div>
        </div>
        
        
     
        </div>
    </div>

</div>
</div>
</body>
<script>



    // create club form validation
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();


    // for open tabs
    const openTabContent = (event, clubName)=>{
        let i, tabContent, tabLinks;

        tabcontent = document.getElementsByClassName('tabcontent');
        for (let i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        document.getElementById(clubName).style.display = "block";
    }
    // default tab 
    document.getElementById('defaultTab').click();


    // handle open and close model for create club
    var modal = document.getElementById("clubModel");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var btn = document.getElementById("createClub");
    btn.onclick = function(){
        var check="{{$check}}";
        var intValue = parseInt(check, 10);
        var rem=5-intValue;
        if (intValue<5)
        {

               document.getElementById('access2').innerHTML="Not allowed to create club. You need to have "+rem+" direct referral To be allowed to create club";
        }
        else{
      modal.style.display = "block";
    

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }
        }
    }
    
       // handle open and close model for create other club
    var modal1 = document.getElementById("clubModel1");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var btn1 = document.getElementById("createClub1");
    btn1.onclick = function(){
        var check="{{$check}}";
        var intValue = parseInt(check, 10);
        var rem=5-intValue;
        if (intValue<5)
        {
            document.getElementById('access').innerHTML="Not allowed to create club. You need to have "+rem+" direct referral To be allowed to create club";
               
        }
        else{
      modal1.style.display = "block";}
   
    
    

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal1.style.display = "none";
    }
 }

</script>

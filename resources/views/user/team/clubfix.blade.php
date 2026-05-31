
<?php 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

    $user = Auth::user();
    $name = $user->name;
    $userr=$user->user;
    $email = $user->email;
    $spons = $user->referee_id;
   
$i=1;
$a=1;
    $access=DB::SELECT("SELECT * from users where referee_id='$spons'");
        $check=count($access);
$clubs=DB::SELECT("SELECT * from clubs where user='$userr'");
?>

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
<body class="hold-transition sidebar-mini">
<div class="wrapper">
@include('user.user-dashboard-base')
 <div class="content-wrapper" id="center-side">

    <div class="content">
        <div class="container-fluid">

          <div class="tabs tab_links">
            <a href="#" class="" onclick="openTabContent(event, 'clubs')" id="defaultTab">MyClub</a>
            <a href="#" class="" onclick="openTabContent(event, 'myClub')" >Clubs</a>
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
                                         <p class="text-center">The most Peaple have joined these clubs. Are u interesting? click on club to join</p>
                                    </div>
                                 </div>
                                 <!-- /.card-header -->
                                 <div class="card-body">
                                     <div class="direct-chat-messages">
                                         <!-- Add font awesome icons -->
                                            <div class="px-3 d-flex justify-content-start align-items-center ">  
                                                    <div class="ml-3 mr-3">
                                                        1
                                                    </div>
                                                    <div class="link-icon">  
                                                        <div><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                                                        <div class="ml-3">diners-club</div>
                                                    </div>
                                             </div>
                                         
                                         <hr>
                                         
                                          <div class="px-3 d-flex justify-content-start align-items-center ">  
                                                    <div class="ml-3 mr-3">
                                                        2
                                                    </div>
                                                    <div class="link-icon">  
                                                        <div><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                                                        <div class="ml-3">diners-club</div>
                                                    </div>
                                             </div>
                                         <hr>

                                         

                                     </div>


                                 </div>
                             </div>
                    </div>

<?php $all=DB::SELECT("SELECT * from clubs");?>

                    <div class="allClubs col-md-6">
                        <div class="card direct-chat direct-chat-primary px-2 ">
                                 <div class="card-header d-flex flex-column align-items-center">
                                    <div class="club_main_icon">
                                        <i class="las la-users"></i>
                                    </div>
                                    <div class="club_main_title">
                                        <h3 class="card-title">All Clubs</h3>
                                    </div>
                                    <div class="club_main_subHead">   
                                         <p class="text-center">Here, the list of all created club. click to join </p>
                                    </div>
                                 </div>
                                 
                                 <!-- /.card-header -->
                                 <div class="card-body">
                                     <div class="direct-chat-messages">
                                         <!-- Add font awesome icons -->
                                            <div class="px-3 d-flex justify-content-start align-items-center ">  
                                                    <div class="ml-3 mr-3">
                                                        1
                                                    </div>
                                                    <div class="link-icon">  
                                                        <div><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                                                        <div class="ml-3"><a href="#club1">test mmddd</a></div>
                                                    </div>
                                             </div>
                                         
                                         <hr>
                                         
                                          
                                          
                                         
                                         
                                        

                                     </div>


                                 </div>
                             </div>
                    </div>


                </div>

     <!-- club in details -->

            <div class="my-3" id="club1">
                <div class="clubBox w-md-50">
                 
                    <div class="d-flex align-items-center flex-column">
                        <div class="clubImg"><img src="{{asset('assets/a/img/club.png')}}" class="links "></div> 
                        <div class="clubTitle">diners-club</div>
                        <div class="clubDetails">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</div>
                    </div>
                    

                    <div class=" rounded clubNums">
                        <div class="team_leader d-flex justify-content-between">
                            <div>Team Leader:</div>
                            <div>Vadim Rafalskiy</div>
                        </div>
                         <div class="team_size d-flex justify-content-between">
                            <div>Team size:</div>
                            <div>0</div>
                        </div>
                    </div>

                    <div class="joinClub d-flex justify-content-center my-3">
                        <button class="btn btn-primary font-weight-bold  rounded-pill px-5">Join Club</button>
                    </div>
                   
                </div>
            </div>
            

             <!-- create club -->
                <div class="createClub">
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
         



           




         </div> 
 
          <div class="club_struct tabcontent my-3" id="clubs">
            <div class="row">
                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Total Member</div>
                        <div class="members" style="font-size: 13px">Members: <span>0</span></div>
                    </div>
                </div>

                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Joined Member</div>
                        <div class="members" style="font-size: 13px">Members: <span>0</span></div>
                    </div>
                </div>

                <div class="topBox bg-white shadow rounded p-3 d-flex align-items-center  col-12 col-sm-12 col-md mx-md-2 my-2">
                    <div class="mx-3 club-icon">
                        <i class="las la-users"></i>
                    </div>
                    <div class="mx-2">
                        <div class="title font-weight-bold" style="color:#000050; font-size:15px;">Requested Member</div>
                        <div class="members" style="font-size: 13px">Members: <span>0</span></div>
                    </div>
                </div>
            </div>
             @if(count($clubs)>0)
              <h3 class="text-center mt-3">Club structure</h3>
                <div class="row">
                <div class="col-12 col-sm-6 col-md-12"> 
                    <div class="table-container rounded" style="overflow-x: scroll;">
                      <table class="table table-striped hist_table" style="text-align: center; " >
                            
                            <div class="d-flex justify-content-between align-items-center ml-3 mr-4" >
                                <p class="pt-3 pl-3 font-weight-bold my-3">Club Members</p>
                                <div class="inviteContainer">
                                    <button class="btn btn-primary" id="invite_btn">+ invite</button>
                                    <div class="invite" id="invite_form">
                                        <form>
                                            <input type="text" name="name" placeholder="User name" class="form-control">
                                            <a href="" class="btn btn-primary inviteBtn font-weight-bold">invite</a>
                                        </form>
                                    </div>
                                </div>
                              </div>
                        
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
                            
                          
                          </tr>
                        </thead>
                    <tbody>
                    
                     
                      @foreach ($members as $joined)
                       <?php $sponsed=DB::SELECT("SELECT * from users where referee_id='$joined->referee_id'"); ?>
                     <?php $member=DB::SELECT("SELECT * from users where user='$joined->member'"); ?>
                      <?php $activation=DB::SELECT("SELECT * from activations where email='$user->email'"); ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$user->member}}</td>
                            <td>$activation[0]->created_at</td>
                            <td>$member[0]->country</td>
                            <td>Member</td>
                            <td>$sponsed[0]->user</td>
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
        if (intValue>5)
        {

               document.getElementById('access2').innerHTML="Not allowed to create club. You need to have "+intValue+" direct referral To be allowed to create club";
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
    // toggle invite form 

    let invitBtn = document.getElementById('invite_btn');
    let invitForm = document.getElementById('invite_form')

    invitBtn.addEventListener("click", ()=>{
        invitForm.classList.toggle("inviteShow");
    })

</script>

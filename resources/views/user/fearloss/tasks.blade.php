
@include('user.user-dashboard-base')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
           <div class="tabs tab_links" >
              <span class="links_tabs d-flex ">
                <a href="{{route('user.dashboard.payclick')}}" class="fomoLink" id="tabs"> <i class="fa-solid fa-hand-point-up"></i> Pay clicks</a>
                <a href="{{route('user.dashboard.payvideo')}}" class="fomoLink"><i class="fa-solid fa-video"></i> Watch Videos</a>
              </span>

              <a  href="/user/dashboard/download-app" class="fomoLink" ><i class="fa-solid fa-download"></i> Download App <i style="color: red">Coming soon</i></a>
              <a  href="{{route('user.dashboard.payvideo.invoice')}}" class="fomoLink" ><i class="fa-solid fa-book"></i> Invoices </a>
              <a  href="/user/dashboard/fixedads" class="fomoLink" > <i class="fa-solid fa-rectangle-ad"></i>
              Fixed Ads </a>
              <a  href="/user/dashboard/tasks" class="fomoLink" ><i class="fa-solid fa-tasks"></i>Tasks </a>

          </div>
      </div><!-- /.container-fluid -->
    </div>


    <div>
        <div class="container">
            Tasks List
            </div>
    </div>
<!-- row one -->
 <div class="row mx-5">
    
<!-- Social Media 1 -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/fb.png') }}" class="img-fluid rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Follow us on Facebook</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+1</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>


<!-- Social Media 2 -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/inst.png') }}" class="img-fluid rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Follow us on Instagram</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+1</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>



 <!-- Social Media 3-->
 <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/tg.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Join our TG channel</span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
                style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+1</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>



<!-- Social Media 4 -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/x.png') }}" class="img-fluid  rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Follow our X account</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+1</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>


<!-- 3 friends -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/frnd.png') }}" class="img-fluid  rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Invite 3 friends</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+3</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>


<!-- 7  friends -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/frnd.png') }}" class="img-fluid  rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Invite 7 friends</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+8</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>


<!-- 10 friends -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center" style="padding: 8px;">
    <div class="mr-3">
        <img src="{{ asset('image/frnd.png') }}" class="img-fluid  rounded-lg shadow" 
             style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
    </div>
    
    <div class="flex-grow-1">
        <div class="mb-2">
            <div class="d-flex align-items-center mb-2">
                <span class="text-white   mb-0">Invite 10 friends</span>
            </div>
            
            <div class="d-flex align-items-center">
            <img src="{{ asset('image/diamond.png') }}" class="rounded-circle" 
            style="height: 25px; width: 25px; object-fit: cover;" alt="Ad Image">
                <h6 class="text-white font-weight-bold  mb-0">+12</h6>
            </div>
        </div>
    </div>
    <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
        <span class="">Follow</span>
    </div> -->
</div>

    </div>
</div>




  <!-- Like and comment on x -->
  <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/x.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Like and Commment on X</span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+50,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>
    

      <!-- Folllow ticktok -->
  <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/tik.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Follow Tiktok</span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+50,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>
    


      <!-- Post about Befonex on x -->
      <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/x.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Post About {{ (env('APP_NAME')) }} On X </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+75,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>
    

    <!-- Like and comment on instagram -->
    <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/inst.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Like, Comment And Story </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+10,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>




    <!-- Daily rewards -->
    <div class="col-md-4">
        <!-- Daily Rewards Title -->
        <div class="text-center">
            <h4 class= "font-weight-bold small">Daily Rewards</h4>
        </div>
        
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/clndr.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Daily rewards </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+10,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>



        <!-- Select Sponsor-->
        <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/hs.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Select Sponsor</span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+5,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>



        <!-- Subscribe Telegram-->
        <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/tg.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Subscribe Telegram channel </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+100,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>



        <!-- Invite friends-->
        <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/usrinv.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Invite friends </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+50,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>


        <!-- Invite friends-->
        <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/ytb.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Subscribe Youtube channel </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+100,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>



        <!-- Subscribe X account-->
        <div class="col-md-4">
        <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #00008B, #000066);">
            <!-- Smaller Image Items -->
            <div class="d-flex align-items-center" style="padding: 8px;">
        <div class="mr-3">
            <img src="{{ asset('image/x.png') }}" class="img-fluid rounded-lg shadow" 
                 style="height: 50px; width: 50px; object-fit:" alt="Ad Image">
        </div>
        
        <div class="flex-grow-1">
            <div class="mb-2">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-white   mb-0">Subscribe X Account </span>
                </div>
                
                <div class="d-flex align-items-center">
                <img src="{{ asset('image/coin.png') }}" class="rounded-circle" 
                style="height: 20px; width: 20px; object-fit: cover;" alt="Ad Image">
                    <h6 class="text-white font-weight-bold  mb-0">+50,000</h6>
                </div>
            </div>
        </div>
        <!-- <div class="d-flex align-items-center bg-white bg-opacity-25  px-3 py-1 w-fit-content" style="border-radius: 10px;">
            <span class="">Follow</span>
        </div> -->
    </div>
    
        </div>
    </div>


</div>

    
    



 </div>




    </div>
    @include('user.footer')
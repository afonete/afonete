
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


<!-- row one -->
 <div class="row mx-5">
    
 <!-- app1 -->
 <div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center p-3">
            <div class="mr-3">
                <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                     style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
            </div>
            
            <div class="flex-grow-1">
                <div class="mb-2">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                    </div>
                </div>
                
                
            </div>
            <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    <span class="">Install</span>
                </div>
        </div>
    </div>
</div>



<!-- app2 -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center p-3">
            <div class="mr-3">
                <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                     style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
            </div>
            
            <div class="flex-grow-1">
                <div class="mb-2">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                    </div>
                </div>
                
                
            </div>
            <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    <span class="">Install</span>
                </div>
        </div>
    </div>
</div>


<!-- app3 -->
<div class="col-md-4">
    <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
        <!-- Smaller Image Items -->
        <div class="d-flex align-items-center p-3">
            <div class="mr-3">
                <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                     style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
            </div>
            
            <div class="flex-grow-1">
                <div class="mb-2">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                             style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                        <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                    </div>
                </div>
                
                
            </div>
            <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    <span class="">Install</span>
                </div>
        </div>
    </div>
</div>

 </div>


 <!-- row two -->
 <div class="row mx-5">
    
    <!-- app1 -->
    <div class="col-md-4">
       <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
           <!-- Smaller Image Items -->
           <div class="d-flex align-items-center p-3">
               <div class="mr-3">
                   <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                        style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
               </div>
               
               <div class="flex-grow-1">
                   <div class="mb-2">
                       <div class="d-flex align-items-center mb-2">
                           <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                       </div>
                       
                       <div class="d-flex align-items-center">
                           <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                       </div>
                   </div>
                   
                   
               </div>
               <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                       <i class="fas fa-clock text-warning mr-2"></i>
                       <span class="">Install</span>
                   </div>
           </div>
       </div>
   </div>
   
   
   
   <!-- app2 -->
   <div class="col-md-4">
       <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
           <!-- Smaller Image Items -->
           <div class="d-flex align-items-center p-3">
               <div class="mr-3">
                   <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                        style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
               </div>
               
               <div class="flex-grow-1">
                   <div class="mb-2">
                       <div class="d-flex align-items-center mb-2">
                           <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                       </div>
                       
                       <div class="d-flex align-items-center">
                           <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                       </div>
                   </div>
                   
                   
               </div>
               <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                       <i class="fas fa-clock text-warning mr-2"></i>
                       <span class="">Install</span>
                   </div>
           </div>
       </div>
   </div>
   
   
   <!-- app3 -->
   <div class="col-md-4">
       <div class="card shadow-lg" style="border-radius: 15px; background: linear-gradient(145deg, #0099ff, #0066cc);">
           <!-- Smaller Image Items -->
           <div class="d-flex align-items-center p-3">
               <div class="mr-3">
                   <img src="{{ asset('image/adss3.png') }}" class="img-fluid rounded-lg shadow" 
                        style="height: 80px; width: 80px; object-fit: cover; border: 3px solid #fff;" alt="Ad Image">
               </div>
               
               <div class="flex-grow-1">
                   <div class="mb-2">
                       <div class="d-flex align-items-center mb-2">
                           <img src="{{ asset('image/bn.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">Ad Name</h6>
                       </div>
                       
                       <div class="d-flex align-items-center">
                           <img src="{{ asset('image/coin.png') }}" class="rounded-circle mr-2" 
                                style="height: 30px; width: 30px; object-fit: cover;" alt="Ad Image">
                           <h6 class="text-white font-weight-bold mb-0">20,000</h6>
                       </div>
                   </div>
                   
                   
               </div>
               <div class="d-flex align-items-center bg-white bg-opacity-25 rounded-pill px-3 py-1 w-fit-content">
                       <i class="fas fa-clock text-warning mr-2"></i>
                       <span class="">Install</span>
                   </div>
           </div>
       </div>
   </div>
   
    </div>

    </div>
    @include('user.footer')
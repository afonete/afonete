<?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="content-wrapper py-5">


<div class="tabs tab_links" >
        <span class="links_tabs d-flex ">
          <a href="<?php echo e(route('profile.edit')); ?>" class="fomoLink" id="tabs"> <i class="fa-regular fa-address-card"></i> My profile</a>
          <a href="<?php echo e(route('password.show')); ?>" class="fomoLink"><i class="fa-solid fa-lock"></i> Password</a>
          <a href="<?php echo e(route('wallet')); ?>" class="fomoLink"><i class="fa-solid fa-wallet"></i> wallet address</a>
          <a href="/user/kyc" class="fomoLink"><i class="fa-solid fa-id-card"></i>KYC</a>
        </span>
      </div>


<div class="bg-white py-3 mx-3 rounded-lg shadow">
<h1>Personal Info</h1>

  <div class="row ">
    
    <div class="col-md-6 col-sm-12">
      <form action="" method="POST" enctype="multipart/form-data">
    
        <div class="row mx-3">
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">Date of Birth</label>
            <input type="date" name="dob" class="form-control" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">Phone Number</label>
            <input type="tel" name="phone" class="form-control" required>
          </div>

          <div class="col-12 col-md-12 mb-3">
            <label class="form-label small">Email</label>
            <input type="tel" name="email" class="form-control" required>
          </div>

          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">City</label>
            <input type="text" name="city" class="form-control" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">Country</label>
            <select name="country" class="form-control" required>
              <option value="">Select Country</option>
              <option value="US">United States</option>
              <option value="UK">United Kingdom</option>
              <option value="CA">Canada</option>
            </select>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label class="form-label small">ID Type</label>
            <select name="id_type" class="form-control" required>
              <option value="">Select ID Type</option>
              <option value="passport">Passport</option>
              <option value="national_id">National ID</option>
              <option value="drivers_license">Driver's License</option>
            </select>
          </div>
        </div>
    </div>


  <div class="col-md-6 col-sm-12">
<div class="row m-3">

    <div class="col-md-4 my-2">
        <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Image" class="img-fluid w-100">
    </div>   

    <div class="col-md-8 my-2">
    <p class="text-dark">National Identity (Front)</p>
        <div class="drag-drop-box transition-all" style="border: 2px dashed #ccc; padding: 10px; text-align: center; border-radius: 10px;">
            <div class="text-center">
             
                <span class="text-muted small">Drag & Drop files here</span>
                <span class="text-muted text-sm">or</span>
                <label class="submit-btn text-sm cursor-pointer bg-primary px-2 rounded py-1">
                    <input type="file" class="" style="display: none;" accept="image/*,.pdf">
                    Choose Files
                </label>
                
            </div>
        </div>
    </div>

    <div class="col-md-4 my-2">
   
        <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Image" class="img-fluid w-100">
    </div>   

    <div class="col-md-8 my-2">
    <p class="text-dark">National Identity (Back)</p>
        <div class="drag-drop-box transition-all" style="border: 2px dashed #ccc; padding: 10px; text-align: center; border-radius: 10px;">
            <div class="text-center">
             
                <span class="text-muted small">Drag & Drop files here</span>
                <span class="text-muted text-sm">or</span>
                <label class="submit-btn text-sm cursor-pointer bg-primary px-2 rounded py-1">
                    <input type="file" class="" style="display: none;" accept="image/*,.pdf">
                    Choose Files
                </label>
                
            </div>
        </div>
    </div>

    
</div>


<div class="row m-3">
    <div class="col-md-12">
    <p class="text-dark">Note : Maximum image size should be 5 MB. Accepted file formats are .JPG, .JPEG and .PNG.
    Photo Selfie</p>
    </div>

    <div class="col-md-4 my-2">
        <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Image" class="img-fluid w-100">
    </div>   

    <div class="col-md-8 my-2">
        <div class="drag-drop-box transition-all" style="border: 2px dashed #ccc; padding: 10px; text-align: center; border-radius: 10px;">
            <div class="text-center">
             
                <span class="text-muted small">Drag & Drop files here</span>
                <span class="text-muted text-sm">or</span>
                <label class="submit-btn text-sm cursor-pointer bg-primary px-2 rounded py-1">
                    <input type="file" class="" style="display: none;" accept="image/*,.pdf">
                    Choose Files
                </label>
                
            </div>
        </div>
    </div>

    <div class="col-md-12">
    <p class="text-dark">Note : Maximum image size should be 5 MB. Accepted file formats are .JPG, .JPEG and .PNG.
    Photo Selfie</p>
    </div>
    <div class="col-md-4 my-2">
        <img src="<?php echo e(asset('image/kyimg.png')); ?>" alt="KYC Image" class="img-fluid w-100">
    </div>   

    <div class="col-md-8 my-2">
        <div class="drag-drop-box transition-all" style="border: 2px dashed #ccc; padding: 10px; text-align: center; border-radius: 10px;">
            <div class="text-center">
             
                <span class="text-muted small">Drag & Drop files here</span>
                <span class="text-muted text-sm">or</span>
                <label class="submit-btn text-sm cursor-pointer bg-primary px-2 rounded py-1">
                    <input type="file" class="" style="display: none;" accept="image/*,.pdf">
                    Choose Files
                </label>
                
            </div>
        </div>
    </div>
</div>
  
  </div>




  <div class="col-12 mt-3  text-center">
    <button type="submit" class="btn btn-primary">Submit KYC</button>
  </div>
  </form>

  </div>


  
  </div>  

<?php echo $__env->make('user.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/kyc.blade.php ENDPATH**/ ?>
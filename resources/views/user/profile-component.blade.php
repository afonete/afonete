<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->name;
$uname = $user->user;
$email=$user->email;
$phone=$user->phone;
$gender=$user->gender;
$country=$user->country;
$package=$user->has_paid_package;
?>

@include('user.user-dashboard-base')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="content-wrapper py-5">

   <div class="tabs tab_links" >
        <span class="links_tabs d-flex ">
          <a href="{{route('profile.edit')}}" class="fomoLink" id="tabs"> <i class="fa-regular fa-address-card"></i> My profile</a>
          <a href="{{route('password.show')}}" class="fomoLink"><i class="fa-solid fa-lock"></i> Password</a>
          <a href="{{route('wallet')}}" class="fomoLink"><i class="fa-solid fa-wallet"></i> wallet address</a>
          <a href="/user/kyc" class="fomoLink"><i class="fa-solid fa-id-card"></i>KYC</a>
        </span>
      </div>
    <br>
    <div class="prof-update">
        <h2 class="upd-title">User Information Update
            <span class="bg-success"> 
                {{session('success')??''}}
            </span>
             <span class="bg-danger"> 
               {{session('fail')??''}}</span>

            </h2>
            <div class="row upd-row">   
                <div class="col-lg-3 prof-img">
                     <img src="{{asset('assets/a/img/user.png')}}" class="" alt="User Image">
                     <div class="UserName">
                         {{$name}}
                     </div>
                </div>
                <div class="col-lg-8">
                        <div class="container-fluid">
                            @if(!$show)
                            <form action="{{route('profile.updates')}}" method="post">
                                @csrf
                              <!--   <div class=" form-group">
                                    <label for="profile-pic">Profile Picture:</label>
                                    <input type="file" id="profile-pic" name="pic">
                                </div> -->
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="name">Username:</label>
                                        <input type="text" id="name" name="username" value="{{$uname}}" required disabled>
                                    </div>
                                       <div class="form-group col">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="{{$name}}" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="member_id">Member ID:</label>
                                        <input type="text" id="member_id" name="member_id" value="" placeholder="Member ID" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">City:</label>
                                        <input type="text" id="city" name="city" value="" placeholder="City" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="dob">Date of Birth:</label>
                                        <input type="date" id="dob" name="dob" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="joining_date">Joining Date:</label>
                                        <input type="date" id="joining_date" name="joining_date" value="" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="referral_date">User Referral Date:</label>
                                        <input type="date" id="referral_date" name="referral_date" value="" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="referral_name">Referral User Name:</label>
                                        <input type="text" id="referral_name" name="referral_name" value="" placeholder="Referral User Name" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="referral_id">Referral ID:</label>
                                        <input type="text" id="referral_id" name="referral_id" value="" placeholder="Referral ID" required>
                                    </div>
                                    <div class="form-group col">
                                        <label for="referral_email">Referral Email:</label>
                                        <input type="email" id="referral_email" name="referral_email" value="" placeholder="Referral Email" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <label for="referral_country">Referral Country:</label>
                                        <select id="referral_country" class="form-group " name="referral_country" required>
                                            <option value="">Select Country</option>
                                            <option value="USA">United States</option>
                                            <option value="UK">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="India">India</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Japan">Japan</option>
                                            <option value="China">China</option>
                                            <option value="Brazil">Brazil</option>
                                        </select>
                                    </div>
                                 </div>
                                 
                              
                                </div>                                                           
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" value="{{$email}}" disabled>
                                    </div>
                                    <div class="form-group col">
                                        <label for="phone">Phone:</label>
                                        <input type="tel" id="phone" name="phone" value="{{$phone}}" required>
                                    </div>
                                </div>
                                  <div class="form-group">
                                    <label for="phone">Country:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$country}}</label>
                                </div>
                                         <!-- <div class="form-group">
                                            <label for="phone">Gender: {{$gender}}
                                        @if($gender=='not' )       
                                    &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" checked> Custom &nbsp;&nbsp;&nbsp;
                                           <input type="radio" name="gender" value="f" checked> Female&nbsp;&nbsp;

                                           <input type="radio" name="gender" value="m"> Male</label>

                                            @endif
                                            @if($gender=='f') 
                                              &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not"> Custom &nbsp;&nbsp;&nbsp;
                                                               <input type="radio" name="gender" value="f" checked> Female&nbsp;&nbsp;

                                                               <input type="radio" name="gender" value="m"> Male</label>

                                            @endif
                                            @if($gender=='m') 
                                                                &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" checked> Custom &nbsp;&nbsp;&nbsp;
                                                                 <input type="radio" name="gender" value="f" > Female&nbsp;&nbsp;

                                                         <input type="radio" name="gender" value="m" checked> Male</label>


                                                        @else

                                                        &nbsp;&nbsp;&nbsp;&nbsp;  <input type="radio" name="gender" value="not" > Custom &nbsp;&nbsp;&nbsp;
                                                                 <input type="radio" name="gender" value="f" > Female&nbsp;&nbsp;

                                                         <input type="radio" name="gender" value="m" checked> Male</label>
                        @endif

                                           
                                        </div> -->
                                <input type="submit" class="font-weight-bold" value="Update">
                            </form>
                         <center>  
                        <a s href="{{route('password.show')}}" tyle="color: red;">change password</a></center>

                        <br>
                                @else
                        <!-- <h2>Change Password <br></h2> -->
                        <center> <span class="bg-success"> 
                           {{session('status')??''}}</span></center>
                            <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                
                        <form action="{{route('password.update')}}" method="post" class="pb-5">
                            @csrf
                          <!--   <div class=" form-group">
                                <label for="profile-pic">Profile Picture:</label>
                                <input type="file" id="profile-pic" name="pic">
                            </div> -->
                            <div class="form-group">
                                <label for="name">Current password:</label>
                                <input type="password" id="current_password" name="current_password" placeholder="current password" required>
                            </div>
                            <div class="wrap-input100 validate-input" data-validate="Enter password">
                                    <span class="btn-show-pass">
                                        <i class="zmdi zmdi-eye"></i>
                                    </span>
                                    <input class="input100" name="password" type="password" placeholder="Enter Password"
                                        name="password" required autocomplete="current-password">
                                    <span class="focus-input100"></span>

                                </div>
                                <div class="wrap-input100 validate-input">
                                <x-input-label for="password_confirmation" /><span class="btn-show-pass"><i
                                        class="zmdi zmdi-eye"></i></span>
                                <x-text-input id="password_confirmation" class="input100" type="password"
                                     name="password_confirmation" required placeholder="Re-enter Password" /><span
                                    class="focus-input100"></span>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="password_pin" style="color: #4b5563; font-size: 14px;">Email Verification PIN <span class="text-danger">*</span></label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" name="pin" placeholder="Enter 6-digit PIN" required style="flex: 1; padding: 10px;" class="form-control">
                                    <button type="button" class="btn btn-info btn-sm font-weight-bold send-pw-pin-btn" style="width: 100px; background-color: #3b82f6; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">Send PIN</button>
                                </div>
                            </div>

                            <br>
                            <input type="submit" value="Change">
                        </form>


                    

                        <!-- second transaction -->
                        <form action="{{ route('transaction-password.update') }}" method="post" class="pb-5">
                            @csrf
                            <h4>Second Transaction Password</h4>
                            <p class="text-muted">This password is required when making withdrawals, transfers, token swaps, and token withdrawals.</p>
                            <div class="form-group">
                                <label for="current_transaction_password">Current login password:</label>
                                <input type="password" id="current_transaction_password" name="current_password" placeholder="Current login password" required>
                                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                            </div>
                            <div class="wrap-input100 validate-input" data-validate="Enter second transaction password">
                                <span class="btn-show-pass">
                                    <i class="zmdi zmdi-eye"></i>
                                </span>
                                <input class="input100" id="transaction_password" name="transaction_password" type="password" placeholder="Enter Second Transaction Password"
                                    required autocomplete="new-password">
                                <span class="focus-input100"></span>
                                <x-input-error :messages="$errors->get('transaction_password')" class="mt-2" />
                            </div>
                            <div class="wrap-input100 validate-input">
                                <x-input-label for="transaction_password_confirmation" />
                                <span class="btn-show-pass"><i class="zmdi zmdi-eye"></i></span>
                                <x-text-input id="transaction_password_confirmation" class="input100" type="password"
                                     name="transaction_password_confirmation" required placeholder="Re-enter Second Transaction Password" autocomplete="new-password" />
                                <span class="focus-input100"></span>
                                <x-input-error :messages="$errors->get('transaction_password_confirmation')" class="mt-2" />
                            </div>

                            <div class="form-group mt-3">
                                <label for="transaction_password_pin" style="color: #4b5563; font-size: 14px;">Email Verification PIN <span class="text-danger">*</span></label>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" name="pin" placeholder="Enter 6-digit PIN" required style="flex: 1; padding: 10px;" class="form-control">
                                    <button type="button" class="btn btn-info btn-sm font-weight-bold send-pw-pin-btn" style="width: 100px; background-color: #3b82f6; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px;">Send PIN</button>
                                </div>
                            </div>

                            <br>
                            <input type="submit" value="Change Second Transaction Password">
                        </form>




                        @endif
                        <style>
                            .container{
                                width: 100%;
                                max-width: 500px;
                                  margin-left: 480px;
                                padding-top: 40px;
                                padding-right: 0px;
                                background-color: #f2f2f2;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            }
                            .row{
                              
                            }
                            h2 {
                                text-align: center;
                            }

                            .form-group {
                                margin-bottom: 15px;
                            }

                            label {
                                display: block;
                                font-weight: bold;
                            }

                            input[type="text"],
                            input[type="email"],
                            input[type="tel"],
                            input[type="file"],input[type="password"] {
                                width: 100%;
                                padding: 8px;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                            }

                            input[type="submit"] {
                                width: 100%;
                                padding: 10px;
                                background-color: #4CAF50;
                                color: #fff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }

                            input[type="submit"]:hover {
                                background-color: #45a049;
                            }

                            /* Responsive Styles */
                            @media screen and (max-width: 480px) {
                                .container {
                        /*            padding: 10px;*/
                                    margin-left: 40px;
                                }
                             input[type="submit"] {
                                    font-size: 14px;
                                }
                            }
                             
                                @media screen and (max-width: 876px) {
                                .container {
                                    padding: 10px;
                                    margin-left: 80px;
                                }
                             input[type="submit"] {
                                    font-size: 14px;
                                }
                            }
                          
                            </style>
                        </div>            
                </div>
            </div>        
    </div>



@include('user.footer')
</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('.send-pw-pin-btn').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.prop('disabled', true).text('Sending...');
        
        $.ajax({
            url: "{{ route('password.send-pin') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.ok) {
                    alert(response.message);
                    $btn.text('Sent!');
                    // Re-enable after 60 seconds countdown
                    var countdown = 60;
                    var interval = setInterval(function() {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            $btn.prop('disabled', false).text('Send PIN');
                        } else {
                            $btn.text('Resend (' + countdown + 's)');
                        }
                    }, 1000);
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).text('Send PIN');
                }
            },
            error: function(xhr) {
                alert('Could not send PIN. Please try again later.');
                $btn.prop('disabled', false).text('Send PIN');
            }
        });
    });
});
</script>
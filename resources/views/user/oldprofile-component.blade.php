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
<div class="content-wrapper py-5">
   
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
                            <br>
                            <input type="submit" value="Change">
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




</div>
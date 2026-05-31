
<style type="text/css">
        *{
            font-family: Arial, Helvetica, sans-serif;
        }
        .login-form{
            width: 40%;
            margin: 50px auto;
            border-radius: 3px;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.19) ;
            padding: 25px 30px;
        }
        .login-title{
            font-size: 35px;
        }
        .txt a{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: red;
        }
        @media screen and (max-width: 700px){
            .login-form{
                width: 80%;
                margin: 80px auto;
            }
        }
    </style>     
     <x-guest-layout>

    </span>
<div class="login-form">
<form action="{{ route('login') }}" method="post" class="validate-form">
   
@csrf 
   
 {{$message??''}}
   <h2 class="form_header_login">Admin panel</h2>
   @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
@if ($errors->has('email') || $errors->has('password'))
    <span class="alert alert-danger">
        {{ $errors->first('email') }}
        {{ $errors->first('password') }}
    </span>
@endif
   <div class="mt-5 mb-4 validate-input" data-validate="Valid email is: a@b.c">
        <div class="input-group">
            <div class="input-group-btn">
                <button class="btn btn-default " type="submit">
                    <i class="las la-envelope input_icon"></i>
                </button>
            </div>
            <input class="input100 formInputs" name="email" type="email" placeholder="Enter Email" name="email"
                :value="old('email')" required>
        </div>
           
            <span class="focus-input100" ></span>

        </div>

        <div class="validate-input mb-4" data-validate="Enter password">
            <span class="btn-show-pass">
                <i class="zmdi zmdi-eye"></i>
            </span>
            <div class="input-group">
                <div class="input-group-btn">
                    <button class="btn btn-default " type="submit">
                        <i class="las la-envelope input_icon"></i>
                    </button>
                </div>
                <input class="input100 formInputs" name="password" type="password" placeholder="Enter Password"
                name="password" required autocomplete="current-password">
            </div>
            
            <span class="focus-input100"></span>

        </div>
        <div style="margin-bottom: 1rem;">
            <label for="remember_me" style="display: flex; align-items: center;">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <span style="margin-left: 1.5rem; font-size: 1.575rem;">{{ __('Remember me') }}</span>
            </label>
        </div>
        <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
                <div class="login100-form-bgbtn"></div>
                <button class="login100-form-btn" id="logbutton">
                    Login
                    <div class="spinner-border m-5" style="width: 3rem; height: 3rem;display:none"
                        role="status" id="loader"></div>
                </button>
            </div>
        </div>

        <br>

        <div class="text-center ">
            

            
            <br>
        <span class="txt">
                <a href="{{ route('password.request') }}" style="color:red" class="signuping"> forget Password</a>
            </span>
            
    </div>
                      
</form>
     </div>
        </div>
        </div>

        <script>
        document.getElementById("logbutton").addEventListener("click", function() {
            document.getElementById("loader").style.display = "block";
        });
        </script></x-guest-layout>
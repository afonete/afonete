

    <!-- Session Status -->
    <auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <auth-validation-errors class="mb-4" :errors="$errors" />


<x-guest-layout style="height: 100%;">
    <div style="background:#f2f2f2; height: 10%; ">    <span class="login100-form-title" >
        @if(session('status'))
        <!-- {{session('status')??''}} -->
       <span style="font-size: 25px; width: 700px;color:#e91e63"> We have e-mailed you 
        a password reset link!<br>
check your email including spam  </span>  
 <br><br><br><br>
        @else
        <span style="font: 32px raleway,sans-serif;">
       {{('RESET YOUR PASSWORD')}}<br><br><br>
   </span><span style="font: 15px raleway,sans-serif;;color:#e91e63">
       {{('A password reset link will be sent to your email.')}}

       @endif
    </span></div>

    <b>

        <div class="container-login100">
            <div class="wrap-login100 col-md-4" style="margin-top: -320px; margin-bottom: 0px;">

                <form class=" validate-form" method="POST" action="{{ route('password.email') }}">
                    @csrf

                   
                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" name="email" type="email" placeholder="Enter Your email"
                            required autocomplete="current-email">
                        <span class="focus-input100"></span>

                    </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2 bg-danger" style="width:300px" />
                 
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" type="submit" id="logbutton">
                                Request password link
                                
                            </button>
                        </div>
                            <br>

                    <a style="color: #e91e63;font-size: 20" href="{{route('login')}}">Back to Login</a>

                    </div>

                    <br>

                    
                    
                      
                      </div>
                      
                </form>
            </div>
        </div>
        </div>

        <script>
        document.getElementById("logbutton").addEventListener("click", function() {
            document.getElementById("loader").style.display = "block";
        });
        </script>
</x-guest-layout>

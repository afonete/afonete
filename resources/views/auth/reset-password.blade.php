

<x-guest-layout>
    <x-auth-card>
<span class="login100-form-title">
        Password reset Form<br>
{{$error??''}}
<br><br>
    </span>
        <!-- Validation Errors -->
        <!-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> -->

     
      
            </div>

    <b>
        <x-input-error :messages="$errors->get('password')" class="mt-2  " style="width:300px;color: red;" />
        <x-input-error :messages="$errors->get('email')" class="mt-2 " style="width:300px;color: red;" />

        <div class="container-login100">
            <div class="wrap-login100 col-md-4" style="margin-top: -180px;">

                <form class=" validate-form" method="post" action="{{ route('password.store') }}">
                    @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c">

                    <x-input id="email" class="input100" type="email" name="email" :value="old('email', $request->email)" required autofocus readonly/>
                        <span class="focus-input100" ></span>

                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" name="password" type="password" placeholder="Enter New Password"
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
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" id="logbutton">
                                Reset password
                                <div class="spinner-border m-5" style="width: 3rem; height: 3rem;display:none"
                                    role="status" id="loader"></div>
                            </button>
                        </div>
                    </div>

                    <br>

                      
                </form>
            </div>
        </div>
        </div>

        <script>
        document.getElementById("logbutton").addEventListener("click", function() {
            document.getElementById("loader").style.display = "block";
        });
        </script>
    </x-auth-card>
</x-guest-layout>

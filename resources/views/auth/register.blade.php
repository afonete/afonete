<x-guest-layout>
    <div class="">
        <div class="register shadow-lg rounded">
            <form class="validate-form" style="margin-top: 0;" action="{{ route('register') }}" method="POST">
                @csrf
                <h2 class="form_header">Register Form</h2>
                <p class="bg-secondary">
                    {{$exist ?? ''}}
                    {{$ref ?? ''}}
                </p>

                <!-- Error Messages -->
                @foreach (['danger', 'warning', 'success', 'info', 'exist'] as $msg)
                    @if (Session::has('alert-'.$msg))
                        <div class="alert alert-{{$msg}}" role="alert">
                            {{ Session::get('alert-'.$msg) }}
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        </div>
                    @endif
                @endforeach
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif
                @if ($request)
                    <div class="alert alert-success">
                        <ul>
                            {{$request}}
                        </ul>
                    </div>

                @endif

                <style> .askHelp {
                        display: none;
                    }

                    #referee_input {
                        display: none;
                    }
                </style>


                <div class="registerForm registerDivs " id="regInputs">
                    <div class="leftSide ">
                        <!-- Country -->
                        <div class=" validate-input">
                            <x-input-label for="country"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-globe-americas input_icon"></i>
                                    </button>
                                </div>
                                <select id="countrySelect" name="country" class="input100" required>
                                    <option value="">Select a country</option>
                                </select>
                            </div>

                            <script type="text/javascript">
                                //  const countrySelect = document.getElementById('country');
                                // const limitedCountries = ['Chile','Denmark','Israel','Italy','Rwanda','Norway']
                                const limitedCountries = ['Chile','Denmark','Israel','Italy','Norway']


                                fetch("https://restcountries.com/v3.1/all?fields=name,idd")
                                    .then(response => response.json())
                                    .then(data => {
                                        const filteredCountries = data.filter((item)=>!limitedCountries.includes(item.name.common))

                                        var countryNames = filteredCountries.map(country => country.name.common);
                                        countryNames.sort();

                                        var countrySelect = document.getElementById("countrySelect");
                                        countrySelect.innerHTML = "<option value=''>Select Country</option>"

                                        countryNames.forEach(countryName => {
                                            countrySelect.innerHTML += `<option value='${countryName}'>${countryName}</option>`
                                        });

                                        countrySelect.addEventListener('change', function () {
                                            const selectedCountry = data.find(country => country.name.common === this.value);
                                            const countryCodeDisplay = document.getElementById('phone');
                                            const countryCode = selectedCountry?.idd?.root + (selectedCountry?.idd?.suffixes?.[0] || '');
                                            countryCodeDisplay.value = countryCode || '';
                                        });
                                    })
                                    .catch(error => {
                                        console.log("Error:", error);
                                    });


                            </script>


                        </div>

                        <!-- Full Name -->
                        <div class="validate-input hide_input">
                            <x-input-label for="name"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-user input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="name" class="p-4 input100 formInputs" type="text" name="name"
                                              :value="old('name')" required
                                              placeholder="Enter Your Full Name"/>
                            </div>

                            <span class="focus-input100"></span>
                        </div>

                        <!-- Phone -->
                        <div class=" validate-input hide_input">
                            <x-input-label for="phone"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-phone-volume input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="phone" class="p-4 input100 formInputs" type="text" name="phone"
                                              :value="old('phone')" required
                                              placeholder="Enter Your Phone"/>
                            </div>

                            <span class="focus-input100"></span>
                        </div>


                        <!-- Email -->
                        <div class=" validate-input hide_input">
                            <x-input-label for="email"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-envelope input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="email" class="p-4 input100 formInputs" type="text" name="email"
                                              :value="old('email')" required placeholder="Enter Your Email"/>
                            </div>
                            <span class="focus-input100"></span>
                        </div>


                    </div>


                    <div class="rightSide mt-5">
                        <!-- Referee Number -->

                        <span class="checkbox_ref hide_input" style="margin-top:-30px">
                                <x-input-label for="has_referee_number" class="input-label" id="referre">Do you have a referee number?&nbsp;&nbsp;</x-input-label>
                            <input type="checkbox" id="has_referee_number" name="has_referee_number" {{(request()->query('referral') != '' || (request()->query('side') == 'LEFT' || request()->query('side') == 'RIGHT') ) ? 'checked':''}}>
                            <input type="hidden" name="side" value="{{request()->query('side')}}">

                          </span>

                        <!-- Referee Number Input (hidden by default) -->
                        <div class=" validate-input hide_input" id="referee_input">

                            <x-input-label for="referee"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="lab la-connectdevelop input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="referee" class="p-4 input100 formInputs" type="text" name="referee_id"
                                              value="{{request()->query('referral') ?? ''}}"
                                              placeholder="Enter your referee number "/>
                            </div>

                            <span class="focus-input100"></span>
                        </div>


                        <script>
                            const hasRefereeNumberCheckbox = document.getElementById("has_referee_number");
                            const refereeInput = document.getElementById("referee_input");
                            refereeInput.style.display = "none";

                            hasRefereeNumberCheckbox.addEventListener("change", toggleRefereeInput);
                            if (hasRefereeNumberCheckbox.checked) {
                                refereeInput.style.display = "block";
                            } else {
                                refereeInput.style.display = "none";
                            }

                            function toggleRefereeInput() {
                                if (hasRefereeNumberCheckbox.checked) {
                                    refereeInput.style.display = "block";
                                } else {
                                    refereeInput.style.display = "none";
                                }
                            }


                        </script>

                        <!-- Username -->
                        <div class=" validate-input hide_input">
                            <x-input-label for="Username"/>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-user input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="user" class="p-4 input100 formInputs" type="text" name="user"
                                              :value="old('username')" required
                                              placeholder="Enter Your Username"/>
                            </div>

                            <span class="focus-input100"></span>
                        </div>

                        <div class=" validate-input hide_input">
                            <x-input-label for="password"/>

                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">

                                        <i class="las la-lock input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="password" class=" input100 formInputs" type="password" name="password"
                                              required
                                              placeholder="Enter Your Password"/>
                                <span class="focus-input100"></span>
                            </div>

                            <div id="password-validation-msg" class="password-validation-msg"></div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class=" validate-input hide_input">
                            <x-input-label for="password_confirmation"/>
                            <!-- <span class="btn-show-pass"><i class="zmdi zmdi-eye"></i></span> -->
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <button class="btn btn-default " type="submit">
                                        <i class="las la-lock input_icon"></i>
                                    </button>
                                </div>
                                <x-text-input id="password_confirmation" class="p-4 input100 formInputs" type="password"
                                              name="password_confirmation" required
                                              placeholder="Re-enter Password"/>
                                <span class="focus-input100"></span>
                            </div>

                            <div id="password-confirmation-msg" class="password-confirmation-msg"></div>
                        </div>


                    </div>
                    <div class="column_reg pinRequired hide_input">

                        <div class="checkList checkValid">
                            <div>
                                <h3 class="checkHead">Password Must Have: </h3>
                                <div class="check" id="check1">
                                    <i class="las la-check-circle"></i><span>One uppercase character.</span>
                                </div>
                                <div class="check" id="check2">
                                    <i class="las la-check-circle"></i><span>One lowercase character.</span>
                                </div>
                                <div class="check" id="check3">
                                    <i class="las la-check-circle"></i><span>Contains numeric character</span>
                                </div>
                                <div class="check" id="check4">
                                    <i class="las la-check-circle"></i><span>Contains specific character</span>
                                </div>
                                <div class="check" id="check5">
                                    <i class="las la-check-circle"></i><span>8 characters minimum</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <style>

                    /* Styles for Password Validation and Confirmation Messages */
                    .password-validation-msg,
                    .password-confirmation-msg {
                        font-size: 18px;
                        color: #e74c3c;
                        margin-top: 5px;
                    }

                    .password-validation-msg.valid,
                    .password-confirmation-msg.valid {
                        color: #2ecc71;
                    }

                </style>
                <script>
                    const passwordInput = document.getElementById("password");
                    const passwordConfirmationInput = document.getElementById("password_confirmation");
                    const passwordValidationMsg = document.getElementById("password-validation-msg");
                    const passwordConfirmationMsg = document.getElementById("password-confirmation-msg");
                    const checkList = document.querySelector('.checkList');
                    const regInputs = document.querySelector('#regInputs');
                    const pinRequired = document.querySelector('.pinRequired');

                    // Add event listeners to both password and confirmation fields
                    passwordInput.addEventListener("input", validatePasswords);
                    passwordConfirmationInput.addEventListener("input", validatePasswords);

                    function validatePasswords() {
                        const password = passwordInput.value;
                        const passwordConfirmation = passwordConfirmationInput.value;
                        const uppercaseRegex = /[A-Z]/;
                        const lowercaseRegex = /[a-z]/;
                        const numberRegex = /[0-9]/;
                        const specialCharacterRegex = /[!@#$%^&*()_+{}\[\]:;<>,.?~\-=/\\|]/;
                        const minLength = 8;

                        let isValidPassword = true;
                        let validationMsg = "";

                        if (!uppercaseRegex.test(password)) {
                            document.getElementById("check1").style.color = "red";
                            // validationMsg += "Password must contain at least one uppercase letter.<br>";
                            isValidPassword = false;
                        } else {
                            document.getElementById("check1").style.color = "green";
                        }

                        if (!lowercaseRegex.test(password)) {
                            document.getElementById("check2").style.color = "red";
                            // validationMsg += "Password must contain at least one lowercase letter.<br>";
                            isValidPassword = false;
                        } else {
                            document.getElementById("check2").style.color = "green";
                        }

                        if (!numberRegex.test(password)) {
                            document.getElementById("check3").style.color = "red";
                            // validationMsg += "Password must contain at least one number.<br>";
                            isValidPassword = false;
                        } else {
                            document.getElementById("check3").style.color = "green";
                        }

                        if (!specialCharacterRegex.test(password)) {
                            document.getElementById("check4").style.color = "red";
                            // validationMsg += "Password must contain at least one special character.<br>";
                            isValidPassword = false;
                        } else {
                            document.getElementById("check4").style.color = "green";
                        }

                        if (password.length < minLength) {
                            document.getElementById("check5").style.color = "red";
                            // validationMsg += `Password must be at least ${minLength} characters long.<br>`;
                            isValidPassword = false;
                        } else {
                            document.getElementById("check5").style.color = "green";
                        }

                        if (isValidPassword) {
                            regInputs.classList.remove('onOpen');
                            checkList.classList.add("checkValid");
                            pinRequired.style.display = "none";
                            passwordValidationMsg.innerHTML = "Password is valid.";
                            passwordValidationMsg.classList.add("valid");
                        } else {
                            regInputs.classList.add('onOpen');
                            checkList.classList.remove("checkValid");
                            pinRequired.style.display = "block";
                            passwordValidationMsg.innerHTML = validationMsg;
                            passwordValidationMsg.classList.remove("valid");
                        }

                        // Password confirmation validation
                        if (password === passwordConfirmation) {
                            passwordConfirmationMsg.innerHTML = "Passwords match.";
                            passwordConfirmationMsg.classList.add("valid");
                        } else {
                            // Show the validation message only if the confirmation field has been modified
                            if (passwordConfirmation) {
                                passwordConfirmationMsg.innerHTML = "Passwords do not match.";
                            } else {
                                passwordConfirmationMsg.innerHTML = "";
                            }
                            passwordConfirmationMsg.classList.remove("valid");
                        }
                    }
                </script>


                <style type="text/css">
                    .form-check-label a, .already a {
                        text-decoration: none;
                        color: red;
                    }

                    .already a {
                        text-decoration: none;
                        color: #0000ff;
                    }

                </style>
                <div class="hide_input">
                    <!-- Terms and Conditions Checkbox -->
                    <div style="margin-top: 30px;">
                        <input type="checkbox" class="form-check-input" required>
                        <label for="tos_field" class="form-check-label" style="display: inline !important;">
                        <span class="accept">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            I have read and agree to the <a href="{{ route('tam')}}" target="__blank">Terms and Conditions</a> and
                            <a href="{{ route('policy')}}" target="__blank">Privacy Policy</a>
                        </span>

                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn" id="logbutton" type="submit">
                                <span class="subtBtn">Register</span>
                                <div class="spinner-border m-5" style="width: 3rem; height: 3rem; display: none"
                                     role="status" id="loader"></div>
                            </button>
                        </div>
                    </div>

                    <!-- Already Registered Link -->
                    <div class="flex items-center justify-end mt-4 already">
                        <a class="alreadyReg" href="{{ route('login') }}">
                            {{ __('Already registered? Login') }}
                        </a>
                    </div>

                </div>

            </form>
            <!-- form for rwandan selected -->


        </div>
    </div>

    <script>
        document.getElementById("logbutton").addEventListener("click", function () {
            document.getElementById("loader").style.display = "block";
        });
    </script>
</x-guest-layout>

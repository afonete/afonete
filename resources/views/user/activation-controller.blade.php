
<div class="wrapper">

    @include('user.user-dashboard-base')


    <div class="content-wrapper">
                <div class="container mt-5">
                     @if(session('message'))
                    <p class="alert alert-danger d-flex justify-content-center">
                        {{ session('message') }}
                    </p>
                    @endif
                    <h2>Activation Code

                    </h2>
                    <form action="{{route('user.dashboard.validate')}}" method="post">
                        <!-- <form action="{{route('user.dashboard.validate')}}" method="post"> -->
                        @csrf

                        <div class="form-group">
                        <label for="name">enter activation code that we sent after buying package to the email <br>
                                used in registration to activate your account</label>
                            <input type="text" id="name" name="code" placeholder="Enter code" required>
                        </div>
                        <center><button class="btn btn-primary">Submit</button></center>
                    </form>
                </div>

            <style>
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f2f2f2;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            form{
                margin-top: 30px;
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
            input[type="file"] {
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            input[type="submit"] {
        /*        width: 100%;*/
                padding: 10px;
        /*        background-color: #4CAF50;*/
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
                    padding: 10px;
                }

                input[type="submit"] {
                    font-size: 14px;
                }
            }

            .alert {
                background-color: red;
            }
            </style>
    </div>

</div>

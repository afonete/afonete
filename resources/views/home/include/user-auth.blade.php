
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
     <meta name="description" content="{{ strtoupper(env('APP_NAME')) }} Affiliate">
    <meta name="keywords" content="{{ strtoupper(env('APP_NAME')) }}, earn money, advertise, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ strtoupper(env('APP_NAME')) }} Affiliate || Earn money infinetely</title>
    <link rel="icon" href="{{asset('assets/front/img/big-logo.png')}}">
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('assets/front/css/jquery-ui.min.css')}}" type="text/css">
   
    <link rel="stylesheet" href="{{asset('assets/front/css/homePage.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/mine.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/css/font-awesome.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/front/css/includes.css')}}">
          <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>
    
    <!-- Offcanvas Menu Section Begin -->
    
    <div class="offcanvas-menu-overlay"></div>
   
    <div class="offcanvas-menu-wrapper">
        <div class="side-top">
            <div class="side-logo">
                <a href="/" style="color: white">
                    <img src="{{asset('assets/front/img/big-logo.png')}}" alt="">
                    {{ strtoupper(env('APP_NAME')) }}
                </a>
            </div>

            <div class="canvas-close">
                <i class="icon_close"></i>
            </div>
        </div>
      
        <div class="header-configure-area">
            <div class="language-option">
                <img src="{{asset('assets/front/img/flag.jpg')}}" alt="">
                <span>ENG <i class="fa fa-angle-down"></i></span>
                <div class="flag-dropdown">
                   <ul>
                        <ul>
                            <li><a href="#">Eng</a></li>
                            <li><a href="#">French</a></li>
                        </ul>   
                    </ul>

                </div>
            </div>
        </div>
        <nav class="mainmenu mobile-menu ">
            <ul>
                <li><a href="{{route('front')}}" class="bttn">Home</a></li>
                <li><a href="{{route('about')}}" class="bttn">About Us</a></li>
                <li><a href="{{route('project')}}" class="bttn">Project</a></li>
                <li><a href="{{route('team.leader')}}" class="bttn">Leader</a></li>
                <li><a href="{{route('login')}}" class="bttn">Login</a></li>
                <li><a href="{{route('register')}}" class="bttn">Register</a></li>
                <li><a href="{{route('contact')}}" class="bttn">Contact Us</a></li>
            </ul>
        </nav>

        <div id="mobile-menu-wrap"></div>
        <div class="footer-mobile container-fluid">

            <div class="top-social">
            <a href="https://web.facebook.com/profile.php?id=61567155638470" target="__blank"><i class="fa fa-facebook"></i></a>
        <a href="https://X.com/bifonex" target="__blank"><i class="fa fa-twitter"></i></a>
        <a href="https://t.me/+l5gAwvltn1AwMjI0" target="__blank"><i class="fa fa-telegram"></i></a>
        <!-- <a href="https://wa.me/+1234567890" target="__blank"><i class="fa fa-whatsapp"></i></a> -->
        <a href="https://discord.gg/ECqJ75ck" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" height="1.7em" viewBox="0 0 640 512"><path d="M524.531,69.836a1.5,1.5,0,0,0-.764-.7A485.065,485.065,0,0,0,404.081,32.03a1.816,1.816,0,0,0-1.923.91,337.461,337.461,0,0,0-14.9,30.6,447.848,447.848,0,0,0-134.426,0,309.541,309.541,0,0,0-15.135-30.6,1.89,1.89,0,0,0-1.924-.91A483.689,483.689,0,0,0,116.085,69.137a1.712,1.712,0,0,0-.788.676C39.068,183.651,18.186,294.69,28.43,404.354a2.016,2.016,0,0,0,.765,1.375A487.666,487.666,0,0,0,176.02,479.918a1.9,1.9,0,0,0,2.063-.676A348.2,348.2,0,0,0,208.12,430.4a1.86,1.86,0,0,0-1.019-2.588,321.173,321.173,0,0,1-45.868-21.853,1.885,1.885,0,0,1-.185-3.126c3.082-2.309,6.166-4.711,9.109-7.137a1.819,1.819,0,0,1,1.9-.256c96.229,43.917,200.41,43.917,295.5,0a1.812,1.812,0,0,1,1.924.233c2.944,2.426,6.027,4.851,9.132,7.16a1.884,1.884,0,0,1-.162,3.126,301.407,301.407,0,0,1-45.89,21.83,1.875,1.875,0,0,0-1,2.611,391.055,391.055,0,0,0,30.014,48.815,1.864,1.864,0,0,0,2.063.7A486.048,486.048,0,0,0,610.7,405.729a1.882,1.882,0,0,0,.765-1.352C623.729,277.594,590.933,167.465,524.531,69.836ZM222.491,337.58c-28.972,0-52.844-26.587-52.844-59.239S193.056,219.1,222.491,219.1c29.665,0,53.306,26.82,52.843,59.239C275.334,310.993,251.924,337.58,222.491,337.58Zm195.38,0c-28.971,0-52.843-26.587-52.843-59.239S388.437,219.1,417.871,219.1c29.667,0,53.307,26.82,52.844,59.239C470.715,310.993,447.538,337.58,417.871,337.58Z" style="fill: white;"/></svg></a>
   
            </div>

            <ul class="top-widget">
                <!--<li style="color: white"><i class="fa fa-phone"></i> +(250) 783524980</li>-->
                <li style="color: white"><i class="fa fa-envelope"></i><a href="info@bifonex.com" target="__blank">info@bifonex.com</a></li>
            </ul>
        </div>
    </div>

    
    <!-- Offcanvas Menu Section End -->

    

    <!-- Header Section Begin for largescreen -->

    <header class="header-section">
        <div class="top-nav">
            <div class="container">
                <div class="row">
                    <!-- web information part -->
                    
                    <div class="col-lg-6">
                        <ul class="tn-left">
                            <!--<li><i class="fa fa-phone"></i>+(250) 783524980</li>-->
                            <li><i class="fa fa-envelope"></i><a href="info@bifonex.com" target="__blank" style="color: white">info@bifonex.com</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="tn-right">
                            <!-- social media icon part -->

                            <div class="top-social mt-1">
                                 <div class="top-social">
                                 <a href="https://web.facebook.com/profile.php?id=61567155638470" target="__blank"><i class="fa fa-facebook"></i></a>
        <a href="https://X.com/bifonex" target="__blank"><i class="fa fa-twitter"></i></a>
        <a href="https://t.me/+l5gAwvltn1AwMjI0" target="__blank"><i class="fa fa-telegram"></i></a>
        <!-- <a href="https://wa.me/+1234567890" target="__blank"><i class="fa fa-whatsapp"></i></a> -->
        <a href="https://discord.gg/ECqJ75ck" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" height="1.7em" viewBox="0 0 640 512"><path d="M524.531,69.836a1.5,1.5,0,0,0-.764-.7A485.065,485.065,0,0,0,404.081,32.03a1.816,1.816,0,0,0-1.923.91,337.461,337.461,0,0,0-14.9,30.6,447.848,447.848,0,0,0-134.426,0,309.541,309.541,0,0,0-15.135-30.6,1.89,1.89,0,0,0-1.924-.91A483.689,483.689,0,0,0,116.085,69.137a1.712,1.712,0,0,0-.788.676C39.068,183.651,18.186,294.69,28.43,404.354a2.016,2.016,0,0,0,.765,1.375A487.666,487.666,0,0,0,176.02,479.918a1.9,1.9,0,0,0,2.063-.676A348.2,348.2,0,0,0,208.12,430.4a1.86,1.86,0,0,0-1.019-2.588,321.173,321.173,0,0,1-45.868-21.853,1.885,1.885,0,0,1-.185-3.126c3.082-2.309,6.166-4.711,9.109-7.137a1.819,1.819,0,0,1,1.9-.256c96.229,43.917,200.41,43.917,295.5,0a1.812,1.812,0,0,1,1.924.233c2.944,2.426,6.027,4.851,9.132,7.16a1.884,1.884,0,0,1-.162,3.126,301.407,301.407,0,0,1-45.89,21.83,1.875,1.875,0,0,0-1,2.611,391.055,391.055,0,0,0,30.014,48.815,1.864,1.864,0,0,0,2.063.7A486.048,486.048,0,0,0,610.7,405.729a1.882,1.882,0,0,0,.765-1.352C623.729,277.594,590.933,167.465,524.531,69.836ZM222.491,337.58c-28.972,0-52.844-26.587-52.844-59.239S193.056,219.1,222.491,219.1c29.665,0,53.306,26.82,52.843,59.239C275.334,310.993,251.924,337.58,222.491,337.58Zm195.38,0c-28.971,0-52.843-26.587-52.843-59.239S388.437,219.1,417.871,219.1c29.667,0,53.307,26.82,52.844,59.239C470.715,310.993,447.538,337.58,417.871,337.58Z" style="fill: white;"/></svg></a>
   
                                </div>
                            </div>

                            <!-- language part -->
                            
                            <div class="language-option">
                                    <img src="{{asset('assets/front/img/flag.jpg')}}" >
                                    <span>ENG <i class="fa fa-angle-down"></i></span>
                                    <div class="flag-dropdown">
                                        <ul>
                                           <ul>
                                                <li><a href="#">Eng</a></li>
                                                <li><a href="#">French</a></li>
                                            </ul>
                                        </ul>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- fullscreen part -->

        <div class="menu-item">
            <div class="container">
                <div class="row">
                    
                    <div class="col-lg-2">
                        <div class="head-logo">
                            <a href="/" style="color: white">
                                <img src="{{asset('assets/front/img/big-logo.png')}}" alt="">
                                {{ strtoupper(env('APP_NAME')) }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="nav-menu">
                            <nav class="mainmenu">
                                <ul id="btns">
                                    <li><a href="{{route('front')}}" class="bttn">Home</a></li>
                                    <li><a href="{{route('about')}}" class="bttn">About Us</a></li>
                                    <li><a href="{{route('project')}}" class="bttn">Projects</a></li>
                                    <li><a href="{{route('team.leader')}}" class="bttn">Leader</a></li>
                                    <!-- <li><a href="{{route('project')}}" class="bttn">Leader</a></li> -->
                                    <li><a href="{{route('login')}}" class="bttn">Login</a></li>
                                    <li><a href="{{route('register')}}" class="bttn">Register</a></li>
                                    <li><a href="{{route('contact')}}" class="bttn">Contact Us</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="canvas-open" style="width:50px:height:50px;right:20px;top:30px;border:none" >
            <i class="icon_menu" style="font-size:50px; color:black"></i>
        </div>
        </div>
    </header>
    {{--
        <script>
            // Get the container element
            var btnContainer = document.getElementById("btns");

            // Get all buttons with class="btn" inside the container
            var btns = btnContainer.getElementsByClassName("btn");

            // Loop through the buttons and add the active class to the current/clicked button
            for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function() {
                    var current = document.getElementsByClassName("active");
                    current[0].className = current[0].className.replace(" active", "");
                    this.className += " active";
                });
            }
        </script> --}}

    @yield('content')
</body>

</html>
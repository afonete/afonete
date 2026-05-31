

<div class="wrapper">
    @include('user.user-dashboard-base')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <style>
   body {
     font-family: "Poppins", sans-serif;
     font-weight: 400;
     font-style: normal;
     }

   </style>
    <div class="content-wrapper">
       <div class="container-fluid">
           <div class="row mb-2">
            <div class="col-md-12">

                        {{-- <div class="my-3 " style=" background: linear-gradient(190deg, #2ecd71 60%, #27ae60 40.1%);  "> --}}


                            <div class="my-3 ">

                                    <div class="container ">
                                            <h1 class=" text-center ">VENTURES</h1>
                                <div class="bg-white rounded mb-2" style="padding:10px;">
                                    <h5 class="text-center"> UNIQUE VENTURE PORTFOLIO</h5>

                                    <span style="font-size:13px; color:black;">
                                    You can become a Royal investor (UVP) in our company and earn up to 300% daily for 600 days and your share will be converted to liquid cash for
                                     withdrawal. Also, through our affiliate program our members can earn with COMPANY. Only Royal investors (UVP) get paid daily venture pool of
                                     whatever you invested for 600 working days (Sat & Sun not inclusive) of which daily income of pool capital 80% and 20% gas fees and in pool
                                     capital 20% in cash to an Open Account which you can withdraw from every week on Mondays and 80% in Escrow to trading their buy token and 80%
                                     Trade Account and used to buy more token.
                                    During the pre-launch, you will get the unique opportunity to build a global business in a way that has never been possible before!! As a Royal
                                    investor or a pioneer, you’ll be able to take part of the company’s success and a greater part of all future income. A Royal investor is who
                                     wants to put money in this stage and you don't need to refer anybody for earn. In during this phase prelaunch we are targeting specification
                                      number of people needed on this first phase. Contract 200% If you don’t do anything and recruit anybody you double your money and get more
                                       through every total company’s revenue. This package available only pro launch but you can buy one or more.

                                    </span>


                                    <div class="btn btn-group">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#activationCode"  id="showActivation" >
                                            User Activation Code
                                        </button>
                                        <a href="{{route('user.payment.deposits')}}" class="btn btn-primary mx-2"   >
                                            Deposit & Pay Later
                                        </a>


                                    </div>

                                </div>


                                <div class="">



                                    <div class="row scrolling pb-5  " style="height:300px;">

                                    <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 100 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >0.8%-1.5%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $50-Max$999</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:150%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp1"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (50$ - 999$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="50" max="999" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                            </div>

                                            </div>

                                            </div>
                                    </div>

                                    <!-- second column  -->


                                    <div class="col-md-3 mt-2">
                                            <div class="mycard2 rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 100 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2%-2.5%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $1k-Max$10k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:250%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp2"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (1000$ - 10000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="1000" max="10000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <!-- Third column  -->

                                    <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 100 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2.5%-2.8%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $10 001-Max$25k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:280%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp3"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (10001$ - 25000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="10001" max="25000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <!-- Fourth column -->

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 100 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >3%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $25k-Max$100k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:300%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp4"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (25000$ - 100000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="25000" max="100000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <!-- Secon row -->

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 200 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $2k-Max$19k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:400%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp5"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (2000$ - 19000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="2000" max="19000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard2 rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 200 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2.5%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $20k-Max$45k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:500%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp6"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (20000$ - 45000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="20000" max="45000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>

                                            </div>

                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 200 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >3%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $50k-Max$90k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:600%</span>
                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp7"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (50000$ - 90000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="50000" max="90000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>

                                            </div>

                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 200 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >3.5%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $100k-Max$500k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:700%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp8"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (100000$ - 500000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="100000" max="500000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <!--Third column  -->

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 600 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" > 1.8%-2%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $10k-Max$50kk</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:1200%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp9"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (10000$ - 50000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="10000" max="50000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>

                                            </div>

                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard2 rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 600 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $50 001-Max$200k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:1200%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp10"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (50001$ - 200000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="50001" max="200000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                        <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 600 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >2.5%-2.5%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $200 001-Max$500k</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:1500%</span>
                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp11"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (200001$ - 500000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="200001" max="500000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>


                                    <div class="col-md-3 mt-2">
                                            <div class="mycard rounded border border-dark">
                                                <div class="m-1">
                                                    <p class="text-danger text-center" style="fon-size:10px;">UVP</p>
                                                    <div class="d-flex justify-content-center">
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQIzaXwUkgU_WXIK-KyUumnaWPkhnOz4zA4kg&s" style="width:40px;">
                                                    </div>
                                                </div>
                                                    <div class="text-center" style="background-color:black;">
                                                        <span style="font-size: 11px;  margin-bottom: -15%; " class="text-danger" >VENTURE LIGHT 600 days</span> <br>
                                                        <span style="font-size: 11px;" class="text-white" >3%</span>
                                                    </div>

                                                    <div class="mt-1 px-1 text-center ">

                                            <span  style="font-size: 10px; margin-bottom:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Min $500k-Max$10M</span> <br>
                                            <span  style="font-size: 10px; margin-to:-22px; font-weight:bold;" class=" border-bottom border-2 border-bottom-dashed">Total return:1800%</span>

                                            <div class="my-1 text-center">
                                                <form action="{{route('ventures')}}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <input type="hidden" name="venture" value="uvp12"/>

                                            <input type="number" name="amount_invest" placeholder=" Invest (500000$ - 10000000$)"
                                                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded" min="500000" max="10000000" required/>
                                                    <button class="btn btn-dark  px-2"
                                                    style="font-size: 10px; background-color:black;" type="submit">
                                                        INVEST NOW
                                                    </button>
                                                </form>
                                         </div>
                                            </div>

                                            </div>
                                        </div>

                                            </div>

                                            <!-- Second row -->


                                            <div class="text-center mt-3">
                                                <button class="btn btn-primary">Use Activation code</button>
                                            </div>
                                        </div>

                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
           <div>
        </div>
    </div>




   <script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
   <!-- Bootstrap 4 -->
   <script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
   <script src="{{asset('assets/a/plugins/sparklines/sparkline.js')}}"></script>
   <!-- AdminLTE App -->
   <script src="{{asset('assets/a/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
   <script src="{{asset('assets/a/dist/js/adminlte.min.js')}}"></script>
   <script src="{{asset('assets/a/dist/js/adminlte.js')}}"></script>
   <script src="{{asset('assets/a/plugins/chart.js/Chart.min.js')}}"></script>
   <script src="{{asset('assets/a/dist/js/pages/dashboard2.js')}}"></script>
   <script src="{{asset('assets/a/dist/js/tree.js')}}"></script>
   <script src="{{asset('assets/a/plugins/chart.js/Chart.min.js')}}"></script>
   <script src="{{asset('assets/a/plugins/jquery-knob/jquery.knob.min.js')}}"></script>

   <script src="{{asset('assets/a/dist/js/pages/dashboard.js')}}"></script>
   <script src="{{asset('assets/a/plugins/summernote/summernote-bs4.min.js')}}"></script>
   <script src="{{asset('assets/a/plugins/daterangepicker/daterangepicker.js')}}"></script>
   <script src="{{asset('assets/a/plugins/moment/moment.min.js')}}"></script>
   <script src="{{asset('assets/a/dist/js/pages/dashboard3.js')}}"></script>

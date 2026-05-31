



<div class="wrapper">
     @include('user.user-dashboard-base')
     <title>Overview</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>

</head>
<body>

    <div class="flex-1 p-4">

        <div class="flex flex-col gap-6">

          <!-- Menu Section -->

           <x-navbar/>

          <!-- Stats Overview Section -->
          <div class="w-full sm:w-1/2 mx-auto flex flex-col gap-6">
            <div class="flex flex-col justify-center items-center">
                <img src="{{asset("image/rf5.png")}}" class="w-20"/>
                <p class="py-2 bg-gray-700 text-white px-2 rounded-lg ">You</p>
            </div>
            <div class="grid grid-cols-3">
                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Indirect</h4>

                    <div class="flex flex-col">
                        @foreach ( $indirect as $member)
                        <div class="flex flex-col justify-center items-center">
                            <img src="{{asset('image/rf5.png')}}" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                            <p class="py-2 bg-gray-700 text-white px-2 rounded-lg">{{$member->name}}</p>
                            <p><i class="fa fa-arrow-down"></i></p>
                        </div>
                        @endforeach

                    </div>


                </div>

                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Direct</h4>


                        @foreach ( $direct as $member)
                        <div class="flex flex-col justify-center items-center">
                            <img src="{{asset('image/rf5.png')}}" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                            <p class="py-2 bg-gray-700 text-white px-2 rounded-lg">{{$member->name}}</p>
                            <p><i class="fa fa-arrow-down"></i></p>
                        </div>
                        @endforeach




                </div>


                <div class="flex flex-col justify-center items-center self-start">
                    <h4 class="text-sm sm:text-base md:text-lg lg:text-xl xl:text-2xl 2xl:text-3xl">Referall</h4>

                    @foreach ( $indirect as $member)
                    <div class="flex flex-col justify-center items-center">
                        <img src="{{asset('image/rf5.png')}}" class="w-16 sm:w-20 md:w-24 lg:w-32 xl:w-40 2xl:w-48" alt="Image description">

                        <p class="py-2 bg-gray-700 text-white px-2 rounded-lg">{{$member->name}}</p>
                        <p><i class="fa fa-arrow-down"></i></p>
                    </div>
                    @endforeach


                </div>

            </div>





          </div>


        </div>
      </div>

</body>
</div>
</div>

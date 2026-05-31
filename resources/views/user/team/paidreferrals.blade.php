



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




         </div>

         <!-- Team Members Table -->
         <div class="bg-white shadow-md rounded-lg p-6 overflow-x-auto">
            <header>
                <h4 class="text-gray-600 font-bold  px-2">PAID REFERRALS</h4>
            </header>
           <table class="w-full text-left">
             <thead>
               <tr>
                 <th class="py-2 px-4">Username</th>
                 <th class="py-2 px-4">Activation Date</th>
                 <th class="py-2 px-4">Country</th>
                 <th class="py-2 px-4">Leadership Rank</th>
                 <th class="py-2 px-4">Sponsored By</th>
                 <th class="py-2 px-4">Team</th>
               </tr>
             </thead>
             <tbody>
              @foreach ($members as $member )
              <tr>
                <td class="py-2 px-4"> {{$member->user}} </td>
                <td class="py-2 px-4">
                    {{$member->has_free_package == 'yes'?'Not Acivated Yet':$member->have_activation_code->created_at}}
                   @php

                   @endphp
                </td>
                <td class="py-2 px-4">
                    {{$member->country}}
                </td>
                <td class="py-2 px-4">Soon</td>
                <td class="py-2 px-4">SYSTEM</td>
                <td class="py-2 px-4">{{$member->teamSide->side}}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="4"> comming soon</td>
              </tr>
             </tbody>
           </table>
         </div>


       </div>
     </div>

</body>
</div>
</div>


<?php
// use Illuminate\Support\Facades\Auth;
// $user = Auth::user();
// $name = $user->user ??'';
$name = "Admin";
$adminPendingDeposits  = (int) \App\Models\Deposits::where('status', 'pending')->count();
$adminPendingWeeklyWd  = (int) \App\Models\WeeklyWithdrawal::where('status', 'pending')->count();
$adminPendingTokenWd   = (int) \App\Models\withdrawals::where('status', 'pending')->count();
$adminPendingWithdraws = $adminPendingWeeklyWd + $adminPendingTokenWd;
$adminPendingLeaders   = (int) \App\Models\TeamLeader::where('status', 'pending')->count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="css/index.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <!-- Trix CSS and JS from CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" /> <!-- 'classic' theme -->
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

   <style>
     .trix-editor {
            background-color: white;
            border: 1px solid #e2e8f0; /* Tailwind gray-300 */
            border-radius: 0.375rem; /* Tailwind rounded-md */
            padding: 1rem;
            min-height: 200px;
            margin-top: 10px;
        }

        .form{
           clip-path: polygon(52% 0, 44% 0, 48% 15%);
            background-color: #fde047;
            width: 400px;
        }

  .submenu {
    display: none;
  }

        .poppins-thin {
  font-family: "Poppins", sans-serif;
  font-weight: 100;
  font-style: normal;
  }

  .poppins-extralight {
  font-family: "Poppins", sans-serif;
  font-weight: 200;
  font-style: normal;
  }

  .poppins-light {
  font-family: "Poppins", sans-serif;
  font-weight: 300;
  font-style: normal;
  }

  body {
  font-family: "Poppins", sans-serif;
  font-weight: 400;
  font-style: normal;
  }


  /* new css from css/index */
  * {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
}

body {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  background-color: #f1f5f9;
}

header {
  background-color: #6366f1;
  display: flex;
  justify-content: space-around;
  padding: 1em;
}
header h1 {
  color: #f1f5f9;
}

.initial-supply {
  position: relative;
}
.initial-supply .top {
  background-color: #5b21b6;
  padding: 10px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}
.initial-supply .top h3 {
  color: #f1f5f9;
}
.initial-supply p {
  text-align: center;
  padding: 5px;
  background: #6366f1;
  color: #f1f5f9;
  font-weight: 600;
  width: calc(100% - 20px);
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  grid-gap: 20px;
  padding: 1.5em 1em;
}


.card-header {
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 10px;

}

.card-body {
  font-size: 16px;
}

#charts {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
  grid-gap: 20px;
  padding: 1.5em 1em;
}
#charts [id^=chart_] {
  background-color: white;
  padding: 10px;
  border-radius: 5px;
}

@media screen and (max-width: 768px) {
  .cards-container {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}

ul li a{
    font-size: 14px;
}
/*# sourceMappingURL=index.css.map */

            [x-cloak] {
                display: none;
            }

    </style>
</head>

<body>



<!-- drawer init and show -->
<!-- <div class="text-center">
    <button
      type="button" data-drawer-target="drawer-navigation"
      data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
    Show navigation
    </button>
 </div> -->

 <!-- drawer component -->
 <!-- <div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-navigation-label">
     <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">Menu</h5>
     <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
         <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
         <span class="sr-only">Close menu</span>
     </button>
   <div class="py-4 overflow-y-auto">
       <ul class="space-y-2 font-medium">
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                   <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                   <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                </svg>
                <span class="ms-3">Dashboard</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                   <path d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Kanban</span>
                <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
                <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                   <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Users</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                   <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Products</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Sign In</span>
             </a>
          </li>
          <li>
             <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                   <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                   <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                   <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                </svg>
                <span class="flex-1 ms-3 whitespace-nowrap">Sign Up</span>
             </a>
          </li>
       </ul>
    </div>
 </div> -->






  <!-- admin side bar -->
  <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pb-20  bg-white  transition-transform
  -translate-x-full  border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
  <div class="flex p-4 gap-2 mb-3">
    <div class="bg-purple-500 h-10 w-10 flex items-center justify-center text-lg font-bold text-white rounded-full">
      <p>A</p>
    </div>
    <div>
     <h3 class="font-semibold">Welcome! {{$name}} </h3>
     <p class="uppercase font-bold text-xs text-gray-500">Super Admin</p>
    </div>
 </div>
  <div class="h-full   overflow-y-auto bg-white dark:bg-gray-800">

        <ul class="space-y-2 font-medium px-2">
           <li>
              <a href="{{route('admin.dashboard')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <i class="fa-solid fa-house text-gray-500"></i>
                 <span class="ms-3">Dashboard {{$unfixedClaims}} </span>
              </a>
           </li>
           <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                 <i class="fa-solid fa-user-gear text-gray-500"></i>
                 <span class="flex-1 ms-3 whitespace-nowrap">Enable Free user</span>
                 <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
                    <i class="fa-solid fa-angle-right"></i>
                 </span>
              </a>
              <ul class="submenu ml-2 py-2">
                 <li>
                    <a href="{{ route('admin.enable-free-user.register') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                       <i class="fa-solid fa-arrow-right-to-bracket text-xs mr-2"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap text-sm">ON Register</span>
                    </a>
                 </li>
              </ul>
           </li>
           {{-- <li>
              <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <i class="fa-solid fa-circle-info text-gray-500"></i>
                 <span class="flex-1 ms-3 whitespace-nowrap">Useful Links</span>
                 <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
                    <i class="fa-solid fa-angle-right"></i>
                 </span>
              </a>
              <ul class="submenu ml-2 py-2">
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                    hover:bg-gray-100 dark:hover:bg-gray-700 group">

                       <span class="flex-1 ms-3 whitespace-nowrap text-sm">About</span>

                    </a>
                 </li>
                 <li>
                    <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                    hover:bg-gray-100 dark:hover:bg-gray-700 group">

                       <span class="flex-1 ms-3 whitespace-nowrap text-sm">Investments</span>

                    </a>
                 </li>
                 <li>
                    <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                    hover:bg-gray-100 dark:hover:bg-gray-700 group">

                       <span class="flex-1 ms-3 whitespace-nowrap text-sm">Social Media</span>

                    </a>
                 </li>
              </ul>
           </li> --}}




       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-university"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Investments</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>

        </a>
        <ul class="submenu ml-2 py-2">



            <li>
                <a  href="{{route('admin.adventures')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-box"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Manage UVP</span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.fom-licence-miner.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-licence-miner.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-microchip"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">FOM Licence Miner</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-referral.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-referral.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-network-wired"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">FOM Referral Management</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-incentive.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-incentive.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-trophy"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">FOM Incentive Program</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-redeem.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-redeem.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-exchange-alt"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Volume Point Redemption</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-leaderboard.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-leaderboard.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-ranking-star"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Weekly Leaderboard</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-rank.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-rank.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-medal"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">FOM Ranks</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-royal.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-royal.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-crown"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Royal Leader Bonus</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.fom-residual.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fom-residual.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-sitemap"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Residual Matching Bonus</span>
                </a>
              </li>
               <li>
                <a href="{{route('admin.token-settings')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-coins"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Token Price</span>
                </a>
               </li>
               <li>
                <a  href="{{route('admin.payments')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-credit-card"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Manage Payments</span>
                   @if($adminPendingDeposits > 0)
                       <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingDeposits }} pending deposit(s) awaiting review">
                           {{ $adminPendingDeposits }}
                       </span>
                   @endif
                </a>
                </li>

                <li>
                    <a href="{{route('admin.settings.deposit-wallets')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-wallet"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Deposit Wallets &amp; Accounts</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.settings.withdrawal-settings')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-sliders"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Withdrawal &amp; Deposit Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.affiliate-terms') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.settings.affiliate-terms*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                      <i class="fa-solid fa-file-contract"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Affiliate Terms &amp; Conditions</span>
                    </a>
                </li>


                <li>
                    <a href="{{route('admin.referral-bonuses')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-users"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Referral Bonuses</span>
                    </a>
                </li>
                @php
                    try { $adminPendingRankApps = \App\Models\UserRank::where('status', 'pending')->count(); } catch (\Throwable $e) { $adminPendingRankApps = 0; }
                @endphp
                <li>
                    <a href="{{ route('admin.rank.applications') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.rank.applications','admin.rank.approve') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                      <i class="fa-solid fa-award"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Rank Applications</span>
                       @if($adminPendingRankApps > 0)
                           <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingRankApps }} pending rank application(s)">
                               {{ $adminPendingRankApps }}
                           </span>
                       @endif
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.token-settings')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-coins"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Token Prices</span>
                    </a>
                </li>
               <li>
                <a href="{{route('admin.token-withdrawals')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.token-withdrawals*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-arrow-up-from-bracket"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Token Withdrawals</span>
                   @if($adminPendingTokenWd > 0)
                       <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingTokenWd }} pending token withdrawal(s)">
                           {{ $adminPendingTokenWd }}
                       </span>
                   @endif
                </a>
               </li>
               <li>
                <a href="{{ route('admin.token-savings.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.token-savings*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                  <i class="fa-solid fa-piggy-bank"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Saving Wallet</span>
                </a>
               </li>
                <li>
                    <a href="{{route('admin.withdrawal')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-money-bill-transfer"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Withdrwal</span>
                       @if($adminPendingWithdraws > 0)
                           <span class="inline-flex items-center justify-center px-2 py-0.5 ms-2 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingWithdraws }} pending withdrawal(s) awaiting review">
                               {{ $adminPendingWithdraws }}
                           </span>
                       @endif
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.withdrawal-history')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-history"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Withdrawal History</span>
                    </a>
                </li>
                <li>
                    <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-credit-card"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Upgrade</span>
                    </a>
                </li>
                <li>
                    <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-credit-card"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Staking Package</span>
                    </a>
                </li>


        </ul>
       </li>


       {{-- ═══ FC PACKAGES (top-level, collapsible, sits right below Investments) ═══ --}}
       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('fcpackages*','admin.fc-ranks.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
          <i class="fa-solid fa-crown text-yellow-500"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">FC Packages</span>
           <span class="inline-flex items-center justify-center px-2 ms-2 text-xs font-bold text-yellow-700 bg-yellow-100 dark:bg-yellow-900/40 dark:text-yellow-300 rounded-full">FC VIP</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>
        </a>
        <ul class="submenu ml-2 py-2">
          <li>
              <a href="{{ route('fcpackages') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('fcpackages*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                 <i class="fa-solid fa-gem ml-1 mr-2 text-yellow-500"></i>
                 <span class="flex-1 ms-1 whitespace-nowrap text-sm">FC VIP Packages</span>
              </a>
          </li>
          <li>
              <a href="{{ route('admin.fc-ranks.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.fc-ranks.*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                 <i class="fa-solid fa-medal ml-1 mr-2 text-yellow-500"></i>
                 <span class="flex-1 ms-1 whitespace-nowrap text-sm">FC Streamline Ranks</span>
              </a>
          </li>
        </ul>
       </li>


       {{-- ═══ KYC Verifications ═══ --}}
       @php
           $adminPendingKycCount = \App\Models\KycVerification::where('status', 'pending')
               ->orWhere('level_1_status', 'pending')
               ->orWhere('level_2_status', 'pending')
               ->orWhere('level_3_status', 'pending')
               ->count();
       @endphp
       <li>
        <a href="{{ route('admin.kyc.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.kyc.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
          <i class="fa-solid fa-id-card text-indigo-500"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">KYC Verifications</span>
           @if($adminPendingKycCount > 0)
               <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingKycCount }} pending KYC level review(s)">
                   {{ $adminPendingKycCount }}
               </span>
           @endif
        </a>
       </li>

       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-file-signature"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Bifonex contract</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
              <i class="fa-solid fa-angle-right"></i>
           </span>
        </a>
        <ul class="submenu ml-2 py-2">
           <li>
              <a href="{{ route('admin.contracts.index') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                 <i class="fa-solid fa-file-contract text-xs mr-2"></i>
                 <span class="flex-1 ms-3 whitespace-nowrap text-sm">Client Contract</span>
              </a>
           </li>
           <li>
              <a href="{{ route('admin.settings.contract-template') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.settings.contract-template*') ? 'bg-gray-100 dark:bg-gray-700 font-bold' : '' }}">
                 <i class="fa-solid fa-file-signature text-xs mr-2"></i>
                 <span class="flex-1 ms-3 whitespace-nowrap text-sm">User Contract Template</span>
              </a>
           </li>
        </ul>
       </li>

       <li>
        <a href="{{route('admin.claims')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white
         hover:bg-gray-100 dark:hover:bg-gray-700 group relative">
          <i class="fa-solid fa-bell"></i>
           <div class="relative">
               <span class="flex-1 ms-3 whitespace-nowrap">Claims</span>
               @if($unfixedClaims > 0)
               <span class=" bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{$unfixedClaims}}</span>
               @endif
           </div>
           <!-- <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span> -->

        </a>
        
       </li>




       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-credit-card"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Payments</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>

        </a>
        <ul class="submenu ml-2 py-2">
          <li>
              <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
              hover:bg-gray-100 dark:hover:bg-gray-700 group">

              <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
                <i class="fa-solid fa-money-check"></i>
              </span>
             </span>
                 <span class="flex-1 ms-3 whitespace-nowrap text-sm">Credit Card</span>

              </a>
           </li>

           <li>
            <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
            hover:bg-gray-100 dark:hover:bg-gray-700 group">

            <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
              <i class="fa-solid fa-money-check"></i>
           </span>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm">Debit Card</span>

            </a>
           </li>
           <li>
            <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
            hover:bg-gray-100 dark:hover:bg-gray-700 group">

            <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
              <i class="fa-solid fa-money-check"></i>
           </span>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm">Visa Card</span>

            </a>
           </li>
           <li>
            <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
            hover:bg-gray-100 dark:hover:bg-gray-700 group">

            <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
              <i class="fa-solid fa-money-check"></i>
           </span>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm">E-wallet</span>

            </a>
            </li>



        </ul>
       </li>


     <li>
      <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
        <i class="fa-solid fa-users"></i>
         <span class="flex-1 ms-3 whitespace-nowrap">Members</span>
         <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
          <i class="fa-solid fa-angle-right"></i>
       </span>
      </a>
      <ul class="submenu ml-2 py-2">
        <li>
            <a href="/admin/users/list" class="flex items-center p-2 text-gray-800 dark:text-white
            hover:bg-gray-100 dark:hover:bg-gray-700 group">

            <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
              <i class="fa-solid fa-list-check"></i>
            </span>
           </span>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm">Users List</span>

            </a>
         </li>

         <li>
          <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
          hover:bg-gray-100 dark:hover:bg-gray-700 group">

          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-list-check"></i>
         </span>
             <span class="flex-1 ms-3 whitespace-nowrap text-sm">Users Groups</span>

          </a>
         </li>
         <li>
          <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
          hover:bg-gray-100 dark:hover:bg-gray-700 group">

          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-list-check"></i>
         </span>
             <span class="flex-1 ms-3 whitespace-nowrap text-sm">Referring Tree</span>

          </a>
         </li>
         <li>
          <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
          hover:bg-gray-100 dark:hover:bg-gray-700 group">

          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-list-check"></i>
         </span>
             <span class="flex-1 ms-3 whitespace-nowrap text-sm">Mail Sender</span>

          </a>
          </li>

          <li>
            <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
            hover:bg-gray-100 dark:hover:bg-gray-700 group">

            <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
              <i class="fa-solid fa-list-check"></i>
           </span>
               <span class="flex-1 ms-3 whitespace-nowrap text-sm">Manage Admin</span>

            </a>
         </li>

      </ul>
   </li>


   <li>
    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa-solid fa-users"></i>
       <span class="flex-1 ms-3 whitespace-nowrap">Membership</span>
       <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
        <i class="fa-solid fa-angle-right"></i>
     </span>
    </a>
    <ul class="submenu ml-2 py-2 ">
      <li>
          <a href="/admin/memberships-plan" class="flex items-center p-2 text-gray-800 dark:text-white
          hover:bg-gray-100 dark:hover:bg-gray-700 group">

          <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-list-check"></i>
          </span>
         </span>
             <span class="flex-1 ms-3 whitespace-nowrap text-sm">Membership Plans</span>

          </a>
       </li>

       <li>
        <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
        hover:bg-gray-100 dark:hover:bg-gray-700 group">

        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
          <i class="fa-solid fa-list-check"></i>
       </span>
           <span class="flex-1 ms-3 whitespace-nowrap text-sm">Membership Orders</span>

        </a>
       </li>
       <li>
        <a href="#" class="flex items-center p-2 text-gray-800 dark:text-white
        hover:bg-gray-100 dark:hover:bg-gray-700 group">

        <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
          <i class="fa-solid fa-list-check"></i>
       </span>
           <span class="flex-1 ms-3 whitespace-nowrap text-sm">Membership Settings</span>

        </a>
       </li>




    </ul>
 </li>




    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-wallet"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Wallet</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>

        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-trophy"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Reward</span>
                </a>
            </li>
            <li>
                <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-university"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Loan</span>
                </a>
            </li>
            <li>
                    <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-briefcase"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap">Fomo</span>
                    </a>
            </li>


            <li>
                    <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-briefcase"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap">FC Licence User</span>
                    </a>
            </li>

        </ul>
    </li>

    {{-- <li>
        <a href="{{route("admin.withdrawal")}}" class="flex items-center p-2 text-gray-900 rounded-lg
         dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
            <i class="fa-solid fa-university"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Withdrawal</span>
        </a>
    </li> --}}
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-wallet"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Market Tools</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>

        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a  href="{{route('admin.campains')}}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-trophy"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Campains</span>
                </a>
            </li>
            <li>
                <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-university"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Programs</span>
                </a>
            </li>
            <li>
                    <a  href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <i class="fa-solid fa-briefcase"></i>
                    <span class="flex-1 ms-3 whitespace-nowrap">Categories</span>
                    </a>
            </li>




        </ul>
    </li>

    {{-- ═══ Zoom Meetings ═══ --}}
    @php
        $activeZoomSidebar = \App\Models\ZoomMeeting::activeMeeting();
    @endphp
    <li>
        <a href="{{ route('admin.zoom.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('admin.zoom.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
            <i class="fa-solid fa-video text-rose-500"></i>
            <span class="flex-1 ms-3 whitespace-nowrap">Zoom Meetings</span>
            @if($activeZoomSidebar)
                <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-extrabold text-white bg-emerald-600 rounded-full shadow-sm animate-pulse" title="Zoom Live Now">
                    LIVE
                </span>
            @endif
        </a>
    </li>

    {{-- ═══ Team Leaders ═══ --}}
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-users-cog"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Team Leaders</span>
           @if($adminPendingLeaders > 0)
               <span class="inline-flex items-center justify-center px-2 py-0.5 me-2 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse" title="{{ $adminPendingLeaders }} pending leader application(s) awaiting review">
                   {{ $adminPendingLeaders }}
               </span>
           @endif
           <span class="inline-flex items-center justify-center px-2 ms-1 text-sm font-medium text-gray-800 dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>
        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a href="{{ route('admin.team-leaders.index') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <i class="fa-solid fa-list-check text-xs mr-2"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap text-sm">All Team Leaders</span>
                   @if($adminPendingLeaders > 0)
                       <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-extrabold text-white bg-red-600 rounded-full shadow-sm animate-pulse">
                           {{ $adminPendingLeaders }}
                       </span>
                   @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tm-auto-activations.index') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <i class="fa-solid fa-bolt text-xs mr-2 text-amber-500"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap text-sm">TM Auto Activation code</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.team-leaders.token-releases') }}" class="flex items-center p-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <i class="fa-solid fa-coins text-xs mr-2 text-yellow-500"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap text-sm">Team leader token releases</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ═══ Official Video Promotions & Tutorials ═══ --}}
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-video"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Video Promotions</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>
        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a href="{{ route('admin.leader-videos') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-list"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">All Videos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.leader-videos.upload') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-cloud-upload-alt"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Upload Video</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ═══ Marketing Banners & Creatives ═══ --}}
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-images"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Banners & Creatives</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>
        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a href="{{ route('admin.leader-banners') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-th-large"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">All Banners</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.leader-banners.upload') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-cloud-upload-alt"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Upload Banner</span>
                </a>
            </li>
        </ul>
    </li>

    {{-- ═══ Team Leaders Announcement ═══ --}}
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-bullhorn"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Team Leaders Announcement</span>
           <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800 dark:text-gray-300">
            <i class="fa-solid fa-angle-right"></i>
         </span>
        </a>
        <ul class="submenu ml-2 py-2">
            <li>
                <a href="{{ route('admin.announcements.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-list"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">All Announcements</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.announcements.create') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-cloud-upload-alt"></i>
                   <span class="flex-1 ms-3 whitespace-nowrap">Create Announcement</span>
                </a>
            </li>
        </ul>
    </li>

       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Balance</span>

        </a>
    </li>

       <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Finance</span>

        </a>
    </li>
    <li>
      <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
        <i class="fa-solid fa-table"></i>
         <span class="flex-1 ms-3 whitespace-nowrap">Localstore</span>

      </a>

   </li>

   <li>
    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa-solid fa-table"></i>
       <span class="flex-1 ms-3 whitespace-nowrap">P2P</span>

    </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Saving</span>

        </a>
    </li>
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Merchant List</span>

        </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Marketing</span>

        </a>
    </li>


    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Exchange</span>

        </a>
    </li>
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Admin Agent</span>

        </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Staff</span>

        </a>
    </li>
    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">ICO Setting</span>

        </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Staking</span>

        </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">Management</span>
        </a>
    </li>

    <li>
        <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
          <i class="fa-solid fa-table"></i>
           <span class="flex-1 ms-3 whitespace-nowrap">CMC</span>

        </a>
    </li>




   <li>
    <a href="#" class="flex items-center  p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
      <i class="fa-solid fa-gear"></i>
       <span class="flex-1 ms-3 whitespace-nowrap">Marketing</span>

    </a>

    </li>
           <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-solid fa-person-running"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">Activity</span>
               <span class="inline-flex items-center justify-center px-2 ms-3 text-sm font-medium text-gray-800  dark:text-gray-300">
                  <i class="fa-solid fa-angle-right"></i>
               </span>
            </a>
            <ul class="submenu ml-2 py-2">
              <li>
                  <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                  hover:bg-gray-100 dark:hover:bg-gray-700 group">

                     <span class="flex-1 ms-3 whitespace-nowrap text-sm">Activity 1</span>

                  </a>
               </li>
               <li>
                  <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                  hover:bg-gray-100 dark:hover:bg-gray-700 group">

                     <span class="flex-1 ms-3 whitespace-nowrap text-sm">Activity 2</span>

                  </a>
               </li>
               <li>
                  <a href="#" class="flex items-center p-2 text-gray-400 dark:text-white
                  hover:bg-gray-100 dark:hover:bg-gray-700 group">

                     <span class="flex-1 ms-3 whitespace-nowrap text-sm">Activity 3</span>

                  </a>
               </li>
            </ul>
         </li>


         <!--  -->


         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-solid fa-wallet"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">Wallet</span>

            </a>

         </li>

         <!--  -->

         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
              <i class="fa-solid fa-box"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">MIM</span>

            </a>

         </li>


         <!--  -->


         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                <i class="fa-solid fa-list-check"></i>
               <span class="flex-1 ms-3 whitespace-nowrap">SaaS</span>

            </a>

         </li>

         <li>
            <ul class="bg-indigo-900 p-2">
                <li>
                    <a href="{{route('admin.requested')}}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white
                    hover:bg-gray-800 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-users "></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Requested Users</span>

                    </a>

                 </li>




                 <li>
                    <a href="{{route('admin.contacted')}}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                        <i class="fa-regular fa-rectangle-list "></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Contacted</span>

                    </a>

                 </li>

                 <li>
                    <a href="{{route('admin.ads')}}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-bullhorn"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Manage Ads</span>

                    </a>

                 </li>

                 <li>
                    <a href="{{route('admin.videos')}}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-video "></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Manage Videos</span>

                    </a>

                 </li>

                 <li>
                    <a href="{{ route('admin.clubs') }}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-diagram-project "></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Manage Clubs</span>

                    </a>

                 </li>


                 <li>
                    <a href="{{ route('admin.position') }}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                        <i class="fa-solid fa-ranking-star"></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Positions</span>

                    </a>

                 </li>


                 <li>
                    <a href="{{route('admin.subscribe')}}" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
                      <i class="fa-solid fa-user "></i>
                       <span class="flex-1 ms-3 whitespace-nowrap">Subscribed</span>

                    </a>

                 </li>

                 <li>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center p-2 text-gray-200 rounded-lg dark:text-white hover:bg-gray-800 dark:hover:bg-gray-700 group">
        <i class="fa-solid fa-right-from-bracket text-red-500"></i>
        <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>


            </ul>
         </li>



         <!--  -->
        </ul>
     </div>
  </aside>

  <div class="p-4 pt-0 px-0 sm:ml-64">

    <main>
        <!-- admin navbar -->
        <nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <header class="sticky top-0 z-40">
                <button class="absolute block lg:hidden bg-blue-400 text-white px-3 py-2 mx-2 rounded left-0 z-48" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"  >
                  <i class="fa-solid fa-bars"></i>
                </button>
                <div class="initial-supply hidden lg:block">
                    <div class="top">
                        <h3>Initial Supply</h3>
                    </div>
                    <p>2,400,000</p>
            </div>
              <h1 class="text-xl sm:text-2xl font-bold text-center ml-3">GENERAL ADMIN </h1>
              <div class="initial-supply">
                <div class="top">
                    <h3>Initial Supply</h3>
                </div>
                <p>2,400,000</p>
             </div>


            </header>

        </nav>


@yield('contents')



        <!-- end charts -->
    </main>
  </div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 <!-- <script src="js/app.js"></script> -->
 <script src="{{asset('js/app2.js')}}"></script>
 <script>
      document.addEventListener("DOMContentLoaded", function() {
    const dropdownToggles = document.querySelectorAll(".submenu");

    dropdownToggles.forEach(function(toggle) {
      toggle.previousElementSibling.addEventListener("click", function(e) {
        e.preventDefault();
        const submenu = toggle;
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
        this.querySelector("span i").classList.toggle("fa-angle-right");
        this.querySelector("span i").classList.toggle("fa-angle-down");
      });
    });
  });
 </script>

</body>
</html>

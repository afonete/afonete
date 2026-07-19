



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
</head>

   <!-- Background div with grid -->
   <nav class="bg-blue-800 shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="text-xl text-white font-bold">
                {{ strtoupper(env('APP_NAME')) }}   </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
            <a href="{{route('team.leader')}}" class="block px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white ">LEADER</a>
            <a href="/home/ambassador" class="block px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white ">AMBASSADOR</a>
            <a href="/home/bestreferral" class="block px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white ">BEST REFERRAL</a>
            <a href="{{ route('team-leader.login') }}" class="bg-white hover:bg-slate-100 text-blue-800 font-bold px-4 py-2 rounded-full text-sm" style="text-decoration: none;">Leader Login</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button class="mobile-menu-button">
                    <i class="fas fa-bars text-white text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden pb-4">
            <a href="/" class="block py-2 px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white ">HOME</a>
            <a href="/about" class="block py-2 px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white ">ABOUT</a>
            <a href="/contact" class="block py-2 px-4 text-white text-lg font-medium hover:border-b-2 hover:border-white">CONTACT</a>
            <a href="{{ route('team-leader.login') }}" class="block py-2 px-4 text-yellow-300 text-lg font-bold">LEADER LOGIN</a>
        </div>
    </div>
</nav>


<script>
            // Mobile menu toggle
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
           const mobileMenu = document.querySelector('.mobile-menu');
   
           mobileMenuButton.addEventListener('click', () => {
               mobileMenu.classList.toggle('hidden');
           });
</script>
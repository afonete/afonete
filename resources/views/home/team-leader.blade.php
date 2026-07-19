<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ strtoupper(env('APP_NAME')) }} Become a Team Leader</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
 <style>
    .modal-open {
    overflow: hidden;
}
 </style>
</head>

<body class="bg-gray-50">

@include('home.include.leadersnav')
    <!-- Hero Section -->

   

   


    <section class="bg-cover bg-center min-h-[80vh] flex items-center text-white relative">

  

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl font-bold my-3 text-blue-900">
                {{ strtoupper(env('APP_NAME')) }}
            </h1>

            <h1 class="text-3xl font-bold my-3 text-blue-900">
                BECOME A TEAM LEADER
            </h1>

            <h1 class="text-3xl font-bold my-3 text-blue-900">
                Join a Global Community Driven by Innovation and Opportunity
            </h1>

            <h5 class="text-2xl font-bold my-3 text-blue-900">
                Grow Together. Earn Together.
            </h5>

             <p class="text-gray-950 text-md">
                Join the Bifonex Referral Program and earn rewards for every successful referral. Share innovative opportunities with your network, expand your community, and unlock new ways to grow with the future of digital technology.
            </p>

            <p class="text-gray-950 text-md">
                Bifonex brings together a global community of users passionate about AI, Web3, blockchain technology, and digital innovation. Our platform is designed to help members connect, explore opportunities, and participate in a growing technology-driven ecosystem.
            </p>

            <p class="text-gray-950 text-md">
                Start referring today and turn every connection into an opportunity.
            </p>

               <!-- Button to trigger modal -->
<button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-full mt-6">
    <i class="fas fa-rocket mr-3"></i>Become a Team Leader
</button>
        </div>
    </div>
</section>


<section>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">UP TO</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">10 LEVELS</h1>
        </div>


        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">REWARDS</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">10% - 30%</h1>
        </div>


        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">NO LIMIT</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">COMPLETE DOWNLINE</h1>
        </div>

        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">VALIDITY</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">LIFETIME</h1>
        </div>

    </div>
    </section>



    <!-- about who can be ambassador -->
    <div class="grid  grid-cols-1 md:grid-cols-2 gap-5 mt-10 md:mx-14 mx-5">
    <div>
            <div class="flex justify-center items-center h-full w-full">
                <div class="">
            <h1 class="text-2xl text-blue-950 font-medium my-1">The {{ env('APP_NAME') }} Leader Program  </h1>

            <p class="">
                The Bifonex Team Leader Program is designed for individuals with strong communities and a proven ability to connect and engage people.
                 As a Team Leader, you gain access to exclusive rewards, special benefits, and unique opportunities through a dedicated partnership with {{ env('APP_NAME') }}.
            </p>
            </div>
            </div>   
         </div>

  
            <div class="space-y-4">
    <!-- Section 1 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Choose Your Leadership Level ?</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
        
       <p> 

        <h1 class="text-1xl font-bold my-1 text-blue-900">
                 SUPER LEADER
            </h1>

<p> If you are a Super Leader with a large community of leaders, you can create a unique referral link that enables your community members to register and participate in the Bifonex Referral Program.
The Bifonex Referral Program is designed to provide exceptional earning opportunities. As a Super Leader, you can:</p>

<p> • Earn rewards across unlimited lines, stages, and levels within your referral structure. </p> 
<p> • Receive dedicated support from a personal account manager available 24/7.</p>


<h1 class="text-1xl font-bold my-1 text-blue-900">
                 TEAM LEADER
            </h1>

<p> If you are a Team Leader with a strong community of members and investors, you can benefit from a special partnership program with Bifonex, providing enhanced rewards beyond the standard referral system.
The Bifonex Referral Program offers valuable opportunities to grow your earnings. As a Team Leader, you can:</p> 

 <p> • Earn rewards across unlimited stages and levels within your referral structure.</p>
 <p> • Receive dedicated support from a personal account manager available 24/7. </p>
        
            </p>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Applicant Profile?</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
            <p>
            {{ env('APP_NAME') }} is looking for motivated and community-driven individuals who are passionate about leadership, communication, and platform growth. 
            The Team Leader role is ideal for applicants who are committed to supporting the Bifonex community and helping others engage with the platform.
 A strong applicant should demonstrate:
<p>• Interest in leadership, teamwork, and community building.</p>
<p>• Good communication and interpersonal skills.</p>
<p>• Motivation to support and promote Bifonex.</p>
<p>• An active presence in online communities or social media platforms is an advantage.</p>
<p>• A positive attitude, reliability, and willingness to guide others.</p>

<p>No specific academic or professional background is required, but applicants should show commitment,
 enthusiasm, and a strong desire to contribute to the success of the Bifonex community.</p>
            </p>
        </div>
    </div>

    <!-- Section 3 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Team Leader Requirements and Responsibilities</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
            <p>
<h1 class="text-1xl font-bold my-1 text-blue-900">
                 Primary responsibilities may include:
            </h1>
<p> • Hosting training sessions, webinars, and community meetings. </p>
<p> • Guiding and supporting new and existing members. </p>
<p> • Promoting Bifonex through social media and community channels. </p>
<p> • Sharing educational content, updates, and testimonials. </p>
<P> • Encouraging teamwork, engagement, and community growth.</P>
<p> • Organizing online sessions or local community activities where applicable. </p>

All Team Leaders are expected to act in line with the values, policies, and standards of Bifonex.
<h1 class="text-1xl font-bold my-1 text-blue-900">
                 Team Leader – Tasks and Requirements
            </h1>
<p> • Represent Bifonex professionally and uphold its values. </p>
<p> • Support, guide, and motivate team members. </p>
<p> • Manage Telegram, WhatsApp, and social media community channels. </p>
<p> • Recruit and onboard new users to the platform. </p>
<p> • Host webinars, Zoom sessions, and online training activities. </p>
<p> • Provide social media support and promotional content. </p>
<p> • Meet the minimum requirements for direct referrals and turnover. </p>
<h1 class="text-1xl font-bold my-1 text-blue-900">
                 Super Leader – Tasks and Requirements
            </h1>
<p> • Represent Bifonex professionally and uphold its values. </p>
<p> • Lead, mentor, and support Team Leaders within the community. </p>
<p> • Manage community channels and drive engagement across teams. </p>
<p> • Recruit and onboard new users to the platform. </p>
<p> • Host webinars, Zoom sessions, and leadership training activities. </p>
<p> • Provide social media support and promotional content.</p>
<p> • Meet the minimum requirements for direct referrals and turnover. </p>
<p> • Support leader development and assist with local events and training initiatives. </p>
            </p>
        </div>
    </div>


    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Team Leader Rewards and Benefits</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
           
          <h1 class="text-1xl font-bold my-1 text-blue-900">
                Team Leader Rewards
            </h1>
            <p>As a Team Leader, you may benefit from: </p>
            <p> • An Activation Package valued at $2,000. </p>
            <p> • The opportunity to earn across 15 referral lines. </p>
            <p> • Access to loan opportunities ranging from $1,000 to $50,000. </p>
            <p> • Access to educational courses and learning resources. </p>
            <p> • VIP Dashboard access for advanced account and referral management. </p>
            <p> • Invitations to official Bifonex events and community activities. </p>
            <p> • Free chat support for guidance and assistance. </p>

            <h1 class="text-1xl font-bold my-1 text-blue-900">
               Super Leader Rewards
            </h1>
            <p> As a Super Leader, you may receive expanded rewards and exclusive benefits, including: </p>
            <p> • An Activation Package valued at $2,000. </p>
            <p> • Credit rewards ranging from $1000 to $5,000. </p>
            <p> • Access to loan opportunities ranging from $2,000 to $5,000,000. </p>
            <p> • Support for office tools and operational setup. </p>
            <p> • Access to educational courses and advanced learning opportunities. </p>
            <p> • Opportunities to participate as a public event speaker. </p>
            <p> • Invitations to all official Bifonex events and leadership activities. </p>
            <p> • The opportunity to earn across 15 referral lines. </p>
            <p> • VIP Dashboard access for advanced account and referral management. </p>
            <p> • Free chat support for ongoing assistance. </p>

            Rewards and benefits may vary based on leadership level, performance, and participation within the Bifonex ecosystem.
            
            </p>
        </div>
    </div>
</div>



   </div>


    <!-- Features Section -->
    <section class="py-10 bg-gradient-to-b from-white to-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-3xl shadow-md p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">
                        <div class="p-8 benefit-card rounded-2xl" data-aos="fade-right">
                            <h3 class="text-3xl font-bold mb-4 text-blue-600">Leader Program</h3>
                            <p class="text-gray-700 text-lg">Become part of an exclusive leadership network and gain access to premium benefits, unique opportunities, and greater earning potential.</p>
                        </div>

                        <div class="p-8 benefit-card rounded-2xl" data-aos="fade-left">
                            <h3 class="text-3xl font-bold mb-4 text-blue-600">Referral Bonuses</h3>
                            <p class="text-gray-700 text-lg">Unlock additional rewards by expanding the Bifonex community and turning your connections into long-term earning opportunities.</p>
                        </div>
                    </div>

                 
                    <div class="mb-5">
                        <h3 class="text-3xl font-bold mb-8 text-center gradient-text">Team Leaders Rewards</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="p-8 benefit-card rounded-2xl text-center" data-aos="fade-up">
                                <i class="fas fa-gift text-4xl text-blue-500 mb-4"></i>
                                <h4 class="text-xl font-bold mb-3">General Reward</h4>
                                <p class="text-lg">Premium Activation Code</p>
                            </div>
                            <div class="p-8 benefit-card rounded-2xl text-center" data-aos="fade-up" data-aos-delay="100">
                                <i class="fas fa-dollar-sign text-4xl text-green-500 mb-4"></i>
                                <h4 class="text-xl font-bold mb-3">Daily Earnings</h4>
                                <p class="text-lg">$500 - $5,000</p>
                            </div>
                            <div class="p-8 benefit-card rounded-2xl text-center" data-aos="fade-up" data-aos-delay="200">
                                <i class="fas fa-trophy text-4xl text-yellow-500 mb-4"></i>
                                <h4 class="text-xl font-bold mb-3">Prize Reward</h4>
                                <p class="text-lg">$100,000 in Focoin</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-3xl font-bold mb-8 text-center gradient-text">Exclusive Benefits</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-credit-card text-blue-500 text-xl"></i>
                                    <span class="text-lg">Master Card</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-coins text-yellow-500 text-xl"></i>
                                    <span class="text-lg">Token 60000+ Focoin</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                                    <span class="text-lg">Allowed loan $1000-$5 000 000</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-ad text-purple-500 text-xl"></i>
                                    <span class="text-lg">Get 100,000 ads reward bonus 0.09% each ads</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-level-up-alt text-blue-500 text-xl"></i>
                                    <span class="text-lg">Upgrade Stage2</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-users text-indigo-500 text-xl"></i>
                                    <span class="text-lg">Affiliate 10%</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-sync text-orange-500 text-xl"></i>
                                    <span class="text-lg">Referral Upgrade 10%</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-tachometer-alt text-green-500 text-xl"></i>
                                    <span class="text-lg">Second Dashboard</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                                    <span class="text-lg">Access More Features</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-network-wired text-blue-500 text-xl"></i>
                                    <span class="text-lg">Earn 10 Level Downline</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-bullhorn text-red-500 text-xl"></i>
                                    <span class="text-lg">Direct Ads 25%</span>
                                </div>
                                <div class="flex items-center space-x-3 p-4 benefit-card rounded-xl">
                                    <i class="fas fa-chart-line text-indigo-500 text-xl"></i>
                                    <span class="text-lg">Earn 15 Lines</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-bold py-4 px-12 rounded-full transition duration-300 transform hover:scale-105 shadow-xl" onclick="openModal()">
                            <i class="fas fa-crown mr-2"></i>Claim Your Leadership Position
                        </button>
                        <div class="mt-4">
                            <span class="text-gray-300">Already applied as a Team Leader?</span>
                            <a href="{{ route('team-leader.login') }}" class="text-yellow-400 hover:text-yellow-300 font-bold ml-1 underline" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
                                <i class="fas fa-sign-in-alt mr-1"></i> Sign In Here
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="overflow-x-auto p-4 bg-gray-100 min-h-screen">
  <div class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-md">
    <table class="w-full border-collapse border border-gray-200">
      <thead>
        <tr>
          <th class="border border-gray-200 bg-blue-800 text-white px-4 py-2">Team leader management</th>
          <th class="border border-gray-200 bg-blue-800 text-white px-4 py-2">Team leader</th>
          <th class="border border-gray-200 bg-blue-800 text-white px-4 py-2">Super leader</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Uniqu personal leader referral link</td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
        </tr>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Instant income</td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
        </tr>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Affiliate 10%</td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
        </tr>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Credit cash $500-$5000</td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-red-500">✘</span></td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
        </tr>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Get Token</td>
          <td class="border border-gray-200 px-4 py-2">60 000</td>
          <td class="border border-gray-200 px-4 py-2">100 000</td>
        </tr>
        <tr>
          <td class="border border-gray-200 px-4 py-2">Event speaker</td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-red-500">✘</span></td>
          <td class="border border-gray-200 px-4 py-2"><span class="text-green-500">✔</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>



<!-- Modal -->
<div id="leaderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Team Leader Application</h3>
            <form id="leaderApplicationForm" class="space-y-6 p-6 bg-white rounded-lg shadow-md">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-globe mr-2"></i>Country
                        </label>
                        <select id="countrySelect" name="country" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                            <option value="">Select your country</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-2"></i>Full Name
                        </label>
                        <input type="text" name="name" placeholder="Enter your full name" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-phone mr-2"></i>Phone Number
                        </label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input type="email" name="email" placeholder="Enter your email address" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user-circle mr-2"></i>Username
                        </label>
                        <input type="text" name="username" placeholder="Choose a username" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input type="password" name="password" placeholder="Enter your password" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2"></i>Re-enter Password
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Confirm your password" class="mt-1 block w-full px-4 py-2 rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150" required>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal()" class="px-6 py-2.5 rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition duration-150 font-medium">Close</button>
                    <button type="submit" class="px-6 py-2.5 rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 font-medium">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- sweatalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        fetch("https://restcountries.com/v3.1/all?fields=name,idd")
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById("countrySelect");
                const phoneInput = document.getElementById("phone");
                
                // Exclude Chile and Israel
                const excludedCountries = ['Chile', 'Israel'];
                const filteredData = data.filter(country => !excludedCountries.includes(country.name.common));
                
                filteredData.sort((a, b) => a.name.common.localeCompare(b.name.common));
                
                filteredData.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    countrySelect.appendChild(option);
                });

                let activeCountryCode = "";

                countrySelect.addEventListener('change', function() {
                    const selectedCountry = filteredData.find(country => country.name.common === this.value);
                    activeCountryCode = selectedCountry?.idd?.root + (selectedCountry?.idd?.suffixes?.[0] || '');
                    phoneInput.value = activeCountryCode;
                });

                phoneInput.addEventListener('input', function(e) {
                    if (!activeCountryCode) return;
                    let cursorPosition = this.selectionStart;
                    let inputValue = this.value;
                    
                    if (inputValue.length < activeCountryCode.length) {
                        this.value = activeCountryCode;
                        cursorPosition = activeCountryCode.length;
                    } else {
                        let numbers = inputValue.slice(activeCountryCode.length).replace(/\D/g, '');
                        this.value = activeCountryCode + numbers;
                        cursorPosition = Math.min(cursorPosition, this.value.length);
                    }
                    
                    this.setSelectionRange(cursorPosition, cursorPosition);
                });
            })
            .catch(error => console.error("Error fetching countries:", error));


    //    subbmit application
    document.getElementById('leaderApplicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Submitting Application',
        html: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('/team-leader/apply', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#0084D1'
            }).then(() => {
                // redirect to team-leader/pending-approval
                window.location.href = `/team-leader/pending-approval?username=${formData.get('username')}`;

            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Application Failed',
                text: data.message,
                confirmButtonColor: '#0084D1'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'Please try again later.',
            confirmButtonColor: '#0084D1'
        });
    });
});




// modal
function openModal() {
    document.getElementById('leaderModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('leaderModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('leaderModal');
    if (event.target == modal) {
        closeModal();
    }
}

    </script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const headers = document.querySelectorAll('.collapsible-header');
    
    headers.forEach(header => {
        header.addEventListener('click', () => {
            // Close all other sections first
            headers.forEach(otherHeader => {
                if (otherHeader !== header) {
                    const otherContent = otherHeader.nextElementSibling;
                    const otherIcon = otherHeader.querySelector('i');
                    
                    otherContent.classList.add('hidden');
                    otherIcon.classList.remove('fa-minus', 'rotate-45');
                    otherIcon.classList.add('fa-plus');
                }
            });

            // Toggle clicked section
            const content = header.nextElementSibling;
            const icon = header.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('fa-plus');
            icon.classList.toggle('fa-minus');
            icon.classList.toggle('rotate-180');
        });
    });
});


      
        
</script>


</body>
</html>

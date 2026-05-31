<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strtoupper(env('APP_NAME')) }} Become a Team Leader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid-bg {
            background-image: linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }
    </style>
</head>
<body class="bg-gray-50">

   <!-- include header -->
  @include('home.include.leadersnav')
   

    <!-- Content container -->
    <div class="relative z-10 ">
        <h1 class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400  via-orange-500 to-purple-500  md:text-4xl text-2xl font-bold text-center my-20 ">
            APPLICATION SOCIAL AMBASSADORS <br>
            Become an  {{ env('APP_NAME') }} Social Ambassador
           
        </h1>
        <div>
            <h5 class="text-blue-950 text-2xl text-center font-medium ">World's Best Referral program - For AMBASSADORS!</h5>
        </div>

   <div class="container md:mx-auto mx-5 my-10">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">UP TO</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">3 LEVELS</h1>
        </div>


        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">REWARDS</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">10% - 30%</h1>
        </div>


        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">UP TO</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">3 TIER LEVELS</h1>
        </div>

        <div>
            <p class="text-center text-zinc-900 font-bold text-xl">VALIDITY</p>
            <h1 class="text-center md:text-3xl text-2xl text-yellow-500 font-bold">LIFETIME</h1>
        </div>

    </div>

<!-- about who can be ambassador -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-10 mx-5">
    <div>
            <div class="flex justify-center items-center h-full w-full">
                <div class="">
            <h1 class="text-2xl text-blue-950 font-medium my-1">The {{ env('APP_NAME') }} Ambassador Program  </h1>

            <p class="">
            These active individuals drive adoption by going beyond their duties by making timely, 
            consistent, and useful contributions to the project. These special people are {{ env('APP_NAME') }} ambassadors.
            </p>
            </div>
            </div>    </div>

  
            <div class="space-y-4">
    <!-- Section 1 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">What is {{ env('APP_NAME') }} Ambassador Program</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
            <p>
        {{ env('APP_NAME') }} Ambassadors Program is created for our community members, who are willing to 
        enhance the presence and image of the project on social media. This includes tweeting - commenting 
        - making Telegram groups - making 4chan threads - Reddit threads - memes - logos - YouTube videos - Meetings etc.... <br>
        Ambassadors should constantly be looking for ways to increase engagement on social channels
        Every action an ambassador makes needs to be logged so the team can verify it. All ambassador 
        actions will be assessed on Tier (in range of 1 to 3 Tier, based on interactions, number of tweet
        impressions, likes, etc) and another 3 Levels of quality, feedback, and engagement. The status will
        be assigned by the {{ env('APP_NAME') }} Team every month.
            </p>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Who are {{ env('APP_NAME') }} Ambassadors?</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
            <p>
            {{ env('APP_NAME') }} Ambassadors are passionate members of our community that support the platform in various ways, 
            such as promoting {{ env('APP_NAME') }} on social media and motivating others to engage, providing feedback to improve offerings, 
            and assisting users with questions and concerns and everything in accordance with the specifications and guidelines of {{ env('APP_NAME') }} .
            </p>
        </div>
    </div>

    <!-- Section 3 -->
    <div class="">
        <div class="flex justify-between items-center cursor-pointer collapsible-header">
            <h1 class="text-2xl text-blue-950 font-medium">Profile of Applicants?</h1>
            <i class="fa fa-plus font-bold transform transition-transform duration-200"></i>
        </div>
        <div class="content hidden mt-4">
            <p>
            Anyone who is interested in providing content - no specific background required.
            You provide at least three previously published pieces of content and to apply for an Ambassadorship and qualify for rewards, 
            simply submit published pieces through the Ambassador nomination form for
            the formal review of the type and quality of content and to provide feedback.
            </p>
        </div>
    </div>
</div>



   </div>


   <div class="text-center my-10 mx-5">
    <h1 class="font-bold text-xl text-blue-800">The {{ env('APP_NAME') }} Ambassadors Programs is create for our community members, 
        who are willing to enhance the presence and image of the project on social media </h1>
   </div>

   <!-- tables -->
    <table class="table-auto  md:mx-auto mx-5 my-10">
        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2" ></td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-md " >Ambassador Program</td>
        </tr>
        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Up to 3 Tier Lavels</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Up to 3 Tier Lavels on top</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Instant Icome</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Cumulative Icome Complete Downline</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Rewards 10% - 30%</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Reward for Ambassadors work</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Unique personal Referral Link</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>

        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > WOrldwide avalable</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>
        
        <tr class="">
            <td class="border-b border-r border-blue-950 px-5 py-2 text-md " > Instant Income Rewards paid monthly</td>
            <td class="border-b border-l border-blue-950 px-5 py-2 text-xl text-blue-500 font-bold" > <i class="fa fa-check"></i> </td>
        </tr>
        
       
    </table>

  

                  <div class="text-center">
                        <button class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-bold py-4 px-12 rounded-full transition duration-300 transform hover:scale-105 shadow-xl" onclick="openModal()">
                            <i class="fas fa-crown mr-2"></i>  Become affiliate Now
                        </button>
                    </div>



   </div>


   


    </div>



    <!-- modal -->

<div id="leaderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
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


    <!-- scripts -->
     


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- sweatalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        fetch("https://restcountries.com/v3.1/all?fields=name,idd")
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById("countrySelect");
                const phoneInput = document.getElementById("phone");
                
                data.sort((a, b) => a.name.common.localeCompare(b.name.common));
                
                data.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    countrySelect.appendChild(option);
                });

                countrySelect.addEventListener('change', function() {
                    const selectedCountry = data.find(country => country.name.common === this.value);
                    const countryCode = selectedCountry?.idd?.root + (selectedCountry?.idd?.suffixes?.[0] || '');
                    phoneInput.value = countryCode;
                    
                    phoneInput.addEventListener('input', function(e) {
                        let cursorPosition = this.selectionStart;
                        let inputValue = this.value;
                        
                        if (inputValue.length < countryCode.length) {
                            this.value = countryCode;
                            cursorPosition = countryCode.length;
                        } else {
                            let numbers = inputValue.slice(countryCode.length).replace(/\D/g, '');
                            this.value = countryCode + numbers;
                            cursorPosition = Math.min(cursorPosition, this.value.length);
                        }
                        
                        this.setSelectionRange(cursorPosition, cursorPosition);
                    });
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

// Initialize country select
document.addEventListener('DOMContentLoaded', function() {
    const countries = [
        "United States", "United Kingdom", "Canada", "Australia", 
        // Add more countries as needed
    ];
    
    const select = document.getElementById('countrySelect');
    countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.text = country;
        select.appendChild(option);
    });
});

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

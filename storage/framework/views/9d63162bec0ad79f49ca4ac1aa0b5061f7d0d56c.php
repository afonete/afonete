<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(strtoupper(env('APP_NAME'))); ?> Become a Team Leader</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid-bg {
            background-image: linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }


        .bounce-infinite {
    animation: bounce 1s infinite ease-in-out;
}

@keyframes  bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-25px);
    }
}

    </style>
</head>
<body class="bg-gray-50">
 

<?php echo $__env->make('home.include.leadersnav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Content container -->
    <div class="relative z-10 ">

    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-[50%] my-20 md:mx-10" >
            <h1 class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400  via-orange-500 to-purple-500  md:text-4xl text-2xl font-bold text-center ">
                JOIN THE MOST LUCRATIVE REFERRAL PROGRAM <br>
            </h1> 
            
            <p class="my-3 mx-5 text-center text-gray-500 text-lg italic font-medium">
                Enjoying the best slice on the financial market together with the best referral program in the world.
            </p>

            <div class="mx-5 md:mx-10 mt-10 hidden">
                <button class="border-2 border-blue-900 rounded-full px-10 py-3 text-xl font-bold text-blue-900 shadow shadow-blue-700/50
                 hover:bg-blue-900 hover:text-white transition-all duration-300 transform hover:scale-105 hover:shadow hover:shadow-blue-700/50 active:scale-95">Learn more</button>    
                    
                </div>
          </div>



        <div class="md:w-[50%] md:mx-10 mx-5 flex justify-center">
            <img src="<?php echo e(asset('image/bgmo.png')); ?>" alt="Ambassador" class="h-auto w-96 md:m-20 m-10 bounce-infinite">
        </div>
    
    </div>

    
       

   <div class="container md:mx-auto mx-5 my-10 text-center">


<div class="md:w-[65%] md:mx-auto w-full mx-5 text-center" >
<div>
    <h1 class="text-3xl font-bold my-3 text-blue-900
    " >EARN MORE WHEN YOU HELP OTHERS EARN WITH YOU</h1>
    <p class="text-gray-950 text-md">
    Whether you are an investor or a <?php echo e((env('APP_NAME'))); ?> enthusiast, you can invite people with your unique referral
    link through the <?php echo e((env('APP_NAME'))); ?> Referral Program and earn unlimited commission.
    </p>
</div>

<div>
    <h1 class="text-3xl font-bold my-3 text-blue-900
    " >WHY START WITH  <?php echo e(strtoupper(env('APP_NAME'))); ?>  REFERRAL  PROGRAMS</h1>
    <p class="text-gray-950 text-md">
    <?php echo e((env('APP_NAME'))); ?> Referral Program is not an ordinary program. It allows you to earn in up to 20 Levels within your referral network  Lifetime. 
    The Referral Program helps you, as well as your referrals, earn higher income and add value to your network.
    <br>
    Let's grow the personal crypto community together with the <?php echo e((env('APP_NAME'))); ?> Referral Program!
    </p>
</div>

<div>
    <h1 class="text-3xl font-bold my-3 text-blue-900
    " >WHAT ARE  <?php echo e(strtoupper(env('APP_NAME'))); ?> REFERRALS BONUSES? </h1>
    <p class="text-gray-950 text-md">
    A <?php echo e((env('APP_NAME'))); ?>  bonus is exactly what it sounds like: you get rewarded for referring your friends to <?php echo e((env('APP_NAME'))); ?> site. It's a very easy way
     to add hundreds  thousands  of dollars to your wallet. There's no limit to how many referrals you can introduce to <?php echo e((env('APP_NAME'))); ?>. Earn as much as you want.
    </p>
</div>

<div>
    <h1 class="text-3xl font-bold my-3 text-blue-900
    " > <?php echo e(strtoupper(env('APP_NAME'))); ?> REFERRAL PROGRAM HIGHLIGHTS</h1>
    <p class="text-gray-950 text-md">
        Start working with <?php echo e((env('APP_NAME'))); ?> that can provide everything you need to generate your personal network.
        Enjoying the best slice on the financial market together with the best referral program in the world.

        <br>
        Participating in the world's best referral program means for YOU, earn two kind of passive incomes - for 
        AMBASSADORS grow with our community and for LEADERS a great opportunity
    </p>
</div>

</div>


       <div class="bg-white p-5 shadow rounded-md md:mt-10 md:w-[70%] md:mx-auto mx-5 mt-10">
        <h1 class="text-3xl font-bold my-3 text-blue-900">DISCOVER ALL OPTION </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
           
        <div class="">
        <p class="my-5 text-4xl font-bold text-left mx-5 text-blue-900">
                Earn passive income by activating referrals in your 20 Levels downline with ecah Contract purchases immediately!
            </p>

            <span class="text-gray-500 text-xl italic text-left ">
                No obligation and hurdles !
            </span>
        </div>
        

        <div class="">
            <h1 class="mt-5">KEY INFORMATION</h1>

            <ul class="text-left mx-5 my-5 space-y-2">
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Up to 20 Levels </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Unique personal Referral Link </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Worldwide available </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>24/7 Dail Reward Payout  </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Available in you Wallet after 24 hours  </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>No obligation to attends </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Termination option at any time </li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>No hidden fees or futher costs</li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>Lifetime Rewards</li>
                <li><i class="fas fa-check mr-2 font-medium text-blue-600 "></i>No Limits - Earn as much as you want</li>
            </ul>        </div>

        </div>

       </div>



   <!-- button -->
             <div class="text-center">
                        <button class="bg-gradient-to-r mt-10 from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-bold py-4 px-12 rounded-full transition duration-300 transform hover:scale-105 shadow-xl" onclick="openModal()">
                            <i class="fas fa-crown mr-2"></i>  Become Best referral Now
                        </button>
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
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/home/bestreferral.blade.php ENDPATH**/ ?>
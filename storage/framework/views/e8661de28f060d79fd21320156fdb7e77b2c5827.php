



<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Withdrawal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
   <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1/qrcode.min.js"></script>


<style>
    [x-cloak]{display: none !important;}
</style>
</head>
<body>

    <div class="min-h-screen flex items-center justify-center bg-gray-900 p-4">
        <div class="bg-indigo-900 text-white p-8 rounded-lg shadow-md max-w-md w-full">
            <!-- Title and Subtitle -->
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-yellow-400">Deposit USDT</h2>
                
            </div>

            <!-- QR Code and Details Section -->
            <div class="flex flex-col items-center mt-6">
                <!-- QR Code Placeholder -->
                <div class="bg-white p-4 rounded mb-4">
                    <div id="qrcode"></div>



                </div>

                <!-- Deposit Details -->
                <div class="w-full text-center space-y-2">
                    <div class="text-sm">
                        <strong>Amount:</strong> <span class="text-gray-200">2000 USDT</span>
                    </div>
                    <div class="text-sm">
                        <strong>Network:</strong> <span class="text-gray-200">Tron (TRC20)</span>
                    </div>
                    <div class="text-sm break-all">
                        <strong>Deposit Address:</strong> <span class="text-gray-200">THcxy2DueLKTNZ25yLaK2syv8nDyymByC7</span>
                    </div>
                </div>
            </div>

            <!-- Copy Address Button -->
            <div class="mt-6">
                <button onclick="copyAddress()" class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-2 rounded focus:outline-none focus:border-none">
                    Copy Address
                </button>
            </div>
        </div>
    </div>

    <script>
        function copyAddress() {
                    // Copy the deposit address to clipboard
                    navigator.clipboard.writeText("THcxy2DueLKTNZ25yLaK2syv8nDyymByC7").then(() => {
                        alert("Address copied to clipboard!");
                    });
                }

    new QRCode(document.getElementById("qrcode"), {
        text: "THcxy2DueLKTNZ25yLaK2syv8nDyymByC7",
        width: 150,
        height: 150
    });
    </script>

    <!-- Include Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


</body>
</div>
</div>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/UserReceive.blade.php ENDPATH**/ ?>
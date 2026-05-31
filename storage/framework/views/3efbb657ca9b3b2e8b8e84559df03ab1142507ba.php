<?php
use Illuminate\Support\Facades\Request;

$amount = Request::get("amount");
$category = Request::get("category");
$package = Request::get('package');

// echo $amount;


?>

<div class="container items-center flex flex-col w-full justify-center">

      <!-- deposit here -->

<!-- Modal backdrop -->
<div id="modalBackdrop" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-50 hidden"></div>

<!-- Modal container -->
<div id="myModal" class="bg-gray-100 fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2  rounded-lg p-8 z-50 hidden w-90">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-center uppercase">Thank you for your Deposits </h2>
        <span id="closeModal" class="cursor-pointer rounded bg-red-500 p-2 text-white">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </span>
    </div>
    <div class="">
        <p class="text-sm text-gray-700 py-1">Your deposit Still pending?</p>
        <p class="mb-2 text-center text-sm text-gray-700">If your transaction is not approved, please submit your transaction ID below:</p>
        <form class="flex items-center justify-center">
            <input id="transctionId" autofocus class="border border-gray-300 px-3 py-1 rounded-md mr-2 focus:outline-none"
             type="text" placeholder="Transaction ID" name="transactionId">
            <button onclick="reportDeposit()"
            class="bg-yellow-600 hover:bg-blue-600 text-white font-semibold px-4 py-1 rounded-md focus:outline-none">Send</button>
        </form>
    </div>
</div>
      <div class="lg:col-span-4 sm:col-span-6 flex-col w-full justify-evenly">


        <div class="grid grid-cols-1 shadow text-white py-6 rounded px-3 w-full min-h-32 ">

        <label class="flex justify-between ">Deposit here (Choose payment option)
<span id="openModalButton" class="flex items-center cursor-pointer justify-center bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4 mr-1">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M19 19H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z"/>
    </svg>
    Report Issue
</span>


        </label>

          <div class="flex flex-col gap-2 mt-3">
            <div class="flex gap-12 justify-center">
              <div class="boxes cursor-pointer rounded p-1 borde-2 border-blue-00" onclick="option('bitcoin')">
                  <img src="<?php echo e(asset('assets/image/py.jpeg')); ?>" class="w-24 rounded-sm" />
              </div>
              <!-- <img src="./image/paymtn.png" class="w-24 rounded-sm" /> -->
              <div class="boxes cursor-pointer rounded p-1 borde-2 border-blue-00" onclick="option('Advcash')"> <img src="<?php echo e(asset('assets/image/adv.jpeg')); ?>" class="w-24 rounded-sm" /> </div>
              <div class="boxes cursor-pointer rounded p-1 borde-2 border-blue-00" onclick="option('perfect')"> <img src="<?php echo e(asset('assets/image/p1.jpeg')); ?>" class="w-24 rounded-sm" /></div>

            </div>

            <div class="flex flex-col gap-2 justify-between">
            <div class="flex gap-2">
                <input type="text" id="optionValue" placeholder="payment option" readonly class="py-2 w-full  px-3 rounded text-black" />
                <input type="number" id="ammount" placeholder="Amount in USD or EUR or Crypto" class="py-2 w-full  px-3 rounded text-black" />

            </div>
              <div class="flex flex-end flex-col">
                <input type="button" value="Deposit" id="openPopup"
                 class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-400 cursor-pointer"/>


              </div>

              <div id="popupContainer" class="hidden fixed top-0 left-0 w-full h-full bg-black flex justify-center bg-opacity-90 items-center z-50 overflow-y-auto">

                <div class="bg-gray-600 text-white p-8 rounded-sm">
                  <span id="closePopup" class="absolute cursor-pointer bg-red-700 px-2  rounded  text-xl lg:text-2xl top-2 right-2
                   text-gray-300 hover:text-white focus:outline-none">
                    &times;
                  </span>
<script>
                    const optionValue = document.getElementById('optionValue');

                    function option(option) {
                      if (option == 'bitcoin') {
                        optionValue.value = "usdt, bitcoin, bnb or eth";
                        document.getElementById("payment_option").value="usdt, bitcoin, bnb or eth"
                        document.getElementById("currency_type").value=""
                      } else if (option == 'Advcash') {
                        optionValue.value = "Advcash";
                          document.getElementById("payment_option").value="usdt, bitcoin, bnb or eth"
                        document.getElementById("currency_type").value=""

                      } else {
                        optionValue.value = "Perfect money";
                          document.getElementById("payment_option").value="usdt, bitcoin, bnb or eth"
                        document.getElementById("currency_type").value=""

                      }
                    }

                                    // Get modal elements
                  const modalBackdrop = document.getElementById('modalBackdrop');
                  const modal = document.getElementById('myModal');
                  const closeModalButton = document.getElementById('closeModal');
                  const openModalButton = document.getElementById('openModalButton');

                  // Function to open the modal
                  function openModal() {
                      modalBackdrop.classList.remove('hidden');
                      modal.classList.remove('hidden');
                      // Disable scrolling of background content when modal is open
                      document.body.style.overflow = 'hidden';
                  }

                            // Function to close the modal
                            function closeModal() {
                                modalBackdrop.classList.add('hidden');
                                modal.classList.add('hidden');
                                // Enable scrolling of background content when modal is closed
                                document.body.style.overflow = 'auto';
                            }

                        // Event listener to open the modal when the button is clicked
                        openModalButton.addEventListener('click', openModal);

                        // Event listener to close the modal when the close button is clicked
                        closeModalButton.addEventListener('click', closeModal);

                        // Event listener to close the modal when the modal backdrop is clicked
                        modalBackdrop.addEventListener('click', closeModal);

                        function reportDeposit() {

                                    const transctionId=document.getElementById('transctionId').value;
                                    if (transctionId =="")
                                    {
                                      alert("enter transction Id")


                                    }

                                  else {
                                    // Make a POST request to your PHP script
                                   alert('transction reported well')
                            }
                          }



               </script>
                  <!-- Section 1 -->
                  <section id="section2" class="hidden">

                    <div>

                      <button id="payBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Pay
                      </button>

                  </section>
                  <!-- Section 2   paying info section.   -->
                  <form action="<?php echo e(route('user.payment.savedeposits')); ?>" method="POST" id="section1" class="section">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <input type="hidden" id="amountExpected" name="expectedAmount" />

                    <h1 class="text-xl font-bold mb-4 text-center">
                      Focoin | Activate
                    </h1>
                    <p class="text-center">Pay With <b><label id="showValue"></label></b></p>

                    <div id="perfectMoney">
                      <!-- <p>Pay With <b><label class="showValue"></label></b></p> -->
                      <ul class="list-decimal pl-4 mb-4">
                        <li>Login to your Perfect Money account,</li>
                        <li>Navigate to the "<b>Transfer</b>" option in the main menu,</li>
                        <li>Enter the Perfect Money account number (choose one):
                          <ul style="list-style: upper-alpha;" class="pl-8">
                            <li>USD perfect money account : <b>U16443155</b></li>
                            <li>EUR perfect money account : <b>*Coming soon</b>
                                
                            </li>
                          </ul>
                        </li>
                        <li>Enter selected amount of money, </li>
                        <li>Review and Click on the "Preview" or "Continue" button to proceed </li>

                        <li>Enter a security code or answer a security question to verify your identity.</li>
                        <li>Authorize the payment by clicking on the "Send" or "Confirm" button.</li>
                        <li>Once you receive a confirmation message of payment,<br> Back to dashboard and confirm your perfect money account number</li>

                      </ul>

                    </div>
                    <div id="advCash">
                      <!-- <p>Pay With <b><label class="showValue"></label></b></p> -->
                      <ul class="list-decimal pl-4 mb-4">
                        <li>Login to your Advcash account,</li>
                        <li>Go to the <b>"Send Funds"</b>,</li>
                        <li>Choose wallet Balance and enter amount, </li>
                        <li>Enter the AdvCash account number (choose one):
                          <ul style="list-style: upper-alpha;" class="pl-8">
                            <li>USD Advcash : <b>U 8678 2735 0323</b></li>
                            <li>EUR Advcash : <b>*Coming soon</b>
                                
                            </li>
                          </ul>
                        </li>
                        <li>Review and Click on the "Preview" or "Continue" button to proceed </li>

                        <!-- <li>Enter a security code or answer a security question to verify your identity.</li> -->
                        <li>Authorize the payment by clicking on the "Send" or "Confirm" button.</li>
                        <li>Once you receive a confirmation message of payment,<br> Back to dashboard and confirm your perfect money account number</li>

                      </ul>
                    </div>

                    <div id="bitCoin">

                      <div class="container mx-auto">
                        <div class="mb-4">
                          <p class="text-lg font-semibold">Select Network</p>
                          <select id="networkSelect"
                           class="block text-black w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none
                            focus:ring focus:border-blue-500" name="network">
                            <option selected disabled>Choose network</option>
                            <option value="TQFC8Rct1kJfGLXuvxtNTAgXfP2hR3qque">Tron (TRC20)</option>
                            <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">Ethereum (Erc20)</option>
                            <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">BNB Smart Chain (BEP20)</option>
                            <option value="">BTC network Bitcoin</option>
                            <option value="1C5p9hrp4PSowLcjZn9eV7eAHWFevhJJiD">BNBnetwork</option>
                            <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">Ethereum Network</option>
                          </select>
                        </div>
                        <div class="mb-4">
                          <div id="qrContainer" class="flex justify-center items-center">
                            <!-- QR code will be generated here -->
                          </div>
                        </div>
                        <div class="mb-4" id="copy-content">
                          <p class="text-lg font-semibold">Address</p>
                          <div class="flex justify-between bg-white items-center rounded overflow-hidden">
                            <input id="addressInput"
                             readonly type="text"
                             class="w-full outline-none p-2 rounded bg-white text-xs text-black" name="address"/>
                            <button id="copyButton" type="button" class="bg-green-400 h-full px-4 py-1">
                              <span class="material-symbols-outlined">
                                content_copy
                              </span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <ul class="list-decimal pl-4 mb-4">
                        <li>Login to your Wallet,</li>
                        <li>Click on "<b>Send</b>" option in the main menu,</li>
                        <li>Enter Network address selected
                        </li>
                        <li>Enter selected amount of money, in any currency</li>
                        <li>Confirm Payment</li>
                        <li>Once you receive a confirmation message of payment,<br> Back to dashboard and confirm your transaction</li>

                      </ul>
                      </div>
                      <div class="mb-2 px-2 bg-green-500" id="successBlock">

                    <p class="text-sm">
                        Your deposit has been successfully recorded. Please allow up to 5 minutes for approval. <br>
                        If the approval process exceeds this timeframe, kindly use the deposit issue button to  <br> report
                        any issues encountered.
                    </p>


                                      </div>
                      <input type="text" id="paymentAccount"
                       placeholder="Enter wallet address used to send money"
                        class="border border-gray-300 p-1 w-full mx-2 px-3 rounded text-black" name="paymentaccount"/>
                      <!-- <input  type="text"                                                                      class="border border-gray-300 rounded p-1 w-full" placeholder="" /> -->

                      <input type="hidden" name="amount" id="amount" value="<?php echo e($amount); ?>"></label>
                        <input type='hidden' name='category' value='<?php echo e($category); ?>'>
                        <input type='hidden' name='package'  value='<?php echo e($package); ?>'>
                        <input type="hidden" name="payment_option" id="payment_option"/>
                        <input type="hidden" name="currency_type" id="currency_type"/>


                        <button type="submit"  class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mt-4 align-end">
                          Confirm your payment
                        </button>
                        <!-- <p class="mt-2">Whatsapp Customer care</p> -->


                  </section>
                </div>
                <!-- </div> -->
              </div>
            </div>
          </div>
        </div>

      </div>


      <!-- enddeposit -->

      </div>
    </div>
  </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
</head>
<script>
  // Function to generate QR code based on selected network
  const generateQR = (network) => {
    const address = getAddressForNetwork(network);
    $('#qrContainer').empty();
    $('#qrContainer').qrcode({
      render: 'canvas',
      text: address,
      width: 128,
      height: 128
    });
  }

  const getAddressForNetwork = (network) => {
    // Replace this with your logic to get the address for the selected network
    // For demonstration, returning a dummy address
    return `${network}`;
  }

  $('#networkSelect').change(() => {
    const selectedNetwork = $('#networkSelect').val();
    //   document.getElementById("payment_option").value="usdt, bitcoin, bnb or eth"
        document.getElementById("currency_type").value=selectedNetwork

    generateQR(selectedNetwork);
    // You can also use the following code to update the address input field based on the selected network
    const address = getAddressForNetwork(selectedNetwork);
    $('#copy-content').css('display', 'block')
    $('#addressInput').val(address);
  });

  // Event listener for copy button
  $('#copyButton').click(function() {
    const addressInput = $('#addressInput');
    addressInput.select();
    document.execCommand('copy');
    // You can provide visual feedback to the user that the address has been copied, if needed
    alert('Address copied to clipboard!');
  });




  document.addEventListener("DOMContentLoaded",function(){

    // Section 1 button click event
    document.getElementById('payBtn').addEventListener('click', function() {
    // Hide section 1, show section 2
    document.getElementById('section1').classList.add('hidden');
    document.getElementById('section2').classList.remove('hidden');
    });

        // Get elements
        const openPopupBtn = document.getElementById('openPopup');
        const closePopupBtn = document.getElementById('closePopup');
        const popupContainer = document.getElementById('popupContainer');
        console.log(openPopupBtn)
        // Event listener to open popup
        document.getElementById('ammount').addEventListener('input',function(){

            document.getElementById('amountExpected').value = this.value;

        })
        openPopupBtn.addEventListener('click', () => {
            // alert("sdfds")
            const valueChoosed= document.getElementById('optionValue').value;
            const ammount= document.getElementById('ammount').value;

            document.getElementById('amountExpected').value = ammount;

            // const userId= document.getElementById('id').innerHTML;
            const userId= 1;
            const perfectBlock= document.getElementById('perfectMoney');
            const advBlock= document.getElementById('advCash');
            const coinBlock= document.getElementById('bitCoin');
            const successBlock= document.getElementById('successBlock');
            successBlock.style.display="none";
            function showPerfectBlock()
            {
                perfectBlock.style.display="block";
                advBlock.style.display="none";
                coinBlock.style.display="none";
            }
            function showAdvBlock()
            {
                advBlock.style.display="block";
                perfectBlock.style.display="none";
                coinBlock.style.display="none";
            }
            function showCoinBlock()
            {
                coinBlock.style.display="block";
                perfectBlock.style.display="none";
                advBlock.style.display="none";
            }

        if (valueChoosed !=='')
        {
        if (ammount !=='')
        {

            if (valueChoosed == 'Advcash') {
                showAdvBlock();

            }
        else if (valueChoosed == 'Perfect money') {
                showPerfectBlock();

                }

                    else {
                showCoinBlock();
                }

            popupContainer.classList.remove('hidden');
            document.getElementById('showValue').innerHTML=valueChoosed;
        }
        else {
            alert('enter amount to deposit');
        }
        }
            else{
                alert('choose payement option');
            }
            // Section 2 button click event
            document.getElementById('confirmPayment').addEventListener('click', function() {
                const payementAccount=document.getElementById('paymentAccount').value;
                if (payementAccount ==='') {
                    alert('enter payment account used to continue');
                    return;
                }
            // save transaction
            successBlock.style.display="block";

            });
            });

            // Event listener to close popup
            closePopupBtn.addEventListener('click', () => {
            popupContainer.classList.add('hidden');
            //   alert("fhh")

            });
  })


</script>


<?php /**PATH /home/blackjay/Downloads/test.focoin.eu/resources/views/user/deposit-options.blade.php ENDPATH**/ ?>
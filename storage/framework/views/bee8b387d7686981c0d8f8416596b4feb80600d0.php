<!-- 
    <div x-data="{ open: false }">
        <button @click="open = true" class="px-4 py-2 bg-blue-600 text-white rounded">Open Modal</button>

        <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div @click.outside="open = false" class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold">Modal Title</h2>
                <p class="mt-2 text-gray-600">This is a simple Alpine.js modal.</p>
                <button @click="open = false" class="mt-4 px-4 py-2 bg-red-600 text-white rounded">Close</button>
            </div>
        </div>
    </div>
 -->


    <div x-data="Deposit()" class="flex flex-col justify-center items-center gap-3">
    <div class="flex space-x-4 py-3">
            <span @click="toggleMethod('Bitcoin')" 
                :class="paymentMethod === 'Bitcoin' ? 'border-4 border-indigo-500' : ''" 
                class="text-white rounded transition relative">
                   <i x-show="paymentMethod === 'Bitcoin'" class="las la-check bg-purple-500 rounded-full p-1 left-0 top-2 absolute border"></i>
                    <img src="<?php echo e(asset('assets/image/py.jpeg')); ?>" class="w-24 rounded-sm" />
              </span>
            <span @click="toggleMethod('Advcash')" 
                :class="paymentMethod === 'Advcash' ? 'border-4 border-indigo-500' : ''" 
                class="text-white rounded transition relative">
                <i x-show="paymentMethod === 'Advcash'" class="las la-check bg-purple-500 rounded-full p-1 left-0 top-2 absolute border"></i>
                <img src="<?php echo e(asset('assets/image/adv.jpeg')); ?>" class="w-24 rounded-sm" /> 
            </span>
            <span @click="toggleMethod('Perfect Money')" 
                :class="paymentMethod === 'Perfect Money' ? 'border-4 border-indigo-500' : ''" 
                class="text-white rounded transition relative">
                <i x-show="paymentMethod === 'Perfect Money'" class="las la-check bg-purple-500 rounded-full p-1 left-0 top-2 absolute border"></i>
                <img src="<?php echo e(asset('assets/image/p1.jpeg')); ?>" class="w-24 rounded-sm" />
            </span>
        </div>

        <!-- Payment Form -->
        <form class="bg-white p-6 rounded-lg shadow-lg w-96 space-y-4 mx-auto" @submit.prevent="continueNext">
            <template x-if="paymentMethod !== 'Bitcoin'">
                <label class="block">
                    <span class="text-gray-700" x-text="paymentMethod || 'Payment Method'"></span>
                    <input type="text" x-model="paymentMethod" readonly class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </label>
            </template>

            <template x-if="paymentMethod === 'Bitcoin'">
                <label class="block">
                    <span class="text-gray-700">Crypto Type</span>
                    <select x-model="cryptoType" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Select Crypto</option>
                        <option value="USDT">USDT</option>
                        <option value="BITCOIN">BITCOIN</option>
                        <option value="BNB">BNB</option>
                        <option value="ETH">ETH</option>
                    </select>
                </label>
            </template>

            <label class="block">
                <span class="text-gray-700">Amount</span>
                <input type="number" step="0.5"  x-model="amount" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Enter amount">
            </label>
            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded">Continue</button>
        </form>

        <form action="<?php echo e(route('user.payment.savedeposits')); ?>" method="post" action="<?php echo e(route('user.payment.savedeposits')); ?>" x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40" 
         id="form">
          <?php echo csrf_field(); ?>
          <?php echo method_field('POST'); ?>
            <div class="bg-white px-3 py-3 w-2xl rounded">
                      <!-- Bitcoin Modal -->
           
                <div x-cloak x-cloak x-show="showBitcoin"  class="bg-white p-6 rounded-lg w-xl">
                    
                    <div class="container mx-auto">
                      <div class="">
                        <p class="text-md font-semibold text-gray-500">Select Network</p>
                        <select id="networkSelect"
                        class="block text-black w-full mt-1 p-2 border border-gray-300 rounded-md focus:outline-none
                          focus:ring focus:border-blue-500 text-sm" name="network" x-model="selectedAddress" @change="generateQR" :required="showBitcoin">
                          <option value="" selected disabled>Choose network</option>
                          <option value="TQFC8Rct1kJfGLXuvxtNTAgXfP2hR3qque">Tron (TRC20)</option>
                          <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">Ethereum (Erc20)</option>
                          <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">BNB Smart Chain (BEP20)</option>
                          <option value="unknown">BTC network Bitcoin</option>
                          <option value="1C5p9hrp4PSowLcjZn9eV7eAHWFevhJJiD">BNBnetwork</option>
                          <option value="0x620ff5ddd33d48fcc77565251cb722e6891d92db">Ethereum Network</option>
                        </select>
                      </div>
                      <div class="">
                        <div id="qrContainer" class="flex justify-center items-center">
                            
                        </div>
                      </div>
                      <div class=" relative" id="copy-content">
                      <div id="copyMessage" class="hidden  bg-gray-700  text-gray-400 absolute mt-2 right-2 -top-6 rounded-xl text-sm p-2 ">Copied!</div>
                        <p class="text-sm py-2 text-gray-600 font-semibold">Address</p>
                        <div class="flex justify-between bg-white items-center rounded overflow-hidden gap-2">
                          <input 
                          id="addressInput"
                          :value="selectedAddress"
                          readonly 
                          type="text"
                          class="w-full outline-none p-2 rounded bg-white text-xs text-black bg-gray-100" 
                          placeholder="Selected address..."
                          name="address"
                          
                          />
                          <button  type="button" class="bg-green-400 h-full p-2 rounded" @click="copyAddress">
                          <i class="las la-copy" ></i>
                
                          </button>
                        </div>
                      </div>
                    </div>
                
                    <ul class="list-decimal pl-4  text-gray-600 text-xs">
                
                      <li>Login to your Wallet,</li>
                      <li>Click on "<b>Send</b>" option in the main menu,</li>
                      <li>Enter Network address selected
                      </li>
                      <li>Enter selected amount of money, in any currency</li>
                      <li>Confirm Payment</li>
                      <li>Once you receive a confirmation message of payment,<br> Back to dashboard and confirm your transaction</li>
                
                    </ul>
                </div>
                <!-- Advcash Modal -->
                <div x-cloak x-show="showAdvcash"  class="bg-white p-6 w-xl">
                    <h2 class="text-lg font-semibold text-center">Fonepo | Activate</h2>
                    <h2 class="text-lg font-semibold">Pay With Advcash</h2>
                    <div id="advCash">
                    <ul class="list-decimal pl-4  text-gray-600 text-sm">
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
                </div>

                <!-- Perfect Modal -->
                <div x-cloak x-show="showPerfectMoney"  class="bg-white p-6 rounded-lg shadow-lg w-xl" >
                    <h2 class="text-lg font-semibold">Perfect Money</h2>
                    <div id="perfectMoney">
                        
                        <ul class="list-decimal pl-4  space-x-1 text-gray-600 text-sm">
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
                   
                </div>
                   



                <!-- footer -->
            
                <input type="text" 
                  id="paymentAccount"
                  required
                  placeholder="Enter wallet address used to send money"
                  class="border border-gray-500 p-1 w-full  px-3 rounded text-black placeholder:text-xs" name="paymentaccount" x-model="userUsedAddress"/>
                  <!-- <input type="text" :value="network"> -->
                  <!-- data -->
                   <input type="hidden" name="amount" :value="amount"/>
                   <input type="hidden" name="paymentMethod" :value="paymentMethod"/>
                   <input type="hidden" name="crypto_type" :value="cryptoType"/>
                  <div class="flex justify-between gap-2">
                    <button  type="submit"   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 
                        rounded mt-4 align-end text-sm">
                      Confirm your payments
                    </button>
                    <button type="button" @click="showModal = false" class="mt-4 px-4 py-2 bg-red-600 text-white rounded">Close</button>
                </div>
            </div>
        </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>


    <script>

      function Deposit(){

        return {
           paymentMethod: '',  
           bitcoin: false, 
           advcash: false, 
           perfect: false ,
           showBitcoin:false,
           showAdvcash:false,
           showPerfectMoney:false,
           amount:0,
           cryptoType:'',
           userUsedAddress:'',
           selectedAddress:'',
           showModal:false,
         
         generateQR (){
           
               $('#qrContainer').empty();
               $('#qrContainer').qrcode({
                 render: 'canvas',
                 text: this.selectedAddress,
                 width: 100,
                 height: 100
               });
         },
           copyAddress(){
                const addressInput = $('#addressInput');
                addressInput.select();
                document.execCommand('copy');
                const copyMessage = $('#copyMessage');
                copyMessage.removeClass('hidden');
                setTimeout(() => {
                    copyMessage.addClass('hidden');
                }, 2500);
           },
            toggleMethod(method){
               this.paymentMethod = method
            },
            continueNext(){
             
              if(!this.amount){alert("Amount is required!"); return }
              if(this.paymentMethod == "Bitcoin" && this.cryptoType == ""){alert("Select Crypto type!"); return }
               this.showModal = true
              switch (this.paymentMethod) {
                case 'Bitcoin':
                  this.showBitcoin=true;
                  this.showAdvcash=false;
                  this.showPerfectMoney=false;
                  break;
                case 'Advcash':
                  this.showBitcoin=false;
                  this.showAdvcash=true;
                  this.showPerfectMoney=false;
                  break;
                case 'Perfect Money':
                  this.showBitcoin=false;
                  this.showAdvcash=false;
                  this.showPerfectMoney=true;
                  break;
                default:
                  break;
              }
           },
          
           async submitPayment() {
            if (!this.paymentMethod) {
                alert('Please select a payment method.');
                return;
            }

            if (!this.amount) {
                alert('Please enter an amount.');
                return;
            }

            if (!this.userUsedAddress) {
                alert('Please enter address used.');
                return;
            }
            // paymentMethod: this.paymentMethod,
            //     cryptoType: this.paymentMethod === 'Bitcoin' ? this.cryptoType : null,
            //     amount: this.amount,
                
            let formData = {
                network:this.selectedAddress,
                expectedAmount:this.amount,
                payment_option:this.paymentMethod,
                userUsedAddress:this.userUsedAddress,
                currency_type:this.cryptoType ?this.cryptoType: this.paymentMethod 
            };
            
            $("#form").submit();
        }

          }
      }


      
    </script><?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\mcu.focoin.eu\resources\views/user/deposit-options.blade.php ENDPATH**/ ?>
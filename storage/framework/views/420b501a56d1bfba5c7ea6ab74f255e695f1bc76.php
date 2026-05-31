



<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title>Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
   <div class="content-wrapper">
<head>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
      )
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
      )
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
      )

      </script>
        <script>
            // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
            // Based on https://gist.github.com/blixt/f17b47c62508be59987b
            var _seed = 42;
            Math.random = function() {
              _seed = _seed * 16807 % 2147483647;
              return (_seed - 1) / 2147483646;
            };
          </script>

      <script>
        var lastDate = 0;
        var data = []
        var TICKINTERVAL = 86400000
        let XAXISRANGE = 777600000
        function getDayWiseTimeSeries(baseval, count, yrange) {
          var i = 0;
          while (i < count) {
            var x = baseval;
            var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

            data.push({
              x, y
            });
            lastDate = baseval
            baseval += TICKINTERVAL;
            i++;
          }
        }

        getDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 10, {
          min: 10,
          max: 90
        })

        function getNewSeries(baseval, yrange) {
          var newDate = baseval + TICKINTERVAL;
          lastDate = newDate

          for(var i = 0; i< data.length - 10; i++) {
            // IMPORTANT
            // we reset the x and y of the data which is out of drawing area
            // to prevent memory leaks
            data[i].x = newDate - XAXISRANGE - TICKINTERVAL
            data[i].y = 0
          }

          data.push({
            x: newDate,
            y: Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
          })
        }

        function resetData(){
          // Alternatively, you can also reset the data at certain intervals to prevent creating a huge series
          data = data.slice(data.length - 10, data.length);
        }
        </script>
</head>
<body>

   <div class="flex-1 p-4 ">

       <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.PaymentNav','data' => []]); ?>
<?php $component->withName('PaymentNav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

        <h4 class="px-2 flex gap-2"><span id="acc">CASHOUT</span> <span>ACCOUNT</span></h4>
        <div class="border px-4 py-4 rounded-lg my-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <select id="acc_choice" class="form-select block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option>CHOOSE ACCOUNT</option>
                        <option value="REDEEM_PACKAGE">Redeem Package</option>
                        <option value="UPGRADE">Upgrade</option>
                        <option value="PAID_ADS">Paid Ads</option>
                        <option value="USDT_TRON">USDT TRON</option>
                        <option value="PERFECT_MONEY">Perfect Money</option>
                        <option value="VOLE">Vole</option>
                    </select>
                </div>
                <div class="text-right">

                    <h1 class="text-gray-500" id="balance"><?php echo e($cashout); ?></h1>
                    <p class="text-gray-500">TOTAL</p>
                    <h4 class="text-2xl text-yellow-600 font-bold"></h4>
                </div>
            </div>
        </div>


        <div class="block sm:grid grid-cols-2 my-2 gap-2">

            <div class="bg-white p-4 flex flex-col rounded-lg my-2 sm:my-0">
                    <h2>TRANSFER TO USER</h2>

                    <?php if(session('success')): ?>
                        <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="bg-red-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <hr/>
                    <form class="flex flex-wrap sm:flex-nowrap gap-2" method="POST" action="<?php echo e(route('transfer-touser')); ?>" >
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('POST'); ?>
                        <input type="hidden" name="from_account" value="CASHOUT">
                        <div class="border  flex justify-center  rounded-lg p-0">
                            <input type="number" name="amount" placeholder="Amount.." class="px-2 py-2 "
                            max="<?php echo e($cashout); ?>" min="1.00" step="0.01"
                            />
                            <div class="bg-gray-800  px-2 flex  items-center text-white">$</div>
                        </div>
                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-600 py-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div class="border  flex justify-center items-center rounded ">
                            <input type="text" placeholder="username" class="px-2 py-2" name="name"/>
                        </div>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-red-600 font-semibold py-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div>
                            <button class="bg-yellow-500">Send</button>
                        </div>
                    </form>

            </div>


            <div class="bg-white p-4 flex flex-col rounded-lg my-2 sm:my-0">
                <h2>Deposit</h2>
                <hr/>
                <form class="flex flex-wrap gap-2  ">
                    <div class="border  flex justify-center  rounded-lg p-0">
                        <input type="number" name="amount" placeholder="Amount.." class="px-2 py-2 "/>
                        <div class="bg-gray-800  px-2 flex  items-center text-white">$</div>
                    </div>

                    <div class="border  flex justify-center items-center rounded ">
                        <select class="border-none outline-none focus:outline-none">
                            <option>CHOOSE PAYMENT TYPE</option>
                            <option>CHOOSE PAYMENT TYPE</option>
                            <option>CHOOSE PAYMENT TYPE</option>
                        </select>
                    </div>
                    <div>
                        <button class="bg-yellow-500">Send</button>
                    </div>
                </form>

            </div>


            <div class="bg-white p-4 flex flex-col rounded-lg my-2 sm:my-0">
                <h2>TRANSFER TO TRADING ACCOUNT</h2>
                <?php if(session('success-tr')): ?>
                    <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        <?php echo e(session('success-tr')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error-tr')): ?>
                    <div class="bg-red-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        <?php echo e(session('error-tr')); ?>

                    </div>
                <?php endif; ?>
                <hr/>
                <form class="flex gap-2 flex-wrap " action="<?php echo e(route('transfer-totrading')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <div class="border  flex justify-center  rounded-lg p-0">
                        <input type="number" name="amount" placeholder="Amount.." class="px-2 py-2 " max="<?php echo e($cashout); ?>" required step="0.01"/>
                        <div class="bg-gray-800  px-2 flex  items-center text-white">$</div>
                    </div>
                    <input type="hidden" name="from_account" class="from_account" value="CASHOUT">

                    <div>
                        <button class="bg-yellow-500" type="submit">TRANSFER TO TRADING ACCOUNT</button>
                    </div>
                </form>

            </div>


            <div class="bg-white p-4 flex flex-col rounded-lg my-2 sm:my-0">
                <h2>WITHDRAW (Processing Time Is Up To One Week)</h2>
                <?php if(session('success-trx')): ?>
                    <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        <?php echo e(session('success-trx')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error-trx')): ?>
                    <div class="bg-red-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        <?php echo e(session('error-trx')); ?>

                    </div>
                <?php endif; ?>

                <hr/>
                <form class="flex gap-2  flex-wrap" method="POST" action="<?php echo e(route('transferToAccount')); ?>" id="transferForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('POST'); ?>
                    <div class="border-2 flex justify-center  rounded-lg p-0">
                        <input type="number" name="amount" placeholder="Amount.." class="px-2 py-2 "
                         max="<?php echo e($cashout); ?>" min="1.00" step="0.01" required/>
                        <div class="bg-gray-800  px-2 flex  items-center text-white" >$</div>
                    </div>

                    <input type="hidden" name="from_account" class="from_account" value="CASHOUT">

                    <div class="border  flex justify-center items-center rounded ">
                        <select class="border-none outline-none focus:outline-none" name="account" id="receiver_acc">

                            <option>CHOOSE ACCOUNT</option>
                            <option value="REDEEM_PACKAGE">Redeem Package</option>
                            <option value="UPGRADE">Upgrade</option>
                            <option value="PAID_ADS">Paid Ads</option>
                            <option value="USDT_TRON">USDT TRON</option>
                            <option value="PERFECT_MONEY">Perfect Money</option>
                            <option value="VOLE">Vole</option>

                        </select>
                    </div>
                    <div>
                        <button class="bg-yellow-500" type="submit">Send</button>
                    </div>
                </form>



            </div>

            <div class="my-3  p-4 flex flex-col rounded-lg col-span-2">
                <h3 class="font-bold py-2 text-2xl">HISTORY</h3>

                <h4 class="text-cyan-500 py-2 text-md">Don't sell your token before going public exchange please</h4>


                    <div class="grid grid-cols-1  sm:grid-cols-2  gap-2">

                        

                        <div class="bg-white py-3 px-2 rounded">
                           <form>
                            <table class=" w-full">
                                <thead>
                                    <tr>
                                        <th  class="border-b pb-3 text-left" colspan="2">
                                            <h1 class="text-left uppercase">Buy Coin</h1>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-3 px-2">Quantity *: </td>
                                        <td class="py-3 px-2"><input type="number" step="0.01"
                                           placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                    </tr>

                                    <tr>
                                        <td class="py-3 px-2">Price soon:</td>
                                        <td class="py-3 px-2"><input type="number" name="price" step="0.01" placeholder="Price " class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                    </tr>

                                    <tr>
                                        <td class="py-3 px-2"> Fees: </td>
                                        <td class="py-3 px-2">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-2">Total: </td>
                                        <td class="py-3 px-2">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-2"></td>
                                        <td class="py-3 px-2">
                                            <button class="w-full">Buy</button>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>

                           </form>
                        </div>
                        <div class="bg-white py-3 px-2 rounded">
                          <div class="flex gap-2 items-center justify-between ">
                            <div>
                              <h5 class="text-left uppercase">Sell With buy pack program</h5>
                            </div>
                            <a class="px-3 py-2 bg-blue-500 text-gray-50 text-center rounded" href="#">READ MORE</a>
                          </div>

                            <form>
                             <table class=" w-full">
                                 <thead>
                                     <tr>
                                         <th  class="border-b pb-3 text-left" colspan="2">

                                                <h1 class="text-left uppercase">Sell Coin </h1>

                                         </th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td class="py-3 px-2">Quantity *: </td>
                                         <td class="py-3 px-2"><input type="number" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                     </tr>

                                     <tr>
                                         <td class="py-3 px-2">Price 0.0004:</td>
                                         <td class="py-3 px-2"><input type="number" name="price" step="0.01" placeholder="Price" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                     </tr>

                                     <tr>
                                         <td class="py-3 px-2"> Fees: </td>
                                         <td class="py-3 px-2">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                     </tr>
                                     <tr>
                                         <td class="py-3 px-2">Total: </td>
                                         <td class="py-3 px-2">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                                     </tr>
                                     <tr>
                                         <td class="py-3 px-2"></td>
                                         <td class="py-3 px-2">
                                             <button class="w-full bg-red-500 hover:bg-red-600">Sell</button>
                                         </td>
                                     </tr>
                                 </tbody>

                             </table>

                            </form>
                        </div>

                        <div class="bg-white py-3 px-2 rounded">
                              <div id="chart"></div>
                        </div>

                        <div class="right-side">
                            
                            <div class="flex flex-col gap-3">
                                <div class="bg-gray-800 px-3 py-4 rounded  flex flex-col gap-2">
                                    <h1 class="text-red-500" >INTERNAL BUY PACKAGE PRICE: 0.004</h1>
                                    <h1 class="text-red-500">LIST PUBLIC EXCHANGE PRICE: 0.125</h1>
                                </div>

                                <div class="py-3 px-3 border rounded">
                                    <h1 class="text-red-600 py-2">TARGET EXCHANGE</h1>

                                    <div class="grid grid-cols-4 gap-2">
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/pancake.jpeg")); ?>"
                                            style="width:60px;height:60px;object-fit:cover" class="rounded-circle">
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/okx.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/exchange.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/kucoin.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/yo.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/bybit.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/binance.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>

                                        </div>
                                        <div class=" py-3 px-2 text-center rounded block">
                                            <img src="<?php echo e(asset("image/bitcoin.jpeg")); ?>" style="width:60px;height:60px;object-fit:cover" class=" rounded-circle"/>
                                        </div>














                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>


            </div>


        </div>





       </div>
     </div>

     <script>


                    const acc_choice = document.querySelector("#acc_choice")
                    const receiver_acc = document.querySelector("#receiver_acc")
                    const title = document.querySelector("#acc")
                    const balance = document.querySelector("#balance")
                    const accounts = [
                                    {name:'REDEEM_PACKAGE',title:'Redeem Package'},
                                    {name:'UPGRADE',title:'Upgrade'},
                                    {name:'PAID_ADS',title:'Paid Ads'},
                                    {name:'USDT_TRON',title:'USDT TRON'},
                                    {name:'PERFECT_MONEY',title:'Perfect Money'},
                                    {name:'VOLE',title:'Vole'}
                    ]

                    const from_accounts = document.querySelectorAll(".from_account")


                    acc_choice.addEventListener("change",function(e){
                        const selected = e.target.value
                        const findAcc = accounts.find((ac)=>ac.name == selected)
                        balance.innerHTML = '0.00$'
                        console.log(from_accounts)

                        const removeSelected = accounts.filter((ac)=>ac.name != selected);

                        fetch('finance/account?ac='+selected)
                            .then((response)=> response.json())
                            .then((data)=>{
                                balance.innerHTML = data.balance
                                title.innerHTML = findAcc.title.toUpperCase()

                                Array.from(from_accounts).forEach((el) => {
                                    el.value = findAcc.name;
                                });
                                // console.log(from_accounts[0].value);
                            })
                            .catch((err)=>{
                                console.log(err)
                            })
                        receiver_acc.innerHTML = "<option>CHOOSE ACCOUNT</option>"

                        removeSelected.map((ac)=>{
                            receiver_acc.innerHTML += `<option value='${ac.name}'>${ac.title}</option>`
                        })


                    })



      var options = {
        series: [{
        data: data.slice()
      }],
        chart: {
        id: 'realtime',
        height: 350,
        type: 'line',
        animations: {
          enabled: true,
          easing: 'linear',
          dynamicAnimation: {
            speed: 1000
          }
        },
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      title: {
        text: 'MARKET DEPTH',
        align: 'left'
      },
      markers: {
        size: 0
      },
      xaxis: {
        type: 'datetime',
        range: XAXISRANGE,
      },
      yaxis: {
        max: 100
      },
      legend: {
        show: false
      },
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();


      var intervalRuns = 0;
    var interval = window.setInterval(function () {
      intervalRuns++
      getNewSeries(lastDate, {
        min: 10,
        max: 90
      })

      chart.updateSeries([{
        data: data
      }])

      if (intervalRuns === 2 && window.isATest === true) {
        clearInterval(interval)
      }
    }, 1000)

  </script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script>

    document.addEventListener("DOMContentLoaded",function(){
        // const Swal = new sweetalert2()

    const transferForm = document.querySelector("#transferForm")


// transferForm.addEventListener("submit",function(e){
//     e.preventDefault()
//     Toastify({
//   text: "This is a toast with offset",
//   newWindow: true,
//   offset: {
//     x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
//     y: 10 // vertical axis - can be a number or a string indicating unity. eg: '2em'
//   },
// }).showToast();
// })
// console.log(sweetalert2)



    })
  </script>
</body>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\mcu.focoin.eu\resources\views/user/payments.blade.php ENDPATH**/ ?>
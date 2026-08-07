



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

   <div class="flex-1 p-4 ">  <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
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
<?php endif; ?></div>


   <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-5xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">Transaction History</h2>

    <!-- Example Transaction Record -->
    <div class="border-b pb-4 mb-4">
        <div class="flex justify-between items-center">
            <span class="text-gray-500">Date:</span>
            <span class="text-black font-bold">15 October 2024</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">Amount:</span>
            <span class="text-black font-bold">$200.00</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">From Account:</span>
            <span class="text-black font-bold">USDT TRON</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">Received Account:</span>
            <span class="text-black font-bold">Perfect Money</span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500">Transaction Fee:</span>
            <span class="text-black font-bold">$0.00</span>
        </div>
        <div class="flex justify-between items-center border-t pt-2 mt-2">
            <span class="text-gray-700 text-lg">Total:</span>
            <span class="text-yellow-500 font-bold text-lg">$200.00</span>
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





    })
  </script>
</body>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/myinvoice.blade.php ENDPATH**/ ?>
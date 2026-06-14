
<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = Auth::user();
$name = $user->user;
 $email = $user->email;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>FONEPO | DEPOSIT</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
.rules div{
    display:flex;


}
body{
    font-family: "Poppins", sans-serif !important;
    font-weight: 400;
    font-style: normal;
}
.rules > div > img{
    width: 20px;
}
.warning{
    background-color: #000050;
    color: white;
}
.warning-head{
    display: flex;
    flex-direction: column;
    align-items: center;
    color: red;
}

.foma {
                   width: 100%;

                padding: 20px 0;
                border-radius: 4px;

background:linear-gradient(190deg, #2ecd71 60%, #27ae60 40.1%);

                margin-top: 37px;

            }
            .pay-input{
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: white;
    border-radius: 3px;
    width: 80%;
    margin: 20px auto;
    padding: 0 20px;
}
.pay-btn{
    background-color: #337ab7;
    font: 12px Cabin,sans-serif;
    padding: 12px 20px;
    color: #ffffff;
}
[x-cloak]{
    display:none !important;
}

</style>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</head>
<body>
    

<div class="row min-h-screen grid grid-cols-1 grid-flow-cols-reverse sm:grid-cols-5  justify-content-center gap-3 justify-center p-3">
<div class="warning  shadow rounded px-4 py-4 col-span-3">
    <div class="warning-head my-2 flex gap-2">
        <div class="icon"><i class="las la-exclamation-triangle"  style="font-size: 40px"></i></div>
        <div style="font-size: 25px">Payment Rules  </div>
    </div>

    <div class="rules flex flex-col gap-3">
              <div>
            <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2"> Overpayment will be deposited as cash out.</p>
        </div>
            <div>
            <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2"> In case of underpayment, you will be allowed to pay remaining.</p>
        </div>
        <div>
            <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2"> do not send your USDT twice to the address on invoice.</p>
        </div>
        <div>
            <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2"> do not create an invoice unless you will be deposited USDT </p>
        </div>
        <div>
             <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
             <p class="px-2"> Only USDT TRC-20 payments are supported </p>
        </div>
        <div>
                <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
                <p class="px-2"> Please create new invoice to resend USDT in case of underpayment </p>
        </div>
        <!--<div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> You can only create 3 invoices with pending status at a time-->
        <!--</div>-->
         <div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2">N.B: Make sure you have paid the full amount that was on the invoice </p>
        </div>
         <div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">
            <p class="px-2">N.B: Remember to cover all transction fees of network </p>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button class="btn btn-warning  bg-blue-600 px-3 py-2 rounded max-w-lg mx-auto block my-3">I Understand</button>
    </div>
</div>

<div class=" col-span-2 ">
    <div>

<button onclick="history.back()" class=" text-blue-600 bg-blue-100 cursor-pointer py-2 px-2 outline-none rounded"> <i class="las la-arrow-circle-left"></i> Back to package</button>


          <div class="foma" id="foma" >
            
                 <label id="am">

                     <span id="payment_area"></span>


                  <div>
                    <h1 class="text-center font-bold uppercase  text-slate-800">Select a Deposit Option</h1>

                    <?php echo $__env->make('user.deposit-options', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  </div>
                </div>







    </div>
    </div>
</div
</div>
</body>
</html>



<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/user/otherpayment.blade.php ENDPATH**/ ?>

 <?php

 use Illuminate\Support\Facades\Auth;
 use App\Models\User;

 $user = Auth::user();
 $name = $user->user;
  $email = $user->email;



 // $ref_code = $user->activation;
 ?>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
       <link rel="stylesheet" href="../libs/css/bootstrap.v3.3.6.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/a/dist/css/bootstrap.min.css')); ?>">
<style>
             body {
                 font-family: Arial, sans-serif;
                 margin: 0;
                 padding: 20px;
                 background-color: #f5f5f5;
             }

             #foma {
/*                    display: none;*/
             }

             h1 {
                 text-align: center;
             }

             .foma {
                    width: 100%;
                 /*margin: 0px;*/
/*                    background-color: #fff;*/
/*                    margin-left:15px;*/
                 padding: 20px 0;
                 border-radius: 4px;
/*                    box-shadow: 0 2px 4px rgba(0, 3, 0, 0.1);*/
 background:linear-gradient(190deg, #2ecd71 60%, #27ae60 40.1%);

                 margin-top: 37px;

             }
             .container label{
                 color:whitesmoke;
                 font-size:18px;
             }
                 .add-ditail{

                     width: 135px;
                     height: 35px;

                     margin-right:20px;
                     border:none;
                     border-radius:4px;

                 }
             input[type="radio"] {
                 margin-bottom: 10px;
             }

             input[type="submit"] {
                 display: block;
                 /*margin: 20px;*/
                 /*padding: 10px 20px;*/
/*                    background-color: #4CAF50;*/
                 color: #fff;
                 border: none;
                 border-radius: 4px;
                 cursor: pointer;
                 font-size: 16px;
             }

             .paypal {
                 background-color: whitesmoke;
                 color: black;
                 padding: 1px;
                 border-radius: 4px;
                 display: inline-block;
             }

             .crypto {
                 background-color: whitesmoke;
                 color: black;
                 padding: 1px;
                 border-radius: 4px;
                 display: inline-block;
             }

             .perfectmoney {
                 background-color: #c82586;
                 color: black;
                 padding: 10px;
                 border: 4px solid white;
                 display: inline-block;
             }

             .foma img {
                 width: 100px;
                 height: 40px;
             }

             .visa {
                 background-color: whitesmoke;
                 color: black;
                 padding: 1px;
                 border-radius: 4px;
                 display: inline-block;
             }

             .mastercard {
                 background-color: #e61c39;
                 color: #fff;
                 padding: 10px;
                 border-radius: 4px;
                 display: inline-block;
             }

             .skrill {
                 background-color: black;
                 color: #fff;
                 padding: 10px;
                 border-radius: 4px;
                 display: inline-block;
             }

             .mobilemoney {
                 background-color: black;
                 color: #fff;
                 padding: 10px;
                 border-radius: 4px;
                 display: inline-block;
             }
             #myButton {
background-color: #4CAF50;
color: white;
padding: 10px 20px;
border: none;
cursor: pointer;
}

.form-popup {
display: none;
position: absolute;
bottom: 0;
right: 15px;
border: 1px solid #555;
z-index: 9;
}

.form-container {
max-width: 300px;
padding: 20px;
background-color: white;
}

.form-container h2 {
text-align: center;
margin-bottom: 20px;
}

.form-container input[type=text],
.form-container input[type=email] {
width: 100%;
padding: 12px;
margin: 8px 0;
border: 1px solid #ccc;
}

.form-container .btn {
background-color: #4CAF50;
color: white;
padding: 12px 20px;
border: none;
cursor: pointer;
width: 100%;
margin-bottom: 10px;
}

.form-container .cancel {
background-color: #ccc;
font-weight:bold;
}

.form-container .btn:hover,
.open-button:hover {
opacity: 0.8;
}

/* payment page */

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
 .back-btn{
     background-color: #337ab7;
     font: 12px Cabin,sans-serif;
     padding: 12px 20px;
     color: #ffffff;
     margin: 20px 0;
     border: none;
     font-size: 16px;
     border-radius: 4px;
 }
 .inp-radio{
   border: 1px solid red;
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
 .rules > div{
     margin: 20px 0;
     font-size: 17px;
 }
 .rules > div > img{
     width: 20px;
 }
</style>


<div class="row d-flex justify-content-center">
 <div class="warning col-sm-10 col-lg-5 mx-md-3 shadow rounded px-4 py-4">
     <div class="warning-head my-2">
         <div class="icon"><i class="las la-exclamation-triangle"  style="font-size: 40px"></i></div>
         <div style="font-size: 25px">Payment Rules</div>
     </div>


     <div class="rules">
               <div>
             <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> Overpayment will be deposited as cash out.
         </div>
             <div>
             <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> In case of underpayment, you will be allowed to pay remaining.
         </div>
         <div>
             <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> do not send your USDT twice to the address on invoice.
         </div>
         <div>
             <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">  do not create an invoice unless you will be deposited USDT
         </div>
         <div>
              <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt="">  Only USDT TRC-20 payments are supported
         </div>
         <div>
                 <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> Please create new invoice to resend USDT in case of underpayment
         </div>
         <!--<div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> You can only create 3 invoices with pending status at a time-->
         <!--</div>-->
          <div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> N.B: Make sure you have paid the full amount that was on the invoice
         </div>
          <div> <img src="<?php echo e(asset('assets/a/img/warning.png')); ?>" alt=""> N.B: Remember to cover all transction fees of network
         </div>
     </div>
     <div class="d-flex justify-content-center">
         <button class="btn btn-warning ">I Understand</button>
     </div>
 </div>

 <div class="col-sm-10 col-lg-5 mx-md-3">
     <div>
         <?php
             $amount=$venture->amount_invest;
             $package = $venture->venture;
            //  dd(session('payment_intent');j;);
         ?>


           <form class="foma" id="foma"method="POST" action="<?php echo e(route('paymentventure')); ?>" >
                     <?php echo csrf_field(); ?>
                  <label id="am">
                      <input type="hidden" name="amount" id="amount_to_invest" value="<?php echo e($venture->amount_invest); ?>">
                    </label>
                      <label id="am1">
                         <input type='hidden' name='email' value='<?php echo e($email); ?>'>
                         <input type='hidden' name='package'  value='<?php echo e($venture->venture); ?>'>
                      </label>

                      <span id="payment_area"></span>

                   <span style="background: #393d4a;color: red;text-transform:uppercase" class="p-2 rounded">
                    UVP &nbsp;&nbsp;<?php echo e($venture->amount_invest); ?> $
                    </span>
                   <div id="hideme">


                    <h1>Select a Payment Option </h1>







      <div class="pay-input">
         <div  style="margin-top:5px">
             <input type="radio" name="option" value="crypto"  checked>
         </div>
         <div>
            Cryptocurrency Payment
         </div>
         <div>
             <img src="https://plisio.net/img/donate/donate_light_icons_color.png" alt="Donate Crypto on Plisio" style="width:80px; height: 30px;" />
         </div>

     <?php


           if($pay=='fc1')
         {
           ?>
             <script>
             var amount=100;
             // alert(amount);
             //   function pay() {
             // document.getElementById('hideme').style.display="none";
                //  Blockonomics.widget({
                //    msg_area: 'payment_area',
                //    uid: 'b2ece55d990d487f',
                   // email: '<?php echo e($email); ?>',
                //    amount:amount
             //   alert(9);//
            //  }) ;
             //  }

             //   document.getElementById('pay').onclick = function() { pay() };
             </script>

             <?php }; ?>



             <script>
             var amount=documen.getElementById("amount_to_invest").value;
              alert(amount);

                 Blockonomics.widget({
                   msg_area: 'payment_area',
                   uid: 'b2ece55d990d487f',
                   // email: '<?php echo e($email); ?>',
                   amount:amount
             //   alert(9);//
             }) ;

             </script>



     </div>

     <div class="pay-input">
         <!--<div style="margin-top:5px">-->
         <!--    <input type="radio" name="option" value="card">-->
         <!--</div>-->
         <!--<div>-->
             <?php echo e($p_fail??''); ?>

         <!--</div>-->
         <!--<div>-->
         <!--    <img src="https://cdn-icons-png.flaticon.com/128/179/179457.png" style="width:34px; height: 44px;" alt="Visa Card"> -->
         <!--</div>-->
     </div>



     <center>
     <input type="submit" value="Proceed to Payment" class="pay-btn"></center>
 </form>
     </div>
 </div
</div>


<button onclick="history.back()" class="back-btn"> <i class="las la-arrow-circle-left"></i> Back to package</button>
<?php /**PATH /home/cwkwkueb/mcu.focoin.eu/resources/views/user/venture.blade.php ENDPATH**/ ?>
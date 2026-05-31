

<style>
    body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  
}

header {
  background-color: #333;
    color:white;
  text-align: center;
  padding: 10px;
  font-weight:bold;
}

main {
  padding: 20px;
}

.deposit-form {
  border-radius: 4px;
  padding: 20px;
}

.deposit-form h2 {
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="number"] {
  width: 100%;
  border: none;
  padding: 10px;
  color:white;
  border-bottom: 1px solid gray;
  background-color: black;
}

button {
  background-color: #333;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #555;
}

footer {
  background-color: #f2f2f2;
  text-align: center;
  padding: 10px;
    color:white;
}
.main_head{
  width: 90%;
  margin: 0 auto;
  font-size: 30px;
  /* border-bottom: 2px solid darkred; */
  padding: 15px 0;
  /* text-align: center; */
  color:white;
  font-weight:bold;
  padding: 15px 0;
}
.main_head::after{
  content: "";
  width: 200px;
  height: 5px;
  background: darkred;
  display:block;
  margin-top: 10px;
  border-radius: 20px;
}
.dep_hist_head{
  border-bottom: 1px solid gray;
}
h3{
  font-size: 20px;
  font-weight:bold;
  margin: 10px 0;
    color:white;
}
.small_text{
  font-size: 14px;
    color:white;
}
.convert{
      color:white;
  background-color: inherit;
  width: 100%;
  border: 2px solid red;
  padding: 7px 20px;
  border-radius: 0;
  color:  red;
  font-weight:bold;
}
.convert:hover{
  background-color: #111;
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
     @include('user.user-dashboard-base')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <div class="content-wrapper text-white" style="background:#1c1d20;">

     <div class="tabs tab_links my-2" style="border-bottom: 1px solid white">
      <span class="links_tabs d-flex ">
        <a  href="{{route('user.dashboard.deposit')}}"  class="fomoLink text-white" id="tabs"> <i class="fa-regular fa-address-card"></i> Deposite</a>
        <a href="{{route('user.dashboard.withdraw')}}" class="fomoLink text-white"><i class="fa-solid fa-money-bill-transfer"></i> Withdraw </a>
      </span>
    </div>
    <h1 class="main_head">Deposit money To Your Account </h1>

   <main>
    <div class="row">
        <div class="deposit-form col-md mx-md-2 px-lg-3 px-xl-5">
          <h3 class="text-center">Deposit Amount </h3>
           @if($ammount)  <span class="btn btn-secondary">{{$ammount}}</span>@endif 
            @if(session('message'))
                <span class="btn btn-success " >
                  {{ session('message') }}
                </span>
                @endif
                    @if($status)
                <span class="btn btn-success " >
                    {{ $status }}
                </span>
                @endif
                      @if($p_failed)
                <span class="btn btn-danger " >
                    {{ $p_failed }}
                </span>
              
                @endif
          <form id="depositForm" method="post" action="{{route('user.deposit')}}">
              @csrf
            <div class="form-group">
              <label for="currency" style="  color:white;">Currency:  USD($)</label>
            </div>
            <div class="form-group">
              <label for="amount" style=" color:white;">Amount:</label>
              <input type="number"  id="amount" name="amount" required class="amountInput" placeholder="Enter deposite amount"> 
            </div>
            <div class="form-group">
              <button type="submit">Deposit</button>
            </div>
          </form>

          <div class="dep_history my-4">
            <div class="d-flex justify-content-between dep_hist_head px-3">
                <div><h3>Your Deposit history</h3></div>
                <div><i class="las la-redo-alt text-white"></i></div>
            </div>
            <div class="Deposit list">
                <div class="zeroDeposite d-flex align-items-center justify-content-center my-3">
                  <i class="las la-info-circle text-white text-sm"></i>
                  <div class="mx-1 text-sm" style="  color:white;">no deposits for this address yet</div>
                </div>
            </div>
          </div>
         
        </div>

        <div class="col-md px-md-2 px-lg-3 px-xl-5">
            <h3>Deposit Rules</h3>
            <div style="border: 1px solid gray; border-radius: 4px; padding: 10px;  color:white;">
              <p>do not send your USDT twice to the address on the invoice. </p>
             <p>Do not create  an invoice  unless you will be depositing USDT.</p>
              <p> Only USDT TRC-20 payment is supported.</p>
               <p>Please create new invoice to resend USDT.</p>
            </div>
            <div style="display: none;">
               <h3 style="border-bottom: 1px solid gray; padding: 10px 0;">You can only create 3 invoices with pending status at time.</h3>
                
              
            </div>
           
        </div>
    </div>
    
    </main>
 
  </div>  
      @include('user.footer')
</div>
</body>
</html>


<?php
Use App\Models\Balance;
Use App\Models\User;
Use App\Models\Wallet as wallet;
use Illuminate\Support\Facades\Auth;

$user=Auth::User();
$username=$user->user;
$wallet=wallet::where('user',$username)->first();

// $depositedMoneySum = Balance::where('user', $username)->sum('deposit');
// $usedMoneySum = Balance::where('user', $username)->sum('used');

// $availlableBalance=$depositedMoneySum-$usedMoneySum;

?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
     @include('user.user-dashboard-base')
  <div class="content-wrapper text-white" style="background:#1c1d20;">

    <div class="tabs tab_links my-2" style="border-bottom: 1px solid white">
      <span class="links_tabs d-flex ">
        <a  href="{{route('user.dashboard.deposit')}}"  class="fomoLink text-white" id="tabs"> <i class="fa-regular fa-address-card"></i> Deposite</a>
        <a href="{{route('user.dashboard.withdraw')}}" class="fomoLink text-white"><i class="fa-solid fa-money-bill-transfer"></i> Withdraw </a>
      </span>
    </div>

  <div  >
    <h1 class="main_head">Withdraw money From Your Account </h1>

   <main>
    <div class="row">
        <div class="deposit-form col-md mx-md-2 px-lg-3 px-xl-5" style="background: #000;border-radius: 6px; ">
          <h3 class="">Withdraw Operation</h3>

          <div class="d-flex justify-content-between" style="border-bottom: 1px solid gray">
            <div>Avalable balance: </div>
            <div>{{$availlableBalance}}</div>
          </div>

          <div class="mt-3">
            <form method="post" action="{{route('user.withdraw')}}">
                @csrf
                <div class="row my-2">
                  <label for="" class="ml-2">  @if(!$wallet)  No wallet adress found, go in <a  href="{{route('profile.edit')}}" style="color:blue">Profile</a> to set it @else Wallet Address <small>
                     Do you want to change wallet address? back to <a  href="{{route('profile.edit')}}" style="color:blue">Profile</a>. </small></label>
                    <div class="col-md-10">
                      <input type="text"  id="amount" name="address" readonly class="form-control" value="{{$wallet->wallet}}">
                    </div>
                    <!--<div class="col-md-2">-->
                    <!--    <input type="submit" name="" class="btn btn-primary" value="change">-->
                    <!--</div>-->

                </div>

                <div class="my-2">
                   <div class="form-group">
                      <label for="amount" style=" color:white;">Amount: minimal amount is: 12$</label>
                      <input type="number"  id="amount" name="amount" min="12" required  class="amountInput" placeholder="Enter Withdraw amount">
                    </div>
                </div>

                 <div class="form-group my-2">
                    <button type="submit">Withdraw</button>
                  </div>
                  @endif
            </form>
          </div>


                </div>

        <div class="col-md px-md-2 px-lg-3 px-xl-5">

        </div>
    </div>

    </main>
  </div>





  </div>
      @include('user.footer')
</div>
</body>
</html>

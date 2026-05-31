<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
// use App\Models\Activations
$user = Auth::user();
$name = $user->name;
$email=$user->email;
$activated=$user->has_paid_package;
$created=$user->created_at;
// $act=find::Activations()
$sponsored=$user->referee_id??'System';

?>

 <div>
     @include('user.user-dashboard-base')
<style type="text/css">
    .table-container {
  overflow-x: auto;
}

.responsive-table {
  width: 80%;
  border-collapse: collapse;
  margin-left: 50px;
}

.responsive-table th,
.responsive-table td {
  padding: 8px;
  border: 1px solid #ccc;
}

@media screen and (max-width: 600px) {
  .responsive-table th,
  .responsive-table td {
    font-size: 14px;
  }
}
.team-container{
    width: 95%;
    margin: 0px auto;
    background-color: white;
    border-radius: 3px;
}
</style>
        <div class="content-wrapper pt-5 payContainer">
        <div class="payment_method row">

            <div class="col-md-6 ">
              <h4 class="payHead">Deposite Methods</h4>
                 <div class="methods rounded row">

                    <div class="expendImg_container col">
                        <img id="expandedImg" style="width:100%" src="{{asset('assets/a/img/payments.png')}}">
                      <!-- <div id="imgtext"></div> -->
                    </div>

                    <!-- The four columns -->
                    <div class="col">
                      <div class="row pay_name">
                        <div class="col-12 pay_item">
                          <button class="payBtn w-100 d-flex justify-content-start font-weight-bold" onclick="myFunct(this);">
                            <img  src="{{asset('assets/a/img/bitcoin.png')}}" > &nbsp; Bitcoin
                          </button>
                        </div>
                        <div class="col-12 pay_item w-100">
                           <button class="payBtn w-100 d-flex justify-content-start" onclick="myFunct(this);">
                            <img  src="{{asset('assets/a/img/usdt.png')}}" alt="Snow"  >  &nbsp;  Usdit
                          </button>

                        </div>
                        <div class="col-12 pay_item w-100">
                           <button class="payBtn w-100 d-flex justify-content-start" onclick="myFunct(this);">
                             <img src="{{asset('assets/a/img/binance.png')}}" alt="Mountains" >  &nbsp;  Binance
                          </button>
                        </div>

                        <div class="col-12 pay_item w-100">
                           <button class="payBtn w-100 d-flex justify-content-start" onclick="myFunct(this);">
                            <img src="{{asset('assets/a/img/money.png')}}" alt="Lights">  &nbsp;  Perfect money
                          </button>

                        </div>
                      </div>
                    </div>

                  </div>
            </div>

            <div class="col-md-5 d-flex justify-content-center  rounded trad">
              <div class="w-75">
                   <h4 class="text-center">Transaction or</h4>
                  <div>
                    <div class="title text-lg font-weight-bold ">Withdraw method 1</div>
                    <div class="mx-4 my-2">
                       <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                    </div>

                  </div>
                   <div>
                    <div class="title text-lg font-weight-bold">Withdraw method 2</div>
                    <div class="mx-4 my-2">
                       <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                    </div>
                  </div>
                   <div>
                    <div class="title text-lg font-weight-bold">Withdraw method 3</div>
                    <div class="mx-4 my-2">
                       <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                        <div class="d-flex my-2 align-items-center">
                          <div><i class="las la-arrow-right trad-icon"></i></div>
                          <div> Peer to peer on system</div>
                        </div>
                    </div>
                  </div>
              </div>

            </div>

      </div>
        </div>
     <!-- /.col -->
 </div>
 </div>
     <!-- /.col -->
 </div>
 </div>

 <script>
    function myFunct(button) {
      var expandImg = document.getElementById("expandedImg");
      var image = button.querySelector('img');
      var imageUrl = image.src;
      expandImg.src = imageUrl;
      console.log(imageUrl); // You can use the image URL as per your requirement
    }
</script>

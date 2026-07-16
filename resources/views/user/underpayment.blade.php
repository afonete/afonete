<?php

    use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

    use Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\Payment as Paymodel;


    $user = Auth::user();
    $name = $user->name;
    $usename=$user->id;
    $email = $user->email;
    //  $userPayment = Paymodel::where('user', $username)->order_by('id','desc')->first();
    //  $totalPaid = Paymodel::where('user', $username)->sum('paid');
    // $package=$userPayment->package=='fc1'?'FC $100':'FC 200';
    $userPayment = Paymodel::find(1)->first();
     $totalPaid = Paymodel::where('user', $username)->sum('paid');
    $package=$userPayment->package=='fc1'?'FC $100':'FC 200';

$paid=$userPayment->paid;
$amount=$userPayment->amount;
$totalamount=$paid+$totalPaid;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bifonex Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            width: 500px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #e74c3c;
        }

        p {
            color: #333;
        }

        a {
            text-decoration: none;
            color: #3498db;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form class="foma" id="foma" method="POST" action="{{route('payment')}}" >
                        @csrf
                           <label id="am"> <input type="hidden" name="amount" id="amount" value="{{$amount}}"></label>
                           <input type='hidden' name='email' value='{{$email}}'>
                            <input type='hidden' name='package' id='amount' value='{{$package}}'>
        <h2>Underpayment made on chosen package of ({{$package}})</h2>
        <p>You have paid (${{$paid}}) your total payment is (${{$totalPaid}}). You need to pay (${{$totalamount}}) to activate package of (${{$amount}}).</p>
        <p>Please  <input type="submit" value="Payment your remaing" class="pay-btn"></center></a> with other amount.</p>
    </form>
    </div>
</body>
</html>

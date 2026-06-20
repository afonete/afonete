<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FONEPO - Payment Confirmation</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link rel="icon" href="{{asset('assets/a/img/big-logo.png')}}">
    <style>
 body {
  font-family: "Poppins", sans-serif;
  font-weight: 400;
  font-style: normal;
  }

    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Graphic Image -->
        <div class="">
            <img class="w-[4rem]  object-cover " src="{{ asset('image/deposits.png') }}" alt="Payment Graphic">

        </div>
        @if ($status == 'i')

        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-4 uppercase">Payment Confirmation</h2>

            <p class="text-center font-bold text-red-500">
                You have an insufficient balance. Your current balance is
                <span class="text-black">${{ number_format($currentBalance, 2) }}</span>,
                 but you need
                 <span class="text-black">${{ number_format($requiredAmount, 2) }}</span>
                  to complete this transaction.
            </p>

            <div class="flex justify-between items-center py-2">

            <button onclick="window.history.back()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Back
            </button>

            <form action="{{route('paymentventuredeposits')}}" method="post">
                @csrf

                @method('POST')




                <input type="hidden" name="package" value="{{$venture->id}}"/>
                <input type="radio" name="option" value="crypto"  checked>
                <input type="hidden" name="package_type" value="VENTURE">
                <input type="hidden" name="amount"
                class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded"
                value="{{$amount}}"
                />
                <button class="text-blue-500 underline text-lg capitalize hover:text-blue-600 font-semibold" type="submit">
                    use crypto payment method
                </button>
            </form>
            </div>



        </div>

        @else
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-4 uppercase">Payment Confirmation</h2>
              @if ($package == 'FC')
                <p class="text-center">You are about to pay <strong>${{ number_format($amount, 2) }}</strong>
                    for <strong>{{$name}} </strong> package </strong>
                </p>
              @else
                <p class="text-center">You are about to pay <strong>${{ number_format($amount, 2) }}</strong>
                    for <strong>{{$venture->name}} </strong> package that will last for <strong>{{ $venture->duration }} days.</strong>
                </p>
              @endif
            <p class="mb-6 text-center">Please confirm your payment.</p>
            <div class="flex flex-col justify-center gap-2">
                          <!-- {{$package}} -->
                    @if ($package == 'FC')
                        <form action="{{route('paymentfc')}}" method="POST" >
                    @else
                        <form action="{{route('paymentventuredeposits')}}" method="POST" >
                    @endif
                          @method("POST")
                    @csrf

                        <input type="hidden" name="amount" id="amount_to_invest" value="{{$amount}}">
                        <input type='hidden' name='package'  value='{{$venture->id}}'>
                        <input type='hidden' name='pack'  value='{{$name}}'>
                        <input type="hidden" name="package_type" value="VENTURE">
                        <input type='hidden' name='payment_method'  value='FROM_DEPOSITS'>
                        <input type="hidden" name="uvp_id" value="{{$venture->id}}"/>

                        <button type="submit" class="bg-blue-500 w-full text-white text-center px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            Confirm
                        </button>
                </form>
                <a href="/dashboard" class="bg-red-500 text-white text-center px-4 py-2 rounded-lg hover:bg-red-600 transition">Cancel</a>

                @if ($package == 'FC')

                 <div class="flex items-center justify-center">
                    @csrf

                    @method('POST')
                    <input type="hidden" name="package" value="{{$venture->id}}"/>
                    <input type="radio" name="option" value="crypto"  checked>


                    <a href="{{route($routes, $id)}}" class="text-blue-500 underline text-xs  capitalize hover:text-blue-600 font-semibold"> use crypto payment method </a>
                </div>
                @else

                <form action="{{route('paymentventure')}}" method="post" class="flex items-center justify-center">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="package" value="{{$venture->id}}"/>
                    <input type="radio" name="option" value="crypto"  checked>
                    <input type="hidden" name="package_type" value="VENTURE">
                    <input type="hidden" name="amount"
                    class="form-control-smaller  my-1 border p-1  w-100 mx-auto rounded"
                    value="{{$amount}}"
                    />
                    <button class="text-blue-500 underline text-xs  capitalize hover:text-blue-600 font-semibold" type="submit">
                        use crypto payment method
                    </button>
                </form>
                @endif


            </div>
        </div>
        @endif
    </div>
</body>
</html>

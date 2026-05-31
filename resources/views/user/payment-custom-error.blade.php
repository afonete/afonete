<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Minimum Invoice Amount for ETH & TRC20 Tokens</title>
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
<body class="bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-center text-gray-600 mb-6">Minimum Invoice Amount for ETH & TRC20 Tokens</h1>

        <!-- Content -->
        <h2 class="text-xl font-semibold text-gray-700 mb-4">New Minimum Invoice Amount</h2>
        <p class="text-gray-600 mb-4">
            The new minimum invoice amount for all ETH, ERC20, TRC20 and tokens is <mark> <strong class="text-black">$10</strong>.
            This is the smallest amount that can be transacted on these blockchains without significant losses.</mark>
        </p>
        <p class="text-gray-600">
            It means that you can create an invoice of any amount equivalent to <strong class="text-black">$10</strong>,
            or exceeding it at the current cryptocurrency exchange rate.
        </p>

        <div class="bg-red-100 py-2 px-3 rounded my-2">
            <p class="text-center font-bold text-red-500">
                Your payment is below the minimum required amount of <span class="text-black">$10</span>. Please adjust your payment to meet the minimum threshold.
            </p>

        </div>
        <button onclick="window.history.back()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            Back
        </button>
    </div>
</body>
</html>



<div class="wrapper">
     @include('user.user-dashboard-base')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="content-wrapper">


<title>Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>



    <style type="">
        /* Add any additional custom styles here */
    </style>

</head>
<body class="bg-gray-100">



        <!-- Main Content -->
        <div class="w-full p-4">


            {{-- Nav (shared Bootstrap finance navbar — Tailwind is not loaded on these pages) --}}
            @include('user.finance-nav', ['active' => 'transaction'])


            <div class="w-full bg-blue-100 rounded-xl p-3">
                <div>
                    <h1 class="flex items-baseline space-x-1"><span class="text-lg">{{$balance}}.00</span>
                         <span class="text-sm font-bold text-orange-600">$</span></h1>
                    <p class="text-gray-700 text-md">Total</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="bg-white rounded-lg p-3">
                        <h1 class="text-sm my-2 text-gray-700">Transfer user</h1>

                       <x-transfer/>


                    </div>

                    <div class="bg-white rounded-lg p-3">
                        <h1 class="text-sm my-2 text-gray-700">Withdraw (Processing time is up to one week)</h1>

                        <form action="">
                            <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
                                <div>
                                    <label for="" class="text-xs font-semibold text-gray-700">AMOUNT</label>
                                    <div>
                                        <input type="text" placeholder="$"
                                         class="w-full md:w-32 border-b-2 border-gray-700 focus:outline-none text-end"
                                         min="50"
                                         max="{{$balance}}"
                                         >
                                    </div>
                                </div>

                                <div>
                                    <label for="" class="text-xs font-semibold text-gray-700">USERNAME</label>
                                    <div>
                                        <select name="" id="" class="w-full md:w-auto text-sm border-b-2 border-gray-700 focus:outline-none">
                                            <option value="" selected disabled>Choose Account</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <button class="bg-orange-600 px-8 py-0.5 mt-4 focus:outline-none md:mt-6 text-white rounded-xl">Withdraw</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white p-3 my-5 rounded shadow-md">
                <h2 class="text-xl font-bold">History</h2>
                <div class="overflow-x-auto py-2">
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif


                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Sr. No</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Transaction No</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Daily</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Daily Vup</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Direct Bonus</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Volume Bonus</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Leadership Bonus</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Total</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Credit</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Cash (25%)</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Trading Voucher (75%)</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Sender Username</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Sender ID</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Transaction Type</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Amount</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Description</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Status</th>
                                <th class="px-4 py-1 font-medium border-b  text-blue-900">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            use Carbon\Carbon;
                        @endphp


                            @foreach ( $trns as $trx)
                                @php
                                    $data = json_decode($trx->transaction_details);
                                    $formattedDate = Carbon::parse($data->date)->format('F j, Y, g:i A');

                                @endphp
                                <tr>
                                    <td class="border-b px-4 py-2">{{$loop->iteration}}</td>
                                    <td class="border-b px-4 py-2">{{$trx->transaction_no}}</td>
                                    <td class="border-b px-4 py-2">{{$formattedDate}}</td>
                                    <td class="border-b px-4 py-2">{{$data->daily_vup}}</td>
                                    <td class="border-b px-4 py-2">{{$data->direct_bonus}}</td>
                                    <td class="border-b px-4 py-2">{{$data->volume_bonus}}</td>
                                    <td class="border-b px-4 py-2">{{$data->leader_bonus}}</td>
                                    <td class="border-b px-4 py-2">{{$data->total}}</td>
                                    <td class="border-b px-4 py-2">{{$data->credit}}</td>
                                    <td class="border-b px-4 py-2">{{$data->cash}}</td>
                                    <td class="border-b px-4 py-2">{{$data->trx_voucher}}</td>

                                    <td class="border-b px-4 py-2">{{$data->sendername}}</td>
                                    <td class="border-b px-4 py-2">{{$data->sender}}</td>
                                    <td class="border-b px-4 py-2">{{$data->trx_type}}</td>
                                    <td class="border-b px-4 py-2">{{$data->amount}}</td>
                                    <td class="border-b px-4 py-2">{{$data->to_sender}}</td>
                                    <td class="border-b px-4 py-2">{{$data->status}}</td>
                                    <td class="border-b px-4 py-2">
                                        <button class="bg-orange-500 text-white px-4 py-0.5 rounded">Details</button>
                                    </td>
                                </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



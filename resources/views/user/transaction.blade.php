

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


            {{-- Nav (shared Bootstrap finance navbar --}}
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
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <h2 class="text-xl font-bold mb-0">History</h2>
                    <span class="badge badge-secondary font-weight-bold px-3 py-2" style="font-size:0.85rem;">
                        {{ $trns->total() }} record{{ $trns->total() !== 1 ? 's' : '' }} · 10 per page
                    </span>
                </div>
                <div class="overflow-x-auto py-2">
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif


                    <table class="table table-bordered table-hover table-sm w-full" style="font-size: 0.82rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Sr. No</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Transaction No</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Date</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Direction</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Daily VUP</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Direct Bonus</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Volume Bonus</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Leader Bonus</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900 text-right">Total</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Credit</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900 text-right">Cash (25%)</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900 text-right">Trading Voucher (75%)</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Counterparty</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Sender ID</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Tx Type</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900 text-right">Amount</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900">Description</th>
                                <th class="px-2 py-2 font-weight-bold text-blue-900 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trns as $idx => $trx)
                                @php
                                    $rowNo = ($trns->currentPage() - 1) * $trns->perPage() + $idx + 1;
                                @endphp
                                <tr>
                                    <td class="border-b px-2 py-2 text-muted font-weight-bold">{{ $rowNo }}</td>
                                    <td class="border-b px-2 py-2 font-mono font-weight-bold text-primary small">{{ $trx->transaction_no ?: '#'.$trx->id }}</td>
                                    <td class="border-b px-2 py-2 text-nowrap small">{{ $trx->d_date }}</td>
                                    <td class="border-b px-2 py-2 text-center">
                                        @if($trx->d_direction === 'sent')
                                            <span class="badge badge-danger px-2 py-1" style="font-size:0.7rem;">SENT</span>
                                        @else
                                            <span class="badge badge-success px-2 py-1" style="font-size:0.7rem;">RECEIVED</span>
                                        @endif
                                    </td>
                                    <td class="border-b px-2 py-2">{{ $trx->d_daily_vup }}</td>
                                    <td class="border-b px-2 py-2">{{ $trx->d_direct }}</td>
                                    <td class="border-b px-2 py-2">{{ $trx->d_volume }}</td>
                                    <td class="border-b px-2 py-2">{{ $trx->d_leader }}</td>
                                    <td class="border-b px-2 py-2 text-right font-weight-bold">{{ $trx->d_total }}</td>
                                    <td class="border-b px-2 py-2">{{ $trx->d_credit }}</td>
                                    <td class="border-b px-2 py-2 text-right">{{ $trx->d_cash }}</td>
                                    <td class="border-b px-2 py-2 text-right">{{ $trx->d_trading }}</td>
                                    <td class="border-b px-2 py-2 small font-weight-bold">{{ $trx->d_sendername }}</td>
                                    <td class="border-b px-2 py-2 small">{{ $trx->d_sender }}</td>
                                    <td class="border-b px-2 py-2">
                                        <span class="badge badge-info text-white px-2 py-1" style="font-size:0.7rem;">{{ ucwords(str_replace(['_','-'],' ', $trx->d_trx_type)) }}</span>
                                    </td>
                                    <td class="border-b px-2 py-2 text-right font-weight-bold text-success">${{ number_format((float) $trx->d_amount, 4) }}</td>
                                    <td class="border-b px-2 py-2 small">{{ $trx->d_description }}</td>
                                    <td class="border-b px-2 py-2 text-center">
                                        @php $st = strtolower((string) $trx->d_status); @endphp
                                        @if($st === 'success' || $st === 'completed' || $st === 'approved')
                                            <span class="badge badge-success">Success</span>
                                        @elseif($st === 'pending')
                                            <span class="badge badge-warning text-dark">Pending</span>
                                        @elseif($st === 'failed' || $st === 'rejected')
                                            <span class="badge badge-danger">{{ ucfirst($st) }}</span>
                                        @else
                                            <span class="badge badge-light border">{{ ucfirst($st ?: 'OK') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="18" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox text-3xl mb-2 d-block text-gray-300"></i>
                                        No transactions recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($trns->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $trns->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>



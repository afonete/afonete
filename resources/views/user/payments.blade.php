<div class="wrapper">
    @include('user.user-dashboard-base')
    <title>Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
   <div class="content-wrapper">
<head>
<script src="https://cdn.jsdelivr.net/npm/gojs/release/go.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
      )
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
      )
    window.Promise ||
      document.write(
        '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
      )

      </script>
        <script>
            // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
            // Based on https://gist.github.com/blixt/f17b47c62508be59987b
            var _seed = 42;
            Math.random = function() {
              _seed = _seed * 16807 % 2147483647;
              return (_seed - 1) / 2147483646;
            };
          </script>

      <script>
        var lastDate = 0;
        var data = []
        var TICKINTERVAL = 86400000
        let XAXISRANGE = 777600000
        function getDayWiseTimeSeries(baseval, count, yrange) {
          var i = 0;
          while (i < count) {
            var x = baseval;
            var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

            data.push({
              x, y
            });
            lastDate = baseval
            baseval += TICKINTERVAL;
            i++;
          }
        }

        getDayWiseTimeSeries(new Date('11 Feb 2017 GMT').getTime(), 10, {
          min: 10,
          max: 90
        })

        function getNewSeries(baseval, yrange) {
          var newDate = baseval + TICKINTERVAL;
          lastDate = newDate

          for(var i = 0; i< data.length - 10; i++) {
            // IMPORTANT
            // we reset the x and y of the data which is out of drawing area
            // to prevent memory leaks
            data[i].x = newDate - XAXISRANGE - TICKINTERVAL
            data[i].y = 0
          }

          data.push({
            x: newDate,
            y: Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
          })
        }

        function resetData(){
          // Alternatively, you can also reset the data at certain intervals to prevent creating a huge series
          data = data.slice(data.length - 10, data.length);
        }
        </script>
</head>
<body>

   <div class="container-fluid py-4 max-w-7xl mx-auto">

       {{-- Top sub-navigation: Cash / Coin / Trading / Invoices (identical bar across Cash & Coin pages) --}}
       <div class="mb-4">
           <x-PaymentNav/>
       </div>

        <h4 class="px-2 flex gap-2"><span id="acc">CASHOUT</span> <span>ACCOUNT</span></h4>
        <div class="border px-4 py-4 rounded-lg my-4 bg-white shadow-sm">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <select id="acc_choice" class="form-select block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm font-semibold">
                        <option value="" disabled selected>CHOOSE ACCOUNT</option>
                        <option value="Cashout Wallet">Cashout Wallet</option>
                        <option value="Purchase Wallet">Purchase Wallet</option>
                        <option value="Reward Wallet">Reward Wallet</option>
                        <option value="Deposit Wallet">Deposit Wallet</option>
                        <option value="FOMO Wallet">FOMO Wallet</option>
                        <option value="Trading Wallet">Trading Wallet</option>
                    </select>
                </div>
                <div class="text-right">

                    <h1 class="text-gray-900 font-extrabold text-2xl" id="balance">${{ number_format($cashout, 2) }}</h1>
                    <p class="text-gray-500 font-bold text-xs uppercase">TOTAL BALANCE</p>
                    <h4 class="text-2xl text-yellow-600 font-bold"></h4>
                </div>
            </div>
        </div>


        <div class="block sm:grid grid-cols-2 my-2 gap-3">

            {{-- 1. TRANSFER TO USER --}}
            <div class="bg-white p-5 flex flex-col rounded-xl my-2 sm:my-0 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-extrabold text-gray-800 uppercase text-base flex items-center gap-2">
                            <i class="fa fa-paper-plane text-yellow-500"></i> TRANSFER TO USER
                        </h2>
                        <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">User-to-User</span>
                    </div>
                    <p class="text-gray-500 text-xs mb-3">Transfer Cashout USD to another member using their Username or 7-Digit Transfer Code.</p>

                    @if(session('success-touser'))
                        <div class="bg-emerald-500 text-white font-bold rounded-lg px-4 py-2.5 mb-3 text-xs shadow-sm flex items-center justify-between" role="alert">
                            <span><i class="fa fa-check-circle mr-1.5"></i> {{ session('success-touser') }}</span>
                        </div>
                    @endif

                    @if(session('error-touser'))
                        <div class="bg-rose-500 text-white font-bold rounded-lg px-4 py-2.5 mb-3 text-xs shadow-sm flex items-center justify-between" role="alert">
                            <span><i class="fa fa-exclamation-triangle mr-1.5"></i> {{ session('error-touser') }}</span>
                        </div>
                    @endif

                    <hr class="mb-3 border-gray-100"/>

                    <form class="flex flex-col gap-3 js-transaction-password-form" method="POST" action="{{ route('transfer-touser') }}" id="cashoutTransferForm">
                        @csrf
                        <input type="hidden" name="from_account" value="CASHOUT">
                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Recipient Username or 7-Digit Transfer Code <span class="text-red-500">*</span></label>
                            <div class="border border-gray-300 rounded-lg flex overflow-hidden focus-within:ring-2 focus-within:ring-yellow-500 focus-within:border-yellow-500">
                                <input type="text" name="recipient" id="recipientInput"
                                       placeholder="Username or Transfer Code..."
                                       value="{{ old('recipient') }}"
                                       class="px-3 py-2 w-full focus:outline-none text-xs font-semibold text-gray-800" required>
                                <button type="button" id="lookupBtn"
                                        class="bg-gray-800 hover:bg-gray-900 px-4 flex items-center text-white text-xs font-bold transition flex-shrink-0">
                                    <i class="fa fa-search mr-1.5"></i> Find
                                </button>
                            </div>
                        </div>

                        {{-- Name preview (shown after AJAX lookup) --}}
                        <div id="recipientPreview" class="hidden bg-emerald-50 border border-emerald-300 text-emerald-900 px-2.5 py-1 rounded-md text-[11px] font-semibold shadow-sm">
                            <div class="flex items-center justify-between gap-1.5 overflow-hidden whitespace-nowrap">
                                <div class="flex items-center gap-1 overflow-hidden truncate">
                                    <span class="text-emerald-600 font-bold text-xs">&check;</span>
                                    <span class="text-emerald-800">Sending to:</span>
                                    <span id="recipientName" class="font-bold text-emerald-950 truncate"></span>
                                </div>
                                <div class="flex items-center gap-1 font-mono text-[10px] text-emerald-900 flex-shrink-0">
                                    <span class="bg-emerald-200/70 px-1.5 py-0.5 rounded font-bold">@<span id="recipientUsernameShown"></span></span>
                                    <span class="bg-emerald-200/70 px-1.5 py-0.5 rounded font-bold">Code: <span id="recipientCodeShown"></span></span>
                                </div>
                            </div>
                        </div>
                        <div id="recipientError" class="hidden bg-rose-50 border border-rose-300 text-rose-800 p-2.5 rounded-lg text-xs font-bold">
                            <i class="fa fa-times-circle mr-1 text-rose-600"></i> <span id="recipientErrorMsg"></span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Amount (USD) <span class="text-red-500">*</span></label>
                            <div class="border border-gray-300 rounded-lg flex overflow-hidden focus-within:ring-2 focus-within:ring-yellow-500 focus-within:border-yellow-500">
                                <input type="number" name="amount" placeholder="Amount (min $1.00)..."
                                       class="px-3 py-2 w-full focus:outline-none text-xs font-semibold text-gray-800"
                                       max="{{ $cashout }}" min="1.00" step="0.01" required>
                                <div class="bg-gray-800 px-3 flex items-center text-white font-bold text-xs">$</div>
                            </div>
                        </div>

                        <div class="pt-1">
                            <button type="submit" id="sendBtn"
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 px-4 py-2.5 rounded-lg text-white font-bold shadow-sm transition text-xs flex items-center justify-center gap-1.5">
                                <i class="fa fa-paper-plane"></i> Send Cashout USD
                            </button>
                        </div>

                        <div class="text-right">
                            <span class="text-gray-500 font-semibold text-[11px]">
                                Available Cashout: <strong class="text-gray-800">${{ number_format($cashout, 2) }}</strong>
                            </span>
                        </div>
                    </form>

                    <script>
                    (function () {
                        const lookupBtn        = document.getElementById('lookupBtn');
                        const recipientInput   = document.getElementById('recipientInput');
                        const preview          = document.getElementById('recipientPreview');
                        const errorBox         = document.getElementById('recipientError');
                        const errorMsg         = document.getElementById('recipientErrorMsg');
                        const nameEl           = document.getElementById('recipientName');
                        const usernameShown    = document.getElementById('recipientUsernameShown');
                        const codeShown        = document.getElementById('recipientCodeShown');

                        function hideBoth() {
                            preview.classList.add('hidden');
                            errorBox.classList.add('hidden');
                        }

                        if (lookupBtn && recipientInput) {
                            lookupBtn.addEventListener('click', function () {
                                const q = recipientInput.value.trim();
                                if (!q) {
                                    errorMsg.textContent = 'Please enter Username or 7-Digit Transfer Code.';
                                    errorBox.classList.remove('hidden');
                                    preview.classList.add('hidden');
                                    return;
                                }
                                fetch('{{ route("transfer-touser.lookup") }}?query=' + encodeURIComponent(q))
                                    .then(r => r.json())
                                    .then(data => {
                                        if (data.found) {
                                            nameEl.textContent        = data.name;
                                            usernameShown.textContent = data.username;
                                            codeShown.textContent     = data.transfer_code;
                                            preview.classList.remove('hidden');
                                            errorBox.classList.add('hidden');
                                        } else {
                                            errorMsg.textContent = data.message || 'No member found.';
                                            errorBox.classList.remove('hidden');
                                            preview.classList.add('hidden');
                                        }
                                    })
                                    .catch(() => {
                                        errorMsg.textContent = 'Lookup failed. Please check connection.';
                                        errorBox.classList.remove('hidden');
                                        preview.classList.add('hidden');
                                    });
                            });

                            recipientInput.addEventListener('input', hideBoth);
                        }
                    })();
                    </script>

            </div>

            {{-- 2. TRANSFER BETWEEN YOUR INTERNAL WALLETS --}}
            <div class="bg-white p-5 flex flex-col rounded-xl my-2 sm:my-0 shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="font-extrabold text-gray-800 uppercase text-base flex items-center gap-2">
                        <i class="fa fa-refresh text-yellow-500"></i> TRANSFER BETWEEN YOUR INTERNAL WALLETS
                    </h2>
                    <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Internal Exchange</span>
                </div>
                <p class="text-gray-500 text-xs mb-3">Select source and destination wallets according to internal exchange rules.</p>

                @if(session('success-internal'))
                    <div class="bg-emerald-500 text-white font-bold rounded-lg px-4 py-2.5 mb-3 text-xs shadow-sm" role="alert">
                        <i class="fa fa-check-circle mr-1.5"></i> {{ session('success-internal') }}
                    </div>
                @endif

                @if(session('error-internal'))
                    <div class="bg-rose-500 text-white font-bold rounded-lg px-4 py-2.5 mb-3 text-xs shadow-sm" role="alert">
                        <i class="fa fa-exclamation-triangle mr-1.5"></i> {{ session('error-internal') }}
                    </div>
                @endif

                <hr class="mb-3 border-gray-100"/>

                <form class="flex flex-col gap-3 js-transaction-password-form" method="POST" action="{{ route('user.internal-exchange.transfer') }}" id="internalWalletTransferForm">
                    @csrf
                    <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">From Wallet (Source) <span class="text-red-500">*</span></label>
                        <select name="from_wallet" id="paymentsFromWallet" required onchange="updatePaymentsDestOptions()" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="" disabled selected>-- Select Source Wallet --</option>
                            <option value="CASHOUT">Cashout Wallet (${{ number_format($cashout, 2) }})</option>
                            <option value="REWARD">Reward Wallet</option>
                            <option value="DEPOSIT">Deposit Wallet</option>
                            <option value="FOMO">Fomo Wallet</option>
                            <option value="PURCHASE">Purchase Wallet</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">To Wallet (Destination) <span class="text-red-500">*</span></label>
                        <select name="to_wallet" id="paymentsToWallet" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="" disabled selected>-- Select Destination Wallet --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Transfer Amount (USD) <span class="text-red-500">*</span></label>
                        <div class="border border-gray-300 rounded-lg flex overflow-hidden focus-within:ring-2 focus-within:ring-yellow-500 focus-within:border-yellow-500">
                            <input type="number" name="amount" placeholder="Amount (USD)..." min="0.01" step="0.01" required class="px-3 py-2 w-full focus:outline-none text-xs font-semibold text-gray-800">
                            <div class="bg-gray-800 px-3 flex items-center text-white font-bold text-xs">$</div>
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 px-4 py-2.5 rounded-lg text-white font-bold shadow-sm transition text-xs flex items-center justify-center gap-1.5">
                            <i class="fa fa-refresh"></i> Execute Wallet Transfer
                        </button>
                    </div>
                </form>
            </div>

            {{-- 3. TRANSFER TO TRADING ACCOUNT --}}
            <div class="bg-white p-5 flex flex-col justify-between rounded-xl my-2 sm:my-0 shadow-sm border border-gray-200">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="font-extrabold text-gray-800 uppercase text-base flex items-center gap-2">
                            <i class="fa fa-line-chart text-yellow-500"></i> TRANSFER TO TRADING ACCOUNT
                        </h2>
                        <span class="bg-purple-100 text-purple-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Trading Exchange</span>
                    </div>
                    <p class="text-gray-500 text-xs mb-3">Swap or buy tokens using your Trading Wallet on the Internal Exchange portal.</p>
                    <hr class="mb-3 border-gray-100"/>
                </div>

                <div class="pt-2">
                    <a href="{{ route('user.dashboard.payments') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 px-4 py-2.5 rounded-lg text-white font-bold shadow-sm transition text-xs flex items-center justify-center gap-1.5 text-center block" style="text-decoration: none;">
                        <i class="fa fa-arrow-right"></i> TRANSFER TO TRADING ACCOUNT &rarr;
                    </a>
                </div>
            </div>


            <div class="bg-white p-4 flex flex-col rounded-lg my-2 sm:my-0">
                <h2>WITHDRAW (Processing Time Is Up To One Week)</h2>
                @if(session('success-trx'))
                    <div class="bg-green-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        {{ session('success-trx') }}
                    </div>
                @endif

                @if(session('error-trx'))
                    <div class="bg-red-500 text-white font-bold rounded-lg px-4 py-3 mb-4" role="alert">
                        {{ session('error-trx') }}
                    </div>
                @endif

                <hr/>
                <form class="flex gap-2  flex-wrap js-transaction-password-form" method="POST" action="{{route('transferToAccount')}}" id="transferForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                    <div class="border-2 flex justify-center  rounded-lg p-0">
                        <input type="number" name="amount" placeholder="Amount.." class="px-2 py-2 "
                         max="{{$cashout}}" min="1.00" step="0.01" required/>
                        <div class="bg-gray-800  px-2 flex  items-center text-white" >$</div>
                    </div>

                    <input type="hidden" name="from_account" class="from_account" value="CASHOUT">

                    <div class="border  flex justify-center items-center rounded ">
                        <select class="border-none outline-none focus:outline-none" name="account" id="receiver_acc">

                            <option>CHOOSE ACCOUNT</option>
                            <option value="REDEEM_PACKAGE">Redeem Package</option>
                            <option value="UPGRADE">Upgrade</option>
                            <option value="PAID_ADS">Paid Ads</option>
                            <option value="USDT_TRON">USDT TRON</option>
                            <option value="PERFECT_MONEY">Perfect Money</option>
                            <option value="VOLE">Vole</option>

                        </select>
                    </div>
                    <div>
                        <button class="bg-yellow-500">Send</button>
                    </div>
                </form>



            </div>

        {{-- TRANSFER HISTORY SECTION --}}
        <div class="my-4 p-5 bg-white rounded-xl shadow-sm border border-gray-200 col-span-2">
            <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-3">
                <div>
                    <h3 class="font-extrabold text-gray-800 text-base uppercase flex items-center gap-2">
                        <i class="fa fa-history text-yellow-500"></i> TRANSFER HISTORY
                    </h3>
                    <p class="text-gray-500 text-xs mt-0.5">Showing recent User-to-User &amp; Internal Wallet Transfers.</p>
                </div>
                <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">5 Records Per Page</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 text-gray-800 uppercase font-bold border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Transaction No</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Details</th>
                            <th class="py-3 px-4 text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @if(isset($history) && $history->count() > 0)
                            @foreach($history as $i => $t)
                                @php
                                    $d = json_decode($t->transaction_details, true) ?: [];
                                    $displayNames = [
                                        'CASHOUT'        => 'Cashout Wallet',
                                        'REWARD'         => 'Reward Wallet',
                                        'DEPOSIT'        => 'Deposit Wallet',
                                        'FOMO'           => 'Fomo Wallet',
                                        'TRADING_WALLET' => 'Trading Wallet',
                                        'PURCHASE'       => 'Purchase Wallet',
                                    ];
                                    $fromName = $displayNames[$d['from_wallet'] ?? ''] ?? ($d['from_wallet'] ?? '');
                                    $toName   = $displayNames[$d['to_wallet'] ?? ''] ?? ($d['to_wallet'] ?? '');
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3.5 px-4 font-bold text-gray-500">{{ method_exists($history, 'currentPage') ? (($history->currentPage() - 1) * $history->perPage() + $loop->iteration) : $loop->iteration }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-gray-900">{{ $t->transaction_no }}</td>
                                    <td class="py-3.5 px-4">
                                        @if($t->transaction_type === 'CASHOUT_TRANSFER_SENT')
                                            <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-[10px] font-bold">SENT</span>
                                        @elseif($t->transaction_type === 'CASHOUT_TRANSFER_RECEIVED')
                                            <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-bold">RECEIVED</span>
                                        @elseif($t->transaction_type === 'INTERNAL_WALLET_TRANSFER')
                                            <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded text-[10px] font-bold">INTERNAL</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-[10px] font-bold">{{ $t->transaction_type }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-medium">
                                        @if(isset($d['from_wallet']) && isset($d['to_wallet']))
                                            Transferred <strong>${{ number_format($d['amount'] ?? 0, 2) }}</strong> from {{ $fromName }} to {{ $toName }}
                                        @elseif($t->transaction_type === 'CASHOUT_TRANSFER_SENT' || !empty($d['to_username']) || !empty($d['to_user']))
                                            Sent <strong>${{ number_format($d['amount'] ?? 0, 2) }}</strong> to @ {{ $d['to_username'] ?? ($d['to_user'] ?? '—') }} (Code: {{ $d['to_code'] ?? '—' }})
                                        @elseif($t->transaction_type === 'CASHOUT_TRANSFER_RECEIVED' || !empty($d['from_username']) || !empty($d['from_user']))
                                            Received <strong>${{ number_format($d['amount'] ?? 0, 2) }}</strong> from @ {{ $d['from_username'] ?? ($d['from_user'] ?? '—') }} (Code: {{ $d['from_code'] ?? '—' }})
                                        @else
                                            {{ $t->transaction_details }}
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono text-gray-500">
                                        {{ $t->created_at ? $t->created_at->format('Y-m-d H:i') : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 font-semibold">
                                    <i class="fa fa-folder-open text-2xl mb-1 block text-gray-300"></i>
                                    No transfer history recorded yet.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($history) && method_exists($history, 'hasPages') && $history->hasPages())
                <div class="mt-4 flex justify-center text-xs font-semibold">
                    {{ $history->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>

        {{-- CONTENTS BELOW HISTORY (RESTORED) --}}
        <div class="my-3 p-4 flex flex-col rounded-lg col-span-2">
            <h4 class="text-cyan-500 py-2 text-md font-semibold">Don't sell your token before going public exchange please</h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                {{-- buy coin --}}
                <div class="bg-white py-3 px-2 rounded border shadow-sm">
                   <form>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border-b pb-3 text-left" colspan="2">
                                    <h1 class="text-left uppercase font-bold text-gray-800">Buy Coin</h1>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3 px-2 text-xs font-bold">Quantity *: </td>
                                <td class="py-3 px-2"><input type="number" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full text-xs"/></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-2 text-xs font-bold">Price soon:</td>
                                <td class="py-3 px-2"><input type="number" name="price" step="0.01" placeholder="Price " class="py-2 px-2 border focus:outline-purple-500 rounded w-full text-xs"/></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-2 text-xs font-bold"> Fees: </td>
                                <td class="py-3 px-2 text-xs font-bold">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-2 text-xs font-bold">Total: </td>
                                <td class="py-3 px-2 text-xs font-bold">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                            </tr>
                            <tr>
                                <td class="py-3 px-2"></td>
                                <td class="py-3 px-2">
                                    <button type="button" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded text-xs">Buy</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                   </form>
                </div>

                {{-- sell coin --}}
                <div class="bg-white py-3 px-2 rounded border shadow-sm">
                  <div class="flex gap-2 items-center justify-between mb-2">
                    <div>
                      <h5 class="text-left uppercase font-bold text-gray-800 text-xs">Sell With buy pack program</h5>
                    </div>
                    <a class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-center rounded text-xs font-bold" href="#">READ MORE</a>
                  </div>

                    <form>
                     <table class="w-full">
                         <thead>
                             <tr>
                                 <th class="border-b pb-3 text-left" colspan="2">
                                     <h1 class="text-left uppercase font-bold text-gray-800">Sell Coin </h1>
                                 </th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td class="py-3 px-2 text-xs font-bold">Quantity *: </td>
                                 <td class="py-3 px-2"><input type="number" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full text-xs"/></td>
                             </tr>
                             <tr>
                                 <td class="py-3 px-2 text-xs font-bold">Price 0.0004:</td>
                                 <td class="py-3 px-2"><input type="number" name="price" step="0.01" placeholder="Price" class="py-2 px-2 border focus:outline-purple-500 rounded w-full text-xs"/></td>
                             </tr>
                             <tr>
                                 <td class="py-3 px-2 text-xs font-bold"> Fees: </td>
                                 <td class="py-3 px-2 text-xs font-bold">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                             </tr>
                             <tr>
                                 <td class="py-3 px-2 text-xs font-bold">Total: </td>
                                 <td class="py-3 px-2 text-xs font-bold">0.00<input type="hidden" step="0.01" placeholder="Quantity" class="py-2 px-2 border focus:outline-purple-500 rounded w-full"/></td>
                             </tr>
                             <tr>
                                 <td class="py-3 px-2"></td>
                                 <td class="py-3 px-2">
                                     <button type="button" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded text-xs">Sell</button>
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                    </form>
                </div>

                {{-- chart --}}
                <div class="bg-white py-3 px-2 rounded border shadow-sm">
                      <div id="chart"></div>
                </div>

                <div class="right-side">
                    <div class="flex flex-col gap-3">
                        <div class="bg-gray-800 px-3 py-4 rounded flex flex-col gap-2 shadow-sm">
                            <h1 class="text-red-400 font-bold text-xs uppercase">INTERNAL BUY PACKAGE PRICE: 0.004</h1>
                            <h1 class="text-red-400 font-bold text-xs uppercase">LIST PUBLIC EXCHANGE PRICE: 0.125</h1>
                        </div>

                        <div class="py-3 px-3 border rounded bg-white shadow-sm">
                            <h1 class="text-red-600 py-2 font-extrabold text-xs uppercase">TARGET EXCHANGE</h1>

                            <div class="grid grid-cols-4 gap-2">
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/pancake.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/okx.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/exchange.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/kucoin.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/yo.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/bybit.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/binance.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                                <div class="py-3 px-2 text-center rounded block">
                                    <img src="{{ asset("image/bitcoin.jpeg") }}" style="width:50px;height:50px;object-fit:cover" class="rounded-full mx-auto shadow-sm border">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        </div>

       </div>
     </div>

     @include('user.components.transaction-password-modal')

     <script>

                    const acc_choice = document.querySelector("#acc_choice")
                    const receiver_acc = document.querySelector("#receiver_acc")
                    const title = document.querySelector("#acc")
                    const balance = document.querySelector("#balance")
                    const accounts = [
                                    {name:'REDEEM_PACKAGE',title:'Redeem Package'},
                                    {name:'UPGRADE',title:'Upgrade'},
                                    {name:'PAID_ADS',title:'Paid Ads'},
                                    {name:'USDT_TRON',title:'USDT TRON'},
                                    {name:'PERFECT_MONEY',title:'Perfect Money'},
                                    {name:'VOLE',title:'Vole'}
                    ]

                    const from_accounts = document.querySelectorAll(".from_account")

                    if (acc_choice) {
                        acc_choice.addEventListener("change", function(e) {
                            const selected = e.target.value;
                            if (!selected || selected === 'CHOOSE ACCOUNT') return;

                            if (title) title.innerHTML = selected.toUpperCase();
                            balance.innerHTML = '$0.00';

                            fetch('{{ url("user/dashboard/finance/account") }}?ac=' + encodeURIComponent(selected))
                                .then((response) => response.json())
                                .then((data) => {
                                    if (balance && data && data.balance !== undefined) {
                                        balance.innerHTML = '$' + data.balance;
                                    }
                                    Array.from(from_accounts).forEach((el) => {
                                        el.value = selected;
                                    });
                                })
                                .catch((err) => {
                                    console.log(err);
                                    balance.innerHTML = '$0.00';
                                });
                        });
                    }

                    const paymentsWalletMatrix = {
                        'CASHOUT':    [ { code: 'REWARD', name: 'Reward Wallet' }, { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
                        'REWARD':     [ { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
                        'DEPOSIT':    [ { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
                        'FOMO':       [ { code: 'DEPOSIT', name: 'Deposit Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' }, { code: 'PURCHASE', name: 'Purchase Wallet' } ],
                        'PURCHASE':   [ { code: 'FOMO', name: 'Fomo Wallet' }, { code: 'TRADING_WALLET', name: 'Trading Wallet' } ]
                    };

                    function updatePaymentsDestOptions() {
                        const fromSelect = document.getElementById('paymentsFromWallet');
                        const toSelect   = document.getElementById('paymentsToWallet');
                        if (!fromSelect || !toSelect) return;

                        const fromCode = fromSelect.value;
                        toSelect.innerHTML = '<option value="" disabled selected>-- Select Destination Wallet --</option>';

                        if (!fromCode || !paymentsWalletMatrix[fromCode]) return;

                        paymentsWalletMatrix[fromCode].forEach(function(item) {
                            const opt = document.createElement('option');
                            opt.value = item.code;
                            opt.textContent = item.name + ' (' + item.code + ')';
                            toSelect.appendChild(opt);
                        });
                    }



      var options = {
        series: [{
        data: data.slice()
      }],
        chart: {
        id: 'realtime',
        height: 350,
        type: 'line',
        animations: {
          enabled: true,
          easing: 'linear',
          dynamicAnimation: {
            speed: 1000
          }
        },
        toolbar: {
          show: false
        },
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },
      title: {
        text: 'MARKET DEPTH',
        align: 'left'
      },
      markers: {
        size: 0
      },
      xaxis: {
        type: 'datetime',
        range: XAXISRANGE,
      },
      yaxis: {
        max: 100
      },
      legend: {
        show: false
      },
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();


      var intervalRuns = 0;
    var interval = window.setInterval(function () {
      intervalRuns++
      getNewSeries(lastDate, {
        min: 10,
        max: 90
      })

      chart.updateSeries([{
        data: data
      }])

      if (intervalRuns === 2 && window.isATest === true) {
        clearInterval(interval)
      }
    }, 1000)

  </script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <script>

    document.addEventListener("DOMContentLoaded",function(){
        // const Swal = new sweetalert2()

    const transferForm = document.querySelector("#transferForm")


// transferForm.addEventListener("submit",function(e){
//     e.preventDefault()
//     Toastify({
//   text: "This is a toast with offset",
//   newWindow: true,
//   offset: {
//     x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
//     y: 10 // vertical axis - can be a number or a string indicating unity. eg: '2em'
//   },
// }).showToast();
// })
// console.log(sweetalert2)



    })
  </script>
</body>
</div>
</div>

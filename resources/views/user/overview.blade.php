
<div class="wrapper">
     @include('user.user-dashboard-base')
     <title>Overview</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>
    <div class="content-wrapper">
            <div class="w-full p-4">

            {{-- Nav (shared Bootstrap finance navbar — Tailwind loaded via CDN above) --}}
            @include('user.finance-nav', ['active' => 'overview'])

            <div class="w-full bg-blue-100 rounded-xl p-3">
                <div>
                    <h1 class="flex items-baseline space-x-1"><span class="text-lg">{{$balance}}</span> <span class="text-sm font-bold text-orange-600">$</span></h1>
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
                                        <input type="text" placeholder="$" class="w-full md:w-32 border-b-2 border-gray-700 focus:outline-none text-end">
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

            <div class="bg-white p-4 my-10 rounded shadow-md">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <h2 class="text-xl font-bold mb-0">History</h2>
                    <span class="badge badge-secondary font-weight-bold px-3 py-2" style="font-size:0.85rem;">
                        {{ $transaction->total() }} record{{ $transaction->total() !== 1 ? 's' : '' }} · 10 per page
                    </span>
                </div>
                <div class="overflow-x-auto">
                        <table class="table-auto w-full mt-4">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4  text-blue-900 font-medium py-2">Amount</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Type Income</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">VUP Income</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Date</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Cash 25%</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Trading Voucher 75%</th>
                                    <th class="px-4  text-blue-900 font-medium py-2">Description</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Transaction Type</th>
                                    <th class="px-4  text-blue-900 font-medium py-2"><i class="fa-solid fa-sort"></i> Transaction Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    use Carbon\Carbon;
                                @endphp
                                @foreach ($transaction as $trx )
                                    @php
                                        $data = json_decode($trx->transaction_details);
                                        $rawDate = $data->date ?? $trx->created_at ?? null;
                                        $formattedDate = $rawDate ? Carbon::parse($rawDate)->format('F j, Y, g:i A') : '—';
                                        $amount = $data->amount ?? 0;
                                        $type = $data->type ?? $trx->transaction_type;
                                        $cash25 = $data->cash_25 ?? 0;
                                        $trading75 = $data->trading_75 ?? 0;
                                        $desc = $data->description ?? '—';
                                        $status = $data->status ?? '—';
                                    @endphp
                                <tr>
                                    <td class="border-b px-4 py-2 text-green-600">{{ $amount }} $</td>
                                    <td class="border-b px-4 py-2">{{ $type }}</td>
                                    <td class="border-b px-4 py-2">{{ $amount }} $</td>
                                    <td class="border-b px-4 py-2">{{ $formattedDate }}</td>
                                    <td class="border-b px-4 py-2">{{ $cash25 }} $</td>
                                    <td class="border-b px-4 py-2">{{ $trading75 }} $</td>
                                    <td class="border-b px-4 py-2">{{ $desc }}</td>
                                    <td class="border-b px-4 py-2">{{ $trx->transaction_type }}</td>
                                    <td class="border-b px-4 py-2">{{ $status }}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (10 per page) --}}
                    @if($transaction->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transaction->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- ────────────────────────────────────────────────────────────── --}}
            {{-- COMMISSION TABLE — all system-generated commissions            --}}
            {{-- ────────────────────────────────────────────────────────────── --}}
            <div id="commissions" class="bg-white p-4 my-6 rounded shadow-md">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <div>
                        <h2 class="text-xl font-bold mb-0">
                            <i class="fas fa-coins text-warning mr-2"></i> Commission
                        </h2>
                        <p class="text-muted small mb-0">
                            Every system-generated commission earned on your account
                            (referral, volume, leader, FC leadership, streamline-rank, FOM incentives, etc).
                        </p>
                    </div>
                    <span class="badge badge-info font-weight-bold px-3 py-2" style="font-size:0.85rem;">
                        {{ $commissions->total() }} record{{ $commissions->total() !== 1 ? 's' : '' }} · 10 per page
                    </span>
                </div>

                @if($commissions->count() === 0)
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-inbox text-4xl mb-2 d-block text-gray-300"></i>
                        No commission earnings recorded yet.
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="table table-hover table-bordered mb-0" style="font-size: 0.88rem;">
                        <thead class="bg-light">
                            <tr>
                                <th class="font-weight-bold">#</th>
                                <th class="font-weight-bold">Transaction No.</th>
                                <th class="font-weight-bold">Date &amp; Time</th>
                                <th class="font-weight-bold">Commission Type</th>
                                <th class="font-weight-bold">Source User</th>
                                <th class="font-weight-bold">Package / Source</th>
                                <th class="font-weight-bold">Level/Tier</th>
                                <th class="text-right font-weight-bold">Amount (USD)</th>
                                <th class="text-right font-weight-bold">Cash 25%</th>
                                <th class="text-right font-weight-bold">Trading 75%</th>
                                <th class="font-weight-bold">Description</th>
                                <th class="text-center font-weight-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commissions as $idx => $c)
                                @php
                                    $rowNo = ($commissions->currentPage() - 1) * $commissions->perPage() + $idx + 1;
                                    $cDate = $c->created_at ? \Carbon\Carbon::parse($c->created_at)->format('d M Y, H:i') : '—';
                                @endphp
                                <tr>
                                    <td class="text-muted font-weight-bold">{{ $rowNo }}</td>
                                    <td>
                                        <span class="font-mono font-weight-bold text-primary small">
                                            {{ $c->transaction_no ?: '#'.$c->id }}
                                        </span>
                                    </td>
                                    <td class="small text-nowrap">{{ $cDate }}</td>
                                    <td>
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size:0.75rem;">
                                            {{ ucwords(str_replace(['_','-'], ' ', $c->c_type)) }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        @if($c->c_from_user)
                                            <strong>{{ $c->c_from_user }}</strong>
                                        @else
                                            <span class="text-muted">— System —</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if($c->c_package)
                                            {{ $c->c_package }}
                                        @elseif($c->c_source_id)
                                            <span class="text-muted font-mono small">Ref #{{ $c->c_source_id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center small">
                                        @if($c->c_level)
                                            <span class="badge badge-secondary">L{{ $c->c_level }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-weight-bold text-success">
                                        ${{ number_format((float) $c->c_amount, 4) }}
                                    </td>
                                    <td class="text-right small">
                                        @if($c->c_cash !== null)
                                            ${{ number_format((float) $c->c_cash, 4) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-right small">
                                        @if($c->c_trading !== null)
                                            ${{ number_format((float) $c->c_trading, 4) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $c->c_description ?: '—' }}</td>
                                    <td class="text-center">
                                        @php $st = strtolower((string) $c->c_status); @endphp
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (10 per page, with page numbers) --}}
                @if($commissions->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $commissions->links('pagination::bootstrap-4') }}
                    </div>
                @endif
                @endif
            </div>

            </div>
            </div>

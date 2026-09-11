




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>

        .scrolling {
            overflow-x: auto;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="wrapper">
    <!-- Include the user-dashboard-base content here -->
    @include('user.user-dashboard-base')

    <div class="content-wrapper">
        <div class="w-full p-4">
            {{-- Nav (shared Bootstrap finance navbar) --}}
            @include('user.finance-nav', ['active' => 'subscription'])

            <div class="w-full bg-slate-100 rounded-xl p-5 my-5 shadow">
            {{-- <div>
                    <h1 class="flex items-baseline space-x-1"><span class="text-lg">{{$balance}}</span>
                         <span class="text-xs font-bold text-orange-600">$</span></h1>
                    <p class="text-gray-700 text-md">Total</p>
                </div> --}}

                <div>
                    <h2 class="text-orange-600 font-medium text-sm my-2">Purchase History</h2>
                </div>

                    <div class="border p-3 rounded-md">
                        <p class="flex items-baseline space-x-1"><span class="text-lg font-medium">{{$balance}}</span><span class="text-xs font-medium text-orange-600">$</span></p>
                        <p class="text-gray-500 text-sm font-medium">Total</p>
                    </div>

            </div>

            <div class="bg-white p-3 my-5 rounded shadow-md">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3 px-1">
                    <h2 class="text-lg font-bold mb-0">Purchase History</h2>
                    <span class="badge badge-secondary font-weight-bold px-3 py-2" style="font-size:0.85rem;">
                        {{ $transaction->total() }} record{{ $transaction->total() !== 1 ? 's' : '' }} · 10 per page
                    </span>
                </div>
                <div class="overflow-x-auto p-3 scrolling">
                    <table class="min-w-full bg-white table-sm table-bordered" style="font-size: 0.78rem;">
                     <thead>
                            <tr>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Product</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Plan</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Starting Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">End Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">User Subscribed</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Activation Code</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Package</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Token</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Token</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Investment</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Investment</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Pool Capital</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Current Pool Capital</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Period</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Purchase Date</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Username</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Revenue Earned</th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Revenue Type </th>
                                <th class="py-2 px-4 bg-gray-50 border-b text-xs border-gray-200 text-blue-900 font-bold uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use Carbon\Carbon;
                                function fmtDate($v) {
                                    if (!$v) return '—';
                                    try { return Carbon::parse($v)->format('M j, Y g:i A'); } catch (\Throwable $e) { return (string) $v; }
                                }
                            @endphp
                            @forelse($transaction as $trx)
                                @php
                                    $data = json_decode($trx->transaction_details) ?: (object) [];
                                    $_v = function ($key, $fallback = '—') use ($data) {
                                        return $data->$key ?? $fallback;
                                    };
                                @endphp
                            <tr>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('product') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs font-weight-bold">{{ $_v('plan') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-nowrap">{{ fmtDate($_v('start_date', null)) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-nowrap">{{ fmtDate($_v('end_date', null)) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('user') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs font-mono">{{ $_v('code') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs font-weight-bold">{{ $_v('package') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('token') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('current_token') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-right">${{ number_format((float) $_v('price', 0), 2) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-right">${{ number_format((float) $_v('current_price', 0), 2) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-right">${{ number_format((float) $_v('poolcapital', 0), 2) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-right">${{ number_format((float) $_v('current_poolcapital', 0), 2) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('period') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-nowrap">{{ fmtDate($_v('purchase_date', null)) }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs font-weight-bold">{{ $_v('username') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-right">{{ $_v('revenue_earned') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs">{{ $_v('revenue_type') }}</td>
                                <td class="py-2 px-3 border-b border-gray-200 text-xs text-center">
                                    @php $st = strtolower((string) $_v('status', '—')); @endphp
                                    @if($st === 'success' || $st === 'completed' || $st === 'approved')
                                        <span class="badge badge-success">Success</span>
                                    @elseif($st === 'pending')
                                        <span class="badge badge-warning text-dark">Pending</span>
                                    @elseif($st === 'failed' || $st === 'rejected')
                                        <span class="badge badge-danger">{{ ucfirst($st) }}</span>
                                    @else
                                        <span class="badge badge-light border">{{ ucfirst($st) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="19" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox text-3xl mb-2 d-block text-gray-300"></i>
                                    You have not subscribed to any product yet.<br>
                                    <a href="{{ route('user.buypackage') }}" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-plus mr-1"></i> Subscribe Product
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                @if($transaction->hasPages())
                    <div class="d-flex justify-content-center mt-3 px-3">
                        {{ $transaction->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>

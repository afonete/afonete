<div class="wrapper">
 @include('user.user-dashboard-base')
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

 <style>
     body {
         font-family: "Poppins", sans-serif;
         background-color: #111215;
         color: #fff;
     }
     .history-container {
         width: 100%;
         margin-top: 1.5rem;
         margin-bottom: 2rem;
     }
     .history-header {
         background: #1c1d20;
         padding: 1.25rem 1.5rem;
         border-radius: 12px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
         margin-bottom: 1.5rem;
         border: 1px solid #2e2f34;
     }
     .history-title-box {
         display: flex;
         align-items: center;
         gap: 12px;
     }
     .history-title-icon {
         font-size: 1.5rem;
         color: #10b981;
         background: rgba(16, 185, 129, 0.1);
         padding: 10px;
         border-radius: 8px;
     }
     .history-title {
         font-size: 1.2rem;
         font-weight: 700;
         color: #fff;
         margin: 0;
     }
     .history-card {
         background: #1c1d20;
         border-radius: 12px;
         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
         overflow: hidden;
         padding: 1.5rem;
         border: 1px solid #2e2f34;
     }
     .payment-table th {
         background: #111215;
         color: #94a3b8;
         font-weight: 600;
         font-size: 0.78rem;
         text-transform: uppercase;
         letter-spacing: 0.6px;
         padding: 1rem 1.25rem;
         border-bottom: 1px solid #2e2f34;
     }
     .payment-table td {
         padding: 1.1rem 1.25rem;
         vertical-align: middle;
         border-bottom: 1px solid #2e2f34;
         color: #cbd5e1;
         font-size: 0.88rem;
     }
     .trx-code {
         font-family: monospace;
         background: #111215;
         color: #94a3b8;
         padding: 3px 8px;
         border-radius: 6px;
         font-weight: 600;
         font-size: 0.78rem;
         border: 1px solid #2e2f34;
     }
     /* Custom pagination styling overrides */
     .pagination-wrapper .page-item .page-link {
         border-radius: 6px !important;
         border: 1px solid #2e2f34;
         color: #cbd5e1;
         background-color: #111215;
         font-weight: 600;
         font-size: 0.85rem;
         padding: 0.4rem 0.8rem;
         transition: all 0.15s ease;
     }
     .pagination-wrapper .page-item.active .page-link {
         background-color: #3b82f6 !important;
         border-color: #3b82f6 !important;
         color: #ffffff !important;
     }
     .stats-card {
         border: 1px solid #2e2f34;
         border-radius: 12px;
         background-color: #1c1d20;
         transition: transform 0.2s;
     }
     .stats-card:hover {
         transform: translateY(-2px);
     }
 </style>

 <div class="content-wrapper">
    <div class="container-fluid">
        <div class="history-container">
            
            {{-- Header --}}
            <div class="history-header">
                <div class="history-title-box">
                    <i class="history-title-icon fas fa-history"></i>
                    <div>
                        <h3 class="history-title">Withdrawal Transaction History</h3>
                        <p class="text-slate-400 text-xs mb-0">Review all your requested, processing, and completed manual or direct blockchain withdrawals.</p>
                    </div>
                </div>
            </div>

            {{-- Summary Stats Row --}}
            @if(isset($stats))
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="card stats-card shadow-sm text-white">
                        <div class="card-body py-3">
                            <small class="text-muted text-uppercase font-weight-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Requested</small>
                            <h4 class="font-weight-bold mb-0 mt-1 text-primary">${{ number_format($stats['total_requested'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="card stats-card shadow-sm text-white">
                        <div class="card-body py-3">
                            <small class="text-muted text-uppercase font-weight-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Completed</small>
                            <h4 class="font-weight-bold mb-0 mt-1 text-success">${{ number_format($stats['total_completed'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stats-card shadow-sm text-white">
                        <div class="card-body py-3">
                            <small class="text-muted text-uppercase font-weight-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.05em;">Pending Requests</small>
                            <h4 class="font-weight-bold mb-0 mt-1 text-warning">{{ $stats['pending_count'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stats-card shadow-sm text-white">
                        <div class="card-body py-3">
                            <small class="text-muted text-uppercase font-weight-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Fees Paid</small>
                            <h4 class="font-weight-bold mb-0 mt-1 text-danger">${{ number_format($stats['total_fees'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- History Card --}}
            <div class="history-card">
                <div class="table-responsive">
                    <table class="table payment-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference No</th>
                                <th>Method &amp; Type</th>
                                <th>Asset &amp; Network</th>
                                <th>Requested</th>
                                <th>Fee Charged</th>
                                <th>Net Received</th>
                                <th>Destination Address</th>
                                <th>Status &amp; Tx Hash</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($history as $w)
                                <tr>
                                    {{-- Row Index --}}
                                    <td>
                                        <span class="font-weight-bold text-slate-500">
                                            {{ ($history->currentPage() - 1) * $history->perPage() + $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- Reference --}}
                                    <td>
                                        <span class="trx-code select-all" title="{{ $w->transaction_no }}">
                                            {{ $w->transaction_no }}
                                        </span>
                                    </td>

                                    {{-- Method & Type --}}
                                    <td>
                                        <span class="badge badge-light border px-2 py-1 text-slate-600 font-weight-bold" style="background-color: #6366f1 !important; color: #fff !important; border: none !important;">
                                            {{ $w->methodLabel() }}
                                        </span>
                                        <small class="text-muted d-block mt-1">
                                            {{ $w->typeLabel() }}
                                        </small>
                                    </td>

                                    {{-- Asset & Network --}}
                                    <td>
                                        <span class="font-weight-bold text-light">
                                            {{ $w->currency ?? 'USDT' }}
                                        </span>
                                        @if($w->network)
                                            <span class="badge badge-dark border border-secondary text-xs ml-1" style="font-size: 0.72rem; padding: 2px 6px;">
                                                {{ $w->network }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Requested Amount --}}
                                    <td class="font-weight-bold">
                                        ${{ number_format($w->amount, 2) }}
                                    </td>

                                    {{-- Fee Charged --}}
                                    <td class="text-warning font-semibold">
                                        ${{ number_format($w->fee_amount ?? 0.00, 2) }}
                                    </td>

                                    {{-- Net Received --}}
                                    <td class="text-success font-extrabold" style="font-size: 0.95rem;">
                                        ${{ number_format($w->net_amount ?? $w->amount, 2) }}
                                    </td>

                                    {{-- Wallet/Recipient --}}
                                    <td>
                                        <code class="text-xs text-slate-400 bg-slate-900 border border-slate-700 px-2 py-1 rounded select-all" title="{{ $w->wallet_address }}">{{ Str::limit($w->wallet_address, 18) }}</code>
                                    </td>

                                    {{-- Status & Tx Hash --}}
                                    <td>
                                        @php
                                            $badgeClass = match($w->status) {
                                                'completed' => 'bg-emerald-950/40 text-emerald-300 border border-emerald-800/40',
                                                'pending' => 'bg-amber-950/40 text-amber-300 border border-amber-800/40',
                                                'processing' => 'bg-sky-950/40 text-sky-300 border border-sky-800/40',
                                                default => 'bg-red-950/40 text-red-300 border border-red-800/40',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-bold">{{ ucfirst($w->status) }}</span>
                                        
                                        @if($w->txn_hash || $w->blockchain_tx_hash)
                                            @php $tx = $w->txn_hash ?? $w->blockchain_tx_hash; @endphp
                                            <small class="text-muted d-block mt-1 truncate max-w-[150px]" title="{{ $tx }}">
                                                <i class="fas fa-link mr-1"></i> {{ Str::limit($tx, 12) }}
                                            </small>
                                        @endif

                                        @if($w->status === 'failed' && $w->failure_reason)
                                            <small class="text-danger d-block mt-1 font-weight-bold" title="{{ $w->failure_reason }}">
                                                Reason: {{ Str::limit($w->failure_reason, 25) }}
                                            </small>
                                        @endif

                                        @if($w->admin_note)
                                            <small class="text-info d-block mt-1" title="{{ $w->admin_note }}">
                                                Note: {{ Str::limit($w->admin_note, 25) }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- Date --}}
                                    <td>
                                        <span class="text-muted" style="font-size: 0.8rem;">
                                            <i class="far fa-clock mr-1"></i> {{ $w->created_at->format('Y-m-d h:i A') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted text-sm">
                                        <i class="fas fa-folder-open mb-2" style="font-size: 2rem; color: #475569;"></i>
                                        <p class="mb-0">No withdrawal records found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($history->hasPages())
                    <div class="pagination-wrapper mt-4 d-flex justify-content-center">
                        {{ $history->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
 </div>
</div>

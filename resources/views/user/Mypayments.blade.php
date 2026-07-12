<div class="wrapper">
 @include('user.user-dashboard-base')
 
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    body {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;
        background-color: #f4f5f7;
    }
    .payment-container {
        width: 100%;
        margin-top: 1.5rem;
        margin-bottom: 2rem;
    }
    .payment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
    }
    .payment-title-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .payment-title-icon {
        font-size: 1.5rem;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.1);
        padding: 10px;
        border-radius: 8px;
    }
    .payment-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .payment-actions {
        display: flex;
        gap: 10px;
    }
    .btn-action-primary {
        background: #3b82f6;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        box-shadow: 0 3px 6px rgba(59, 130, 246, 0.2);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(59, 130, 246, 0.3);
    }
    .btn-action-secondary {
        background: #4b5563;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        border: none;
        box-shadow: 0 3px 6px rgba(75, 85, 99, 0.2);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action-secondary:hover {
        background: #374151;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(75, 85, 99, 0.3);
    }
    .payment-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        padding: 1.5rem;
    }
    .table-responsive {
        margin: 0;
        padding: 0;
    }
    .payment-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .payment-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .payment-table td {
        padding: 1.1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.88rem;
    }
    .trx-code {
        font-family: monospace;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.78rem;
    }
    .pagination-wrapper {
        border-top: 1px solid #f1f5f9;
        padding-top: 1.25rem;
        margin-top: 1rem;
        background: #ffffff;
        display: flex;
        justify-content: center;
    }
    /* Custom tab styles */
    .nav-pills .nav-link {
        color: #4b5563;
        transition: all 0.2s;
    }
    .nav-pills .nav-link.active {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 6px rgba(59, 130, 246, 0.2);
    }
</style>

 <div class="content-wrapper">
    <div class="container-fluid">
        <div class="payment-container">
            
            {{-- Session Success/Error Feedback Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 p-3 shadow-sm" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-check-circle mr-2"></i><strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 p-3 shadow-sm" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-exclamation-triangle mr-2"></i><strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Table Header Banner --}}
            <div class="payment-header">
                <div class="payment-title-box">
                    <i class="payment-title-icon fas fa-history"></i>
                    <div>
                        <h3 class="payment-title">Deposits &amp; Purchases Transactions</h3>
                        <p class="text-muted text-xs mb-0">List of all deposits, payment plans, and direct-purchases records.</p>
                    </div>
                </div>
                <div class="payment-actions">
                    {{-- <a class="btn-action-primary" href="{{ route('user.payment.deposits') }}">
                        <i class="fas fa-plus"></i> New Deposit
                    </a> --}}
                    <a class="btn-action-primary" href="/user/manual-deposit">
                        <i class="fas fa-plus"></i> New Deposit
                    </a>
                    {{-- <a class="btn-action-secondary" href="/user/manual-deposit">
                        <i class="fas fa-file-invoice-dollar"></i> Manual Deposit
                    </a> --}}
                </div>
            </div>

            {{-- Tab Switchers --}}
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="gap: 10px;">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="pills-deposits-tab" data-toggle="pill" href="#pills-deposits" role="tab" aria-controls="pills-deposits" aria-selected="true" style="border-radius: 8px; padding: 0.6rem 1.2rem;">
                        <i class="fas fa-university mr-1"></i> Deposits History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="pills-purchases-tab" data-toggle="pill" href="#pills-purchases" role="tab" aria-controls="pills-purchases" aria-selected="false" style="border-radius: 8px; padding: 0.6rem 1.2rem;">
                        <i class="fas fa-shopping-bag mr-1"></i> Package Purchases (UVP)
                    </a>
                </li>
            </ul>

            {{-- Table Card --}}
            <div class="payment-card">
                <div class="tab-content" id="pills-tabContent">
                    
                    {{-- TAB 1: DEPOSITS HISTORY --}}
                    <div class="tab-pane fade show active" id="pills-deposits" role="tabpanel" aria-labelledby="pills-deposits-tab">
                        <div class="table-responsive">
                            <table class="table payment-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Transaction Date</th>
                                        <th>Status &amp; Feedback</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($deposits as $deposit)
                                        <tr>
                                            <td>
                                                <span class="font-weight-bold text-slate-500">
                                                    {{ ($deposits->currentPage() - 1) * $deposits->perPage() + $loop->iteration }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="trx-code" title="{{ $deposit->transaction_id }}">
                                                    {{ $deposit->transaction_id ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- Safe Fallback to show outflow of USED deposits vs inflow of NEW deposits --}}
                                                @if ($deposit->amount_deposited > 0)
                                                    <span class="text-success font-weight-bold" style="font-size: 0.95rem;">
                                                        +${{ number_format($deposit->amount_deposited, 2) }}
                                                    </span>
                                                @elseif ($deposit->amount_removed > 0)
                                                    <span class="text-danger font-weight-bold" style="font-size: 0.95rem;">
                                                        -${{ number_format($deposit->amount_removed, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted font-weight-bold">$0.00</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-light border px-2 py-1 text-slate-600 font-weight-bold">
                                                    {{ $deposit->deposit_method ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                    <i class="far fa-clock mr-1"></i> {{ $deposit->created_at->format('Y-m-d h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($deposit->status == 'pending')
                                                    <span class="badge badge-warning px-2 py-1 text-white font-weight-bold"><i class="fas fa-clock mr-1"></i>Pending</span>
                                                @elseif ($deposit->status == 'approved')
                                                    <span class="badge badge-success px-2 py-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Approved</span>
                                                @elseif ($deposit->status == 'used')
                                                    <span class="badge badge-primary px-2 py-1 font-weight-bold" style="background-color: #3b82f6 !important;"><i class="fas fa-shopping-cart mr-1"></i>Used</span>
                                                @elseif ($deposit->status == 'rejected')
                                                    <span class="badge badge-danger px-2 py-1 font-weight-bold"><i class="fas fa-times-circle mr-1"></i>Rejected</span>
                                                @elseif ($deposit->status == 'cancelled')
                                                    <span class="badge badge-danger px-2 py-1 font-weight-bold"><i class="fas fa-ban mr-1"></i>Cancelled</span>
                                                @else
                                                    <span class="badge badge-secondary px-2 py-1 font-weight-bold">{{ ucfirst($deposit->status) }}</span>
                                                @endif

                                                {{-- Admin Response Comment rendering --}}
                                                @if(!empty($deposit->comment))
                                                    <div class="text-xs text-muted mt-1" style="max-width: 200px; line-height: 1.3;">
                                                        <span class="text-info font-weight-bold"><i class="fas fa-comment-dots"></i> Admin:</span>
                                                        <span class="font-italic">"{{ $deposit->comment }}"</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if (($deposit->status == 'pending' || $deposit->status == 'rejected') && !empty($deposit->transaction_id))
                                                    <form method="POST" action="{{ route('user.claim') }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="transactionId" value="{{ $deposit->transaction_id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm font-weight-bold" style="border-radius: 6px; transition: all 0.2s;"
                                                            onclick="return confirm('Are you sure you want to request approval / report an issue for Transaction ID: {{ $deposit->transaction_id }}?')">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i> Report Issue
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-light btn-sm text-muted font-weight-bold" disabled style="cursor: not-allowed; opacity: 0.7; border-radius: 6px;">
                                                        <i class="fas fa-check mr-1 text-success"></i> No Issue
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fas fa-folder-open mb-2" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                <p class="mb-0">No deposit records found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Deposits Pagination (Independently tracked by 'deposits_page' variable) --}}
                        @if($deposits->total() > 10)
                            <div class="pagination-wrapper">
                                {{ $deposits->appends(request()->except('deposits_page'))->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>

                    {{-- TAB 2: UVP PACKAGE PURCHASES --}}
                    <div class="tab-pane fade" id="pills-purchases" role="tabpanel" aria-labelledby="pills-purchases-tab">
                        <div class="table-responsive">
                            <table class="table payment-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Package Plan</th>
                                        <th>Amount Paid</th>
                                        <th>Purchase Date</th>
                                        <th>Expiration Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($investments as $inv)
                                        <tr>
                                            <td>
                                                <span class="font-weight-bold text-slate-500">
                                                    {{ ($investments->currentPage() - 1) * $investments->perPage() + $loop->iteration }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary px-3 py-2 font-weight-bold" style="background-color: #6366f1 !important; border-radius: 6px; font-size: 0.8rem;">
                                                    <i class="fas fa-gem mr-1"></i> {{ $inv->package ?: 'VENTURE' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success font-weight-bold" style="font-size: 0.95rem;">
                                                    ${{ number_format($inv->paid, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                    <i class="far fa-calendar-alt mr-1"></i> {{ $inv->created_at->format('Y-m-d h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                    <i class="far fa-calendar-times mr-1"></i> {{ $inv->expiration_date ?: 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($inv->is_expired)
                                                    <span class="badge badge-danger px-2 py-1 font-weight-bold"><i class="fas fa-times-circle mr-1"></i>Expired</span>
                                                @elseif ($inv->status == 1)
                                                    <span class="badge badge-success px-2 py-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Active</span>
                                                @else
                                                    <span class="badge badge-warning px-2 py-1 font-weight-bold text-white"><i class="fas fa-clock mr-1"></i>Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-box-open mb-2" style="font-size: 2rem; color: #cbd5e1;"></i>
                                                <p class="mb-0">No package purchase records found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Purchases Pagination (Independently tracked by 'purchases_page' variable) --}}
                        @if($investments->total() > 10)
                            <div class="pagination-wrapper">
                                {{ $investments->appends(request()->except('purchases_page'))->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
 </div>
</div>

<script src="{{asset('assets/a/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4">

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold uppercase text-slate-700 mb-0">Withdrawal Requests</h2>
        <div class="btn-group btn-group-sm" role="group" aria-label="Filter by method">
            <a href="{{ route('admin.withdrawal') }}"
               class="btn btn-{{ empty($methodFilter) ? 'primary' : 'outline-primary' }}">All</a>
            <a href="{{ route('admin.withdrawal', ['method' => 'crypto']) }}"
               class="btn btn-{{ ($methodFilter ?? '') === 'crypto' ? 'primary' : 'outline-primary' }}">
                <i class="fab fa-bitcoin"></i> Crypto
            </a>
            <a href="{{ route('admin.withdrawal', ['method' => 'advcash']) }}"
               class="btn btn-{{ ($methodFilter ?? '') === 'advcash' ? 'warning' : 'outline-warning' }}">
                <i class="fas fa-money-bill-wave"></i> Advcash
            </a>
            <a href="{{ route('admin.withdrawal', ['method' => 'perfect_money']) }}"
               class="btn btn-{{ ($methodFilter ?? '') === 'perfect_money' ? 'info' : 'outline-info' }}">
                <i class="fas fa-coins"></i> Perfect Money
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════
         PENDING — needs action
    ═══════════════════════════════════ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-clock mr-1"></i> Pending ({{ $pending->count() }})
            <small class="ml-2 font-weight-normal">— Requires approval or rejection</small>
        </div>
        <div class="card-body p-0">
            @if($pending->isEmpty())
                <p class="text-muted p-3 mb-0">No pending withdrawals.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Currency</th>
                            <th>Network</th>
                            <th>Wallet Address</th>
                            <th>Reference</th>
                            <th>Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $i => $w)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $w->user->name ?? '—' }}</strong><br>
                                <small class="text-muted">{{ $w->user->email ?? '' }}</small>
                            </td>
                            <td class="font-weight-bold text-danger">${{ number_format($w->amount, 2) }}</td>
                            <td>
                                @php
                                    $mb = $w->method === 'crypto' ? 'primary'
                                        : ($w->method === 'advcash' ? 'warning'
                                        : ($w->method === 'perfect_money' ? 'info' : 'secondary'));
                                @endphp
                                <span class="badge badge-{{ $mb }}">{{ $w->methodLabel() }}</span>
                            </td>
                            <td><small>{{ $w->currency }}</small></td>
                            <td><small class="text-muted">{{ $w->network ?? '—' }}</small></td>
                            <td><small style="word-break:break-all;">{{ $w->wallet_address }}</small></td>
                            <td><small class="text-muted">{{ $w->transaction_no }}</small></td>
                            <td><small>{{ $w->created_at->format('d M Y H:i') }}</small></td>
                            <td>
                                {{-- Approve (with on-chain hash field — FIX W8) --}}
                                <form method="POST" action="{{ route('admin.withdrawal.approve') }}" class="d-inline-block">
                                    @csrf
                                    <input type="hidden" name="withdrawal_id" value="{{ $w->id }}">
                                    <input type="text" name="txn_hash" placeholder="on-chain hash (optional)"
                                           class="form-control form-control-sm mb-1" style="width:220px">
                                    <input type="text" name="admin_note" placeholder="note (optional)"
                                           class="form-control form-control-sm mb-1" style="width:220px">
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Approve withdrawal of ${{ number_format($w->amount,2) }} for {{ $w->user->name ?? '' }}?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                {{-- Reject with note --}}
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="showRejectModal({{ $w->id }}, '{{ addslashes($w->user->name ?? '') }}', {{ $w->amount }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════
         COMPLETED
    ═══════════════════════════════════ --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white font-weight-bold">
            <i class="fas fa-check-circle mr-1"></i> Completed
        </div>
        <div class="card-body p-0">
            @if($completed->isEmpty())
                <p class="text-muted p-3 mb-0">No completed withdrawals yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Wallet</th>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completed as $w)
                        <tr>
                            <td>{{ $w->user->name ?? '—' }}<br><small class="text-muted">{{ $w->user->email ?? '' }}</small></td>
                            <td>${{ number_format($w->amount, 2) }}</td>
                            <td>
                                @php
                                    $mb = $w->method === 'crypto' ? 'primary'
                                        : ($w->method === 'advcash' ? 'warning'
                                        : ($w->method === 'perfect_money' ? 'info' : 'secondary'));
                                @endphp
                                <span class="badge badge-{{ $mb }}">{{ $w->methodLabel() }}</span>
                                <small class="text-muted d-block">{{ $w->currency }} {{ $w->network ? '· ' . $w->network : '' }}</small>
                            </td>
                            <td><small style="word-break:break-all;">{{ Str::limit($w->wallet_address, 25) }}</small></td>
                            <td><small class="text-muted">{{ $w->transaction_no }}</small></td>
                            <td><span class="badge badge-info">{{ $w->plisio_txn_id ? 'Instant' : 'Manual' }}</span></td>
                            <td><small>{{ $w->admin_note ?? '—' }}</small></td>
                            <td><small>{{ $w->created_at->format('d M Y') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-2">{{ $completed->links() }}</div>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ═══════ Reject Modal ═══════ --}}
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times mr-2"></i>Reject Withdrawal</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.withdrawal.reject') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="withdrawal_id" id="rejectWithdrawalId">
                    <p id="rejectSummary" class="text-muted"></p>
                    <p class="text-info"><i class="fas fa-info-circle mr-1"></i>The amount will be <strong>refunded</strong> to the user's CASHOUT balance.</p>
                    <div class="form-group">
                        <label>Reason / Note (shown to user)</label>
                        <textarea name="admin_note" rows="3" class="form-control" placeholder="e.g. Invalid wallet address, please resubmit..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i>Confirm Reject & Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(id, name, amount) {
    document.getElementById('rejectWithdrawalId').value = id;
    document.getElementById('rejectSummary').textContent =
        'Rejecting $' + parseFloat(amount).toFixed(2) + ' withdrawal for ' + name + '.';
    $('#rejectModal').modal('show');
}
</script>
@endsection
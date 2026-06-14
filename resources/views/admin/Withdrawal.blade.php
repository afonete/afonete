@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4">

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2 class="text-2xl font-bold mb-4 uppercase text-slate-700">Withdrawal Requests</h2>

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
                            <th>Wallet Address</th>
                            <th>Currency</th>
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
                            <td><small>{{ $w->wallet_address }}</small></td>
                            <td>{{ $w->currency }}</td>
                            <td><small class="text-muted">{{ $w->transaction_no }}</small></td>
                            <td><small>{{ $w->created_at->format('d M Y H:i') }}</small></td>
                            <td>
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('admin.withdrawal.approve') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="withdrawal_id" value="{{ $w->id }}">
                                    <input type="hidden" name="admin_note" value="Approved by admin">
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
                            <td><small>{{ Str::limit($w->wallet_address, 25) }}</small></td>
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

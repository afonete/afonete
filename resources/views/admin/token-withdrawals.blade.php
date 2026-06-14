@extends('admin.sidebar')
@section('contents')
<div class="container-fluid py-4 px-4">

    @if(session('message'))<div class="alert alert-success">{{ session('message') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <header class="bg-blue-50 py-6 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold"><i class="fas fa-coins mr-2"></i>Token Withdrawals</h1>
    </header>

    {{-- Gas Fees Summary (admin-only) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white font-weight-bold"><i class="fas fa-gas-pump mr-1"></i>Gas Fees (20% Charges) — Admin Only</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Gas Fee (tokens)</th></tr></thead>
                    <tbody>
                        @forelse($gasFees as $userId => $amount)
                        @php $u = \App\Models\User::find($userId); @endphp
                        <tr>
                            <td>{{ $u->name ?? '—' }} <small class="text-muted">{{ $u->email ?? '' }}</small></td>
                            <td>{{ number_format($amount, 0) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-muted p-3">No gas fees recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pending --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-warning text-dark font-weight-bold"><i class="fas fa-clock mr-1"></i>Pending ({{ $pending->count() }})</div>
        <div class="card-body p-0">
            @if($pending->isEmpty())<p class="text-muted p-3 mb-0">No pending requests.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Tokens</th><th>Wallet</th><th>Coin Value</th><th>Ref</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($pending as $tw)
                        <tr>
                            <td><strong>{{ $tw->user->name ?? '—' }}</strong><br><small class="text-muted">{{ $tw->user->email ?? '' }}</small></td>
                            <td class="font-weight-bold">{{ number_format($tw->token_amount, 0) }}</td>
                            <td><small>{{ $tw->wallet_address }}</small></td>
                            <td><small>${{ number_format($tw->coin_value_at_request, 6) }}</small></td>
                            <td><small class="text-muted">{{ $tw->transaction_no }}</small></td>
                            <td><small>{{ $tw->created_at->format('d M Y H:i') }}</small></td>
                            <td>
                                <form method="POST" action="{{ route('admin.token-withdrawals.approve') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="token_withdrawal_id" value="{{ $tw->id }}">
                                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve?')"><i class="fas fa-check"></i></button>
                                </form>
                                <button class="btn btn-danger btn-sm" onclick="showReject({{ $tw->id }})"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Completed/Rejected --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i>Processed</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light"><tr><th>User</th><th>Tokens</th><th>Status</th><th>Note</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($completed as $tw)
                        <tr>
                            <td>{{ $tw->user->name ?? '—' }}</td>
                            <td>{{ number_format($tw->token_amount, 0) }}</td>
                            <td><span class="badge badge-{{ $tw->status === 'approved' ? 'success' : 'danger' }}">{{ ucfirst($tw->status) }}</span></td>
                            <td><small>{{ $tw->admin_note ?? '—' }}</small></td>
                            <td><small>{{ $tw->created_at->format('d M Y') }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-muted p-3">None yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-2">{{ $completed->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white"><h5 class="modal-title">Reject Token Withdrawal</h5><button class="close text-white" data-dismiss="modal">&times;</button></div>
            <form method="POST" action="{{ route('admin.token-withdrawals.reject') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="token_withdrawal_id" id="rejectId">
                    <div class="form-group"><label>Reason</label><textarea name="admin_note" class="form-control" rows="2" placeholder="Reason for rejection…"></textarea></div>
                    <p class="text-info small"><i class="fas fa-info-circle mr-1"></i>Tokens will be refunded to the user.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>function showReject(id){ document.getElementById('rejectId').value=id; $('#rejectModal').modal('show'); }</script>
@endsection

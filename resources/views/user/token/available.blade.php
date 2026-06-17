@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="font-weight-bold mb-0"><i class="fas fa-check-circle text-success mr-2"></i> Available Token Wallet</h3>
        <a href="{{ route('user.investments') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-box-open mr-1"></i> My Investments
        </a>
    </div>

    @include('user.token._nav', ['active' => 'available'])

    @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Available Token Wallet
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6 border-right">
                            <div class="text-muted small">Available Balance</div>
                            <div class="font-weight-bold" style="font-size:1.3rem;">{{ number_format($availableBal, 0) }}</div>
                            <div class="text-muted small">{{ $symbol }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Free Token Balance</div>
                            <div class="font-weight-bold" style="font-size:1.3rem;">{{ number_format($freeBal, 0) }}</div>
                            <div class="text-muted small">{{ $symbol }}</div>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Spec:</strong> Available Token contains:<br>
                        &bull; Tokens earned from each <strong>30-day renewal</strong> (formula: <code>trading_voucher ÷ renewal_price</code>)<br>
                        &bull; <strong>Locked tokens released</strong> when your package expires (auto by system)<br><br>
                        To use them (transfer / swap / withdraw), first move them to <strong>Free Token</strong>.
                    </div>

                    <form method="POST" action="{{ route('user.token.available-to-free') }}">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Amount to Move to Free Token <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control form-control-lg"
                                   min="1" step="1" max="{{ $availableBal }}" required
                                   placeholder="Number of tokens">
                        </div>
                        <button type="submit" class="btn btn-success btn-block font-weight-bold py-2"
                                {{ $availableBal < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-arrow-right mr-1"></i>
                            Move to Free Token Wallet
                        </button>
                        @if($availableBal < 1)
                            <small class="text-muted d-block text-center mt-1">No available tokens yet.</small>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7 mt-3 mt-md-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Transfer History</div>
                <div class="card-body p-0">
                    @if($history->isEmpty())
                        <p class="text-muted p-3 mb-0">No transfers yet.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light"><tr><th>Tokens Moved</th><th>Ref</th><th>Date</th></tr></thead>
                            <tbody>
                                @foreach($history as $t)
                                <tr>
                                    <td class="font-weight-bold text-success">{{ number_format($t->tok_amt, 0) }} {{ $symbol }}</td>
                                    <td><small class="text-muted">{{ $t->transaction_no }}</small></td>
                                    <td><small>{{ $t->created_at->format('d M Y H:i') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    {{-- Token wallet nav --}}
    @include('user.token._nav', ['active' => 'transfer'])

    @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger mt-2">{{ $errors->first() }}</div>@endif

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold">
                    <i class="fas fa-paper-plane mr-1"></i> Transfer {{ $symbol }} Tokens to Another User
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 p-2 bg-light rounded">
                        <span class="text-muted">Your Free Token balance:</span>
                        <span class="font-weight-bold">{{ number_format($freeBal, 0) }} {{ $symbol }}</span>
                    </div>

                    <form method="POST" action="{{ route('user.token.transfer.post') }}" id="transferForm" class="js-transaction-password-form">
                        @csrf

                        {{-- Email lookup --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Recipient Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="email" name="recipient_email" id="recipientEmail"
                                       class="form-control" placeholder="recipient@email.com"
                                       value="{{ old('recipient_email') }}" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="lookupBtn">
                                        <i class="fas fa-search"></i> Find
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Name preview (shown after lookup) --}}
                        <div id="recipientPreview" class="alert alert-success py-2 d-none">
                            <i class="fas fa-user-check mr-1"></i>
                            Sending to: <strong id="recipientName"></strong>
                        </div>
                        <div id="recipientError" class="alert alert-danger py-2 d-none">
                            <i class="fas fa-times-circle mr-1"></i>
                            <span id="recipientErrorMsg"></span>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Token Amount <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" class="form-control"
                                   min="1" step="1" max="{{ $freeBal }}"
                                   placeholder="How many tokens" required>
                        </div>

                        <div class="alert alert-warning small py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Transfers go to the recipient's <strong>Free Token</strong> wallet. Immediate and irreversible.
                        </div>

                        <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold"
                                id="submitBtn" {{ $freeBal < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-1"></i> Transfer Tokens
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7 mt-3 mt-md-0">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light font-weight-bold">
                    <i class="fas fa-history mr-1"></i> Transfer History
                </div>
                <div class="card-body p-0">
                    @if($history->isEmpty())
                        <p class="text-muted p-3 mb-0">No transfers yet.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr><th>To Name</th><th>To Email</th><th>Tokens</th><th>Ref</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @foreach($history as $t)
                                <tr>
                                    <td><strong>{{ $t->to_name }}</strong></td>
                                    <td><small class="text-muted">{{ $t->to_email }}</small></td>
                                    <td class="font-weight-bold text-primary">{{ number_format($t->tok_amt, 0) }} {{ $symbol }}</td>
                                    <td><small class="text-muted">{{ $t->transaction_no }}</small></td>
                                    <td><small>{{ $t->created_at->format('d M Y') }}</small></td>
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

@include('user.components.transaction-password-modal')
<script>
document.getElementById('lookupBtn').addEventListener('click', function () {
    const email   = document.getElementById('recipientEmail').value.trim();
    const preview = document.getElementById('recipientPreview');
    const errBox  = document.getElementById('recipientError');
    const nameEl  = document.getElementById('recipientName');

    if (!email) { return; }

    preview.classList.add('d-none');
    errBox.classList.add('d-none');

    fetch('{{ route("user.token.transfer.lookup") }}?email=' + encodeURIComponent(email), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.found) {
            nameEl.textContent = data.name + ' (' + data.email + ')';
            preview.classList.remove('d-none');
        } else {
            document.getElementById('recipientErrorMsg').textContent = data.message;
            errBox.classList.remove('d-none');
        }
    });
});
</script>

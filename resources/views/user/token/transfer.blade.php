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

                        {{-- Recipient lookup by activation code or username --}}
                        <div class="form-group">
                            <label class="font-weight-bold">Recipient Activation Code or Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="recipient_identifier" id="recipientIdentifier"
                                       class="form-control" placeholder="Enter activation code or username"
                                       value="{{ old('recipient_identifier') }}" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="lookupBtn">
                                        <i class="fas fa-search"></i> Find
                                    </button>
                                </div>
                            </div>
                            @error('recipient_identifier')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Name preview (shown after lookup) --}}
                        <div id="recipientPreview" class="alert alert-success py-2 d-none">
                            <i class="fas fa-user-check mr-1"></i>
                            Sending to: <strong id="recipientName"></strong>
                            <div class="small mt-1">
                                Username: <strong id="recipientUsername"></strong> · Activation: <strong id="recipientActivation"></strong>
                            </div>
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
                                id="submitBtn" disabled data-can-transfer="{{ $freeBal >= 1 ? '1' : '0' }}">
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
                                <tr><th>To Name</th><th>Username</th><th>Activation</th><th>Tokens</th><th>Ref</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                @foreach($history as $t)
                                <tr>
                                    <td><strong>{{ $t->to_name }}</strong></td>
                                    <td><small class="text-muted">{{ $t->to_username }}</small></td>
                                    <td><small class="text-muted">{{ $t->to_activation }}</small></td>
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
(function () {
    const lookupBtn  = document.getElementById('lookupBtn');
    const input      = document.getElementById('recipientIdentifier');
    const preview    = document.getElementById('recipientPreview');
    const errBox     = document.getElementById('recipientError');
    const nameEl     = document.getElementById('recipientName');
    const usernameEl = document.getElementById('recipientUsername');
    const activationEl = document.getElementById('recipientActivation');
    const submitBtn  = document.getElementById('submitBtn');
    const canTransfer = submitBtn && submitBtn.getAttribute('data-can-transfer') === '1';

    function resetLookup() {
        preview.classList.add('d-none');
        errBox.classList.add('d-none');
        submitBtn.disabled = true;
    }

    lookupBtn.addEventListener('click', function () {
        const identifier = input.value.trim();
        resetLookup();

        if (!identifier) {
            document.getElementById('recipientErrorMsg').textContent = 'Enter recipient activation code or username.';
            errBox.classList.remove('d-none');
            return;
        }

        fetch('{{ route("user.token.transfer.lookup") }}?identifier=' + encodeURIComponent(identifier), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.found) {
                nameEl.textContent = data.name;
                usernameEl.textContent = data.username || '—';
                activationEl.textContent = data.activation || '—';
                preview.classList.remove('d-none');
                submitBtn.disabled = !canTransfer;
            } else {
                document.getElementById('recipientErrorMsg').textContent = data.message || 'No user found.';
                errBox.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        })
        .catch(() => {
            document.getElementById('recipientErrorMsg').textContent = 'Lookup failed. Please try again.';
            errBox.classList.remove('d-none');
            submitBtn.disabled = true;
        });
    });

    input.addEventListener('input', resetLookup);
})();
</script>

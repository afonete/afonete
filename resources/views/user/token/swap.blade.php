@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.token._nav', ['active' => 'swap'])

    @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif

    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-sync-alt mr-1"></i> Swap {{ $symbol }} → Cashout
                </div>
                <div class="card-body">

                    <table class="table table-borderless table-sm mb-3">
                        <tr>
                            <td class="text-muted">Free Token Balance:</td>
                            <td class="font-weight-bold">{{ number_format($freeBal, 0) }} {{ $symbol }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Swap rate (per spec):</td>
                            <td class="font-weight-bold text-warning">1 {{ $symbol }} = ${{ number_format($swapPrice, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Token display value:</td>
                            <td>1 {{ $symbol }} = ${{ number_format($coinValue, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Example:</td>
                            <td class="text-success">200,000 {{ $symbol }} → ${{ number_format(200000 * $swapPrice, 2) }}</td>
                        </tr>
                    </table>

                    <form method="POST" action="{{ route('user.token.swap.post') }}">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Token Amount to Swap <span class="text-danger">*</span></label>
                            <input type="number" name="token_amount" id="swapInput"
                                   class="form-control form-control-lg" min="1" step="1"
                                   max="{{ $freeBal }}" required placeholder="Enter token amount">
                        </div>

                        <div class="p-3 mb-3 text-center" style="background:#f8f9fa; border-radius:6px; border:1px solid #dee2e6;">
                            <span class="text-muted">You will receive in Cashout:</span>
                            <div class="font-weight-bold text-success" style="font-size:1.5rem;" id="swapResult">$0.00</div>
                            <small class="text-muted">Cashout is withdrawable anytime (min <strong>$10</strong> per spec)</small>
                        </div>

                        <div class="alert alert-warning small py-2 d-none" id="minCashoutWarn">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            After swap, your Cashout balance must reach at least <strong>$10</strong> before you can withdraw it.
                        </div>

                        <div class="alert alert-warning small py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Swap is <strong>immediate and irreversible</strong>. Tokens become Cashout.
                        </div>

                        <button type="submit" class="btn btn-warning btn-block font-weight-bold py-2"
                                {{ $freeBal < 1 ? 'disabled' : '' }}>
                            <i class="fas fa-sync-alt mr-1"></i> Swap Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
document.getElementById('swapInput').addEventListener('input', function () {
    // Use swap_price (per spec) for the conversion, not coin_value
    const v = (parseFloat(this.value) || 0) * {{ $swapPrice }};
    document.getElementById('swapResult').textContent = '$' + v.toFixed(2);
    // Warn if total cashout would be below the $10 withdrawable minimum (per spec)
    const warnBox = document.getElementById('minCashoutWarn');
    if (v > 0 && v < 10) {
        warnBox.classList.remove('d-none');
    } else {
        warnBox.classList.add('d-none');
    }
});
</script>

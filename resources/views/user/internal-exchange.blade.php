<div class="wrapper">
@include('user.user-dashboard-base')

<div class="content-wrapper text-gray-800" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="container-fluid py-4 max-w-7xl mx-auto">

        {{-- Top sub-navigation: Cash / Coin / Trading / Invoices --}}
        <div class="mb-4">
            <x-PaymentNav/>
        </div>

        {{-- 1. HEADER BANNER --}}
        <div class="alert alert-dark d-flex align-items-center justify-content-between flex-wrap gap-3 p-4 mb-4 text-white shadow-sm" style="border-radius: 14px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(168, 85, 247, 0.4);">
            <div>
                <h3 class="font-weight-bold text-white mb-1 d-flex align-items-center gap-2">
                    <i class="fas fa-coins text-warning" style="color: #f59e0b !important;"></i> Trading Wallet — Swap &amp; Buy Tokens
                </h3>
                <p class="text-light opacity-90 small mb-0">Convert or purchase Available Tokens instantly using your Trading Wallet USD balance.</p>
            </div>
            <div class="bg-dark px-3.5 py-2 rounded text-right border border-purple shadow-sm" style="border-radius: 10px !important; border-color: #a855f7 !important;">
                <small class="text-purple font-weight-bold d-block text-uppercase" style="color: #c084fc !important; font-size: 0.7rem; letter-spacing: 1px;">Live Token Symbol</small>
                <strong class="text-white font-mono" style="font-size: 1.25rem; letter-spacing: 1px;">{{ $symbol }}</strong>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-2 shadow-sm d-flex align-items-center justify-content-between" style="border-radius: 10px;">
                <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-2 shadow-sm d-flex align-items-center justify-content-between" style="border-radius: 10px;">
                <span><i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-2 shadow-sm" style="border-radius: 10px;">{{ $errors->first() }}</div>
        @endif

        {{-- 2. TRADING WALLET & TOKEN BALANCES --}}
        <div class="row mb-4">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm border-0 h-100 p-4 bg-white" style="border-radius: 14px; border-left: 5px solid #8b5cf6 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted font-weight-bold small text-uppercase"><i class="fas fa-wallet text-purple mr-1" style="color: #8b5cf6;"></i> Trading Wallet Balance</span>
                        <span class="badge badge-purple px-2.5 py-1 text-white" style="background-color: #8b5cf6; font-size: 10px;">USD BALANCE</span>
                    </div>
                    <h2 class="font-weight-bold text-dark my-1" style="font-size: 2rem;">${{ number_format($tradingBal, 2) }}</h2>
                    <small class="text-muted">Available USD balance for swapping and purchasing tokens.</small>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100 p-4 bg-white" style="border-radius: 14px; border-left: 5px solid #10b981 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted font-weight-bold small text-uppercase"><i class="fas fa-coins text-success mr-1"></i> Available Token Balance</span>
                        <span class="badge badge-success px-2.5 py-1" style="font-size: 10px;">ACCUMULATED TOKENS</span>
                    </div>
                    <h2 class="font-weight-bold text-dark my-1" style="font-size: 2rem;">{{ number_format($availableTokenBal, 2) }} <small class="text-muted font-weight-bold" style="font-size: 1.1rem;">{{ $symbol }}</small></h2>
                    <small class="text-muted">Tokens credited and ready for release &amp; trading.</small>
                </div>
            </div>
        </div>

        {{-- 3. TRADING ACTION CARDS (SWAP & BUY TOKENS) --}}
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; overflow: hidden;">
            <div class="card-header bg-white p-3.5 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="font-weight-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-line text-purple" style="color: #8b5cf6;"></i> Trading Wallet (Swap &amp; Buy Tokens)
                </h5>
                <span class="badge badge-dark px-3 py-1 font-mono" style="font-size: 11px;">USD &rarr; {{ $symbol }}</span>
            </div>

            <div class="card-body p-4 bg-white">
                <div class="row">

                    {{-- Swap Token Card --}}
                    <div class="col-md-6 mb-4 mb-md-0">
                        <div class="p-4 rounded-2xl shadow-sm h-100" style="border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-sync text-primary mr-2"></i> Swap Token</h5>
                                <span class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 0.75rem;">${{ number_format($swapPrice, 4) }} / token</span>
                            </div>
                            <p class="text-muted small mb-3">Convert Trading Wallet USD <i class="fas fa-arrow-right text-muted mx-1"></i> Available Tokens at the official Swap Rate.</p>

                            <form method="POST" action="{{ route('user.internal-exchange.trading-action') }}" class="js-transaction-password-form">
                                @csrf
                                <input type="hidden" name="action_type" value="swap_token">

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small">Trading USD Amount ($)</label>
                                    <div class="input-group">
                                        <input type="number" name="amount" id="swapAmountInput" onkeyup="calcTokens('swap')" min="0.01" step="0.01" max="{{ $tradingBal }}" required placeholder="Available Trading: ${{ number_format($tradingBal, 2) }}" class="form-control font-weight-bold" style="border-radius: 8px 0 0 8px;">
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-dark text-white" style="border-radius: 0 8px 8px 0;">USD</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded border mb-3 small shadow-sm">
                                    <span class="text-muted d-block font-weight-bold mb-1">Tokens to Receive:</span>
                                    <strong id="swapTokenResult" class="text-primary font-weight-bold font-mono" style="font-size: 1.2rem;">0.00 {{ $symbol }}</strong>
                                </div>

                                <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2.5 shadow-sm" style="border-radius: 8px; font-size: 0.95rem;">
                                    <i class="fas fa-exchange-alt mr-1.5"></i> Execute Token Swap
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Buy Token Card --}}
                    <div class="col-md-6">
                        <div class="p-4 rounded-2xl shadow-sm h-100" style="border-radius: 12px; border: 1px solid #e2e8f0; background-color: #f8fafc;">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="font-weight-bold text-dark mb-0"><i class="fas fa-shopping-bag text-success mr-2"></i> Buy Token</h5>
                                <span class="badge badge-success px-3 py-1 font-weight-bold" style="font-size: 0.75rem;">${{ number_format($tradingPrice, 4) }} / token</span>
                            </div>
                            <p class="text-muted small mb-3">Buy Available Tokens directly with Trading USD at the official Trading Rate.</p>

                            <form method="POST" action="{{ route('user.internal-exchange.trading-action') }}" class="js-transaction-password-form">
                                @csrf
                                <input type="hidden" name="action_type" value="buy_token">

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold text-dark small">Trading USD Amount ($)</label>
                                    <div class="input-group">
                                        <input type="number" name="amount" id="buyAmountInput" onkeyup="calcTokens('buy')" min="0.01" step="0.01" max="{{ $tradingBal }}" required placeholder="Available Trading: ${{ number_format($tradingBal, 2) }}" class="form-control font-weight-bold" style="border-radius: 8px 0 0 8px;">
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold bg-dark text-white" style="border-radius: 0 8px 8px 0;">USD</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded border mb-3 small shadow-sm">
                                    <span class="text-muted d-block font-weight-bold mb-1">Tokens to Receive:</span>
                                    <strong id="buyTokenResult" class="text-success font-weight-bold font-mono" style="font-size: 1.2rem;">0.00 {{ $symbol }}</strong>
                                </div>

                                <input type="hidden" name="transaction_password" class="js-transaction-password-value">

                                <button type="submit" class="btn btn-success btn-block font-weight-bold py-2.5 shadow-sm" style="border-radius: 8px; font-size: 0.95rem;">
                                    <i class="fas fa-coins mr-1.5"></i> Buy Tokens Now
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- 4. TRADING HISTORY TABLE (Only Trading Wallet Swap & Buy Tokens) --}}
        <div class="card shadow-sm border-0 mt-4 bg-white" style="border-radius: 14px; overflow: hidden;">
            <div class="card-header bg-white font-weight-bold py-3 text-dark border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <i class="fas fa-history text-warning mr-1.5"></i> Trading Wallet History (Swap &amp; Buy Tokens)
                </div>
                <span class="badge badge-secondary px-2.5 py-1 text-uppercase" style="font-size: 10px;">5 Records Per Page</span>
            </div>
            <div class="card-body p-0">
                @if($transactions->isEmpty())
                    <p class="text-muted p-4 mb-0 text-center font-weight-bold">
                        <i class="fas fa-folder-open text-2xl d-block mb-1 opacity-50"></i> No token swap or purchase transactions recorded yet.
                    </p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 text-dark" style="font-size: 0.85rem;">
                        <thead class="bg-light uppercase font-weight-bold border-bottom" style="font-size: 0.75rem;">
                            <tr>
                                <th class="py-3 px-3">#</th>
                                <th class="py-3 px-3">Transaction No</th>
                                <th class="py-3 px-3">Type</th>
                                <th class="py-3 px-3">Details</th>
                                <th class="py-3 px-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $i => $t)
                                @php
                                    $d = json_decode($t->transaction_details, true) ?: [];
                                    $usdAmt = (float) ($d['usd_amount'] ?? 0);
                                    $tokens = (float) ($d['tokens'] ?? 0);
                                    $rate   = (float) ($d['rate'] ?? 0);
                                @endphp
                                <tr>
                                    <td class="py-3 px-3 font-weight-bold text-muted">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                                    <td class="py-3 px-3"><strong class="font-mono text-dark">{{ $t->transaction_no }}</strong></td>
                                    <td class="py-3 px-3">
                                        @if($t->transaction_type === 'TOKEN_SWAP')
                                            <span class="badge badge-primary px-2.5 py-1" style="font-size: 10px;">SWAP</span>
                                        @elseif($t->transaction_type === 'TOKEN_PURCHASE')
                                            <span class="badge badge-success px-2.5 py-1" style="font-size: 10px;">PURCHASE</span>
                                        @else
                                            <span class="badge badge-dark px-2.5 py-1" style="font-size: 10px;">{{ $t->transaction_type }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 font-weight-bold">
                                        @if($t->transaction_type === 'TOKEN_SWAP')
                                            Swapped <strong>${{ number_format($usdAmt, 2) }} USD</strong> from Trading Wallet for <strong>{{ number_format($tokens, 2) }} {{ $symbol }}</strong> (Rate: ${{ number_format($rate, 4) }}/token)
                                        @elseif($t->transaction_type === 'TOKEN_PURCHASE')
                                            Purchased <strong>{{ number_format($tokens, 2) }} {{ $symbol }}</strong> for <strong>${{ number_format($usdAmt, 2) }} USD</strong> from Trading Wallet (Rate: ${{ number_format($rate, 4) }}/token)
                                        @else
                                            {{ $t->transaction_details }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-right font-mono text-muted"><small>{{ $t->created_at ? $t->created_at->format('Y-m-d H:i') : '—' }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-3 d-flex justify-content-center border-top">
                        {{ $transactions->links('pagination::bootstrap-4') }}
                    </div>
                @endif
                @endif
            </div>
        </div>

    </div>
</div>
</div>

@include('user.components.transaction-password-modal')

<script>
function calcTokens(type) {
    const swapPrice    = {{ $swapPrice }};
    const tradingPrice = {{ $tradingPrice }};
    const symbol       = '{{ $symbol }}';

    if (type === 'swap') {
        const amt = parseFloat(document.getElementById('swapAmountInput').value) || 0;
        const tokens = amt / swapPrice;
        document.getElementById('swapTokenResult').textContent = tokens.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 }) + ' ' + symbol;
    } else if (type === 'buy') {
        const amt = parseFloat(document.getElementById('buyAmountInput').value) || 0;
        const tokens = amt / tradingPrice;
        document.getElementById('buyTokenResult').textContent = tokens.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 4 }) + ' ' + symbol;
    }
}
</script>
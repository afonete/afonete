<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Deposit</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #0f1115; color: #f8fafc; }
        .wrap { max-width: 1050px; margin: 0 auto; padding: 26px 16px; }
        .top { display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:18px; }
        .btn { border:0; border-radius:8px; padding:10px 14px; cursor:pointer; font-weight:700; text-decoration:none; display:inline-block; }
        .btn-blue { background:#2563eb; color:#fff; }
        .btn-green { background:#16a34a; color:#fff; }
        .btn-dark { background:#1f2937; color:#fff; border:1px solid #374151; }
        .btn-full { width:100%; }
        .grid { display:grid; grid-template-columns: 1fr 360px; gap:22px; }
        .card { background:#161a22; border:1px solid #293244; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.28); overflow:hidden; }
        .card-h { padding:18px 20px; border-bottom:1px solid #293244; background:#111827; }
        .card-b { padding:20px; }
        .muted { color:#94a3b8; }
        .alert { padding:12px; border-radius:12px; margin-bottom:14px; }
        .success { background:#052e16; border:1px solid #15803d; color:#bbf7d0; }
        .error { background:#450a0a; border:1px solid #b91c1c; color:#fecaca; }
        .info { background:#082f49; border:1px solid #0369a1; color:#bae6fd; }
        .warn { background:#431407; border:1px solid #9a3412; color:#fed7aa; }
        label { display:block; margin:0 0 7px; font-weight:700; }
        input, select, textarea { width:100%; background:#0b0f16; border:1px solid #334155; color:#f8fafc; border-radius:10px; padding:12px; outline:none; }
        input:focus, select:focus, textarea:focus { border-color:#60a5fa; }
        .field { margin-bottom:15px; }
        .value { background:#0b0f16; border:1px solid #334155; padding:12px; border-radius:10px; word-break:break-all; color:#fde047; font-family:ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
        .qr { background:#fff; padding:10px; border-radius:12px; width:220px; height:220px; object-fit:contain; }
        .method-meta { font-size:13px; color:#cbd5e1; margin-top:7px; }
        @media (max-width: 850px) { .grid { grid-template-columns: 1fr; } .top { align-items:flex-start; flex-direction:column; } }
    </style>
</head>
<body>
@php
    $firstWallet = $wallets->first();
@endphp
<div class="wrap">
    <div class="top">
        <div>
            <h1 style="margin:0 0 6px;">Manual Deposit</h1>
            <div class="muted">Use this page for Deposit & Pay Later. Your dashboard access is enabled after admin approval.</div>
        </div>
        <div>
            <a class="btn btn-dark" href="{{ route('user.venture') }}">Back to UVP</a>
            <a class="btn btn-dark" href="{{ route('user.package') }}">Back to FC</a>
        </div>
    </div>

    @if(session('message')) <div class="alert success">{{ session('message') }}</div> @endif
    @if(session('error')) <div class="alert error">{{ session('error') }}</div> @endif
    @if($errors->any()) <div class="alert error">{{ $errors->first() }}</div> @endif

    @if($pendingDeposit)
        <div class="alert info">
            You already have a pending manual deposit request: <strong>{{ $pendingDeposit->transaction_id }}</strong>.
            <a href="{{ route('user.manual-deposit.waiting', $pendingDeposit->id) }}" style="color:#fff; text-decoration:underline; font-weight:700;">Open waiting page</a>
        </div>
    @endif

    @if($wallets->isEmpty())
        <div class="card">
            <div class="card-b">
                <div class="alert warn" style="margin:0;">No manual deposit wallets are configured yet. Please contact admin.</div>
            </div>
        </div>
    @else
    <form method="POST" action="{{ route('user.manual-deposit.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid">
            <div class="card">
                <div class="card-h">
                    <strong>1. Choose manual deposit method</strong><br>
                    <span class="muted">Select the wallet/account you will pay to, then upload proof.</span>
                </div>
                <div class="card-b">
                    <div class="field">
                        <label for="wallet_id">Deposit method</label>
                        <select name="wallet_id" id="wallet_id" required>
                            <option value="">Select method</option>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}"
                                        {{ (string) old('wallet_id') === (string) $wallet->id ? 'selected' : '' }}
                                        data-type="{{ $wallet->kindLabel() }}"
                                        data-label="{{ $wallet->label }}"
                                        data-network="{{ $wallet->network }}"
                                        data-currency="{{ $wallet->currency }}"
                                        data-address="{{ $wallet->wallet_address }}"
                                        data-min="{{ $wallet->min_amount }}"
                                        data-max="{{ $wallet->max_amount }}"
                                        data-qr="{{ $wallet->qrImageUrl(220) }}"
                                        data-instructions="{{ $wallet->instructions ?: $wallet->notes ?: '' }}">
                                    {{ $wallet->kindLabel() }} — {{ $wallet->label ?: $wallet->currency }} {{ $wallet->network ? '(' . $wallet->network . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" min="{{ $minDeposit }}" step="0.01" required placeholder="Minimum ${{ number_format($minDeposit, 2) }}" value="{{ old('amount') }}">
                    </div>

                    <div class="field">
                        <label for="paymentaccount">Wallet/account you paid from</label>
                        <input type="text" id="paymentaccount" name="paymentaccount" required placeholder="Sender wallet address / Advcash / Perfect Money account" value="{{ old('paymentaccount') }}">
                    </div>

                    <div class="field">
                        <label for="proof_of_payment">Proof of payment</label>
                        <input type="file" id="proof_of_payment" name="proof_of_payment" required accept=".jpg,.jpeg,.png,.pdf">
                        <div class="method-meta">Upload screenshot or PDF. Max 4MB.</div>
                    </div>

                    <div class="alert info">
                        After submitting, you will see a waiting page with a 15-minute countdown. Admin will also see the same countdown while reviewing your deposit.
                    </div>

                    <button class="btn btn-blue btn-full" type="submit">Submit Deposit for Admin Approval</button>
                </div>
            </div>

            <div class="card">
                <div class="card-h">
                    <strong>2. Send payment to this account</strong><br>
                    <span class="muted">Confirm address and amount before sending.</span>
                </div>
                <div class="card-b">
                    <div class="field">
                        <div class="muted" style="margin-bottom:6px;">Selected method</div>
                        <div id="methodTitle" style="font-weight:800;">Select a method</div>
                        <div id="methodMeta" class="method-meta"></div>
                    </div>

                    <div class="field">
                        <div class="muted" style="margin-bottom:6px;">Pay to</div>
                        <div id="depositAddress" class="value">—</div>
                        <button class="btn btn-green" type="button" id="copyAddress" style="margin-top:8px;">Copy address/account</button>
                    </div>

                    <div class="field" style="text-align:center;">
                        <img id="qrImg" class="qr" src="" alt="QR code" style="display:none;">
                    </div>

                    <div id="instructionsBox" class="alert warn" style="display:none;"></div>
                </div>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
(function(){
    var select = document.getElementById('wallet_id');
    if (!select) return;
    var addressEl = document.getElementById('depositAddress');
    var titleEl = document.getElementById('methodTitle');
    var metaEl = document.getElementById('methodMeta');
    var qrImg = document.getElementById('qrImg');
    var instructionsBox = document.getElementById('instructionsBox');
    var amountInput = document.getElementById('amount');

    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) return navigator.clipboard.writeText(text);
        var t = document.createElement('textarea');
        t.value = text;
        document.body.appendChild(t);
        t.select();
        document.execCommand('copy');
        document.body.removeChild(t);
        return Promise.resolve();
    }

    function updateMethod() {
        var opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) {
            titleEl.textContent = 'Select a method';
            metaEl.textContent = '';
            addressEl.textContent = '—';
            qrImg.style.display = 'none';
            instructionsBox.style.display = 'none';
            return;
        }

        var type = opt.getAttribute('data-type') || 'Manual';
        var label = opt.getAttribute('data-label') || '';
        var network = opt.getAttribute('data-network') || '';
        var currency = opt.getAttribute('data-currency') || '';
        var address = opt.getAttribute('data-address') || '';
        var min = opt.getAttribute('data-min') || '';
        var max = opt.getAttribute('data-max') || '';
        var qr = opt.getAttribute('data-qr') || '';
        var instructions = opt.getAttribute('data-instructions') || '';

        titleEl.textContent = type + (label ? ' — ' + label : '');
        metaEl.textContent = [currency, network, min ? 'Min $' + parseFloat(min).toFixed(2) : '', max ? 'Max $' + parseFloat(max).toFixed(2) : ''].filter(Boolean).join(' · ');
        addressEl.textContent = address || '—';

        if (min) amountInput.min = parseFloat(min).toFixed(2);
        if (max) amountInput.max = parseFloat(max).toFixed(2); else amountInput.removeAttribute('max');

        if (qr && address) {
            qrImg.src = qr;
            qrImg.style.display = 'inline-block';
        } else {
            qrImg.style.display = 'none';
        }

        if (instructions) {
            instructionsBox.textContent = instructions;
            instructionsBox.style.display = '';
        } else {
            instructionsBox.style.display = 'none';
        }
    }

    document.getElementById('copyAddress').addEventListener('click', function(){
        var btn = this;
        copyText(addressEl.textContent.trim()).then(function(){
            btn.textContent = 'Copied';
            setTimeout(function(){ btn.textContent = 'Copy address/account'; }, 1400);
        });
    });

    select.addEventListener('change', updateMethod);
    updateMethod();
})();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Automatic USDT TRC20 Payment</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #0f1115; color: #f8fafc; }
        .wrap { max-width: 980px; margin: 0 auto; padding: 28px 16px; }
        .top { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 18px; }
        .btn { border: 0; border-radius: 8px; padding: 10px 14px; cursor: pointer; font-weight: 700; text-decoration: none; display: inline-block; }
        .btn-blue { background: #2563eb; color: white; }
        .btn-green { background: #16a34a; color: white; }
        .btn-dark { background: #1f2937; color: white; border: 1px solid #374151; }
        .grid { display: grid; grid-template-columns: minmax(260px, 360px) 1fr; gap: 22px; }
        .card { background: #161a22; border: 1px solid #293244; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.32); overflow: hidden; }
        .card-h { padding: 18px 20px; border-bottom: 1px solid #293244; background: #111827; }
        .card-b { padding: 20px; }
        .muted { color: #94a3b8; }
        .label { color: #94a3b8; font-size: 13px; margin-bottom: 6px; }
        .value { background: #0b0f16; border: 1px solid #334155; padding: 12px; border-radius: 10px; word-break: break-all; color: #fde047; font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
        .amount { font-size: 40px; line-height: 1; font-weight: 900; color: #22c55e; margin: 8px 0 4px; }
        .timer { margin: 12px 0; padding: 14px; border-radius: 14px; background: #111827; border: 1px solid #334155; }
        .timer-num { font-size: 34px; font-weight: 900; letter-spacing: .04em; color: #facc15; }
        .pill { display: inline-block; border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 800; letter-spacing: .03em; }
        .pending { background: #78350f; color: #fef3c7; }
        .approved { background: #14532d; color: #dcfce7; }
        .expired { background: #7f1d1d; color: #fee2e2; }
        .qr { background: white; padding: 12px; border-radius: 14px; width: 260px; height: 260px; object-fit: contain; }
        .warn { background: #431407; color: #fed7aa; border: 1px solid #9a3412; border-radius: 12px; padding: 12px; margin-top: 14px; }
        .info { background: #082f49; color: #bae6fd; border: 1px solid #0369a1; border-radius: 12px; padding: 12px; margin-top: 14px; }
        .row { display: flex; gap: 8px; align-items: center; margin-top: 8px; flex-wrap: wrap; }
        ol { margin-top: 8px; padding-left: 20px; }
        li { margin: 7px 0; }
        @media (max-width: 800px) { .grid { grid-template-columns: 1fr; } .top { align-items: flex-start; flex-direction: column; } .qr { width: 220px; height: 220px; } .amount { font-size: 34px; } }
    </style>
</head>
<body>
@php
    $amount = (float) $deposit->amount_deposited;
    $address = $deposit->deposit_address;
    $packageName = $deposit->package_name ?: ($deposit->package_type . ' Package');
    $paymentUrl = route('payment.directPackage.show', $deposit->id);
    $statusUrl = route('payment.directPackage.status', $deposit->id);
    $qrPayload = $address; // Keep QR wallet-compatible. Amount is displayed clearly beside it.
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($qrPayload) . '&margin=10';
    $expiresAtIso = $deposit->expires_at ? $deposit->expires_at->toIso8601String() : null;
    $secondsRemaining = $deposit->expires_at ? max(0, now()->diffInSeconds($deposit->expires_at, false)) : null;
    $isExpired = !$deposit->activated_payment_id && $deposit->status === 'pending' && $deposit->expires_at && now()->greaterThan($deposit->expires_at);
@endphp
<div class="wrap">
    <div class="top">
        <div>
            <h1 style="margin:0 0 6px;">Automatic USDT TRC20 Payment</h1>
            <div class="muted">Send USDT directly on TRON/TRC20.</div>
        </div>
        <div class="row">
            <a class="btn btn-dark" href="{{ $deposit->package_type === 'FC' ? route('user.package') : route('user.venture') }}">Back to packages</a>
            <a class="btn btn-dark" href="{{ route('user.dashboard') }}">Dashboard</a>
        </div>
    </div>

    @if(session('error'))
        <div class="warn" style="margin-bottom:14px;">{{ session('error') }}</div>
    @endif

    <div class="grid">
        <div class="card">
            <div class="card-h">
                <strong>Scan QR Code</strong><br>
                <span class="muted">TRON / TRC20 USDT address</span>
            </div>
            <div class="card-b" style="text-align:center;">
                <img class="qr" src="{{ $qrUrl }}" alt="USDT TRC20 payment QR code">
                <div class="info" style="text-align:left;">
                    <strong>Before sending, confirm:</strong><br>
                    Amount: <strong>{{ number_format($amount, 2) }} USDT</strong><br>
                    Network: <strong>TRON / TRC20</strong><br>
                    Package: <strong>{{ $packageName }}</strong>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-h">
                <span id="statusBadge" class="pill {{ $deposit->status === 'approved' ? 'approved' : ($isExpired ? 'expired' : 'pending') }}">
                    {{ $isExpired && $deposit->status === 'pending' ? 'EXPIRED' : strtoupper($deposit->status) }}
                </span>
                <h2 style="margin:12px 0 0;">{{ $packageName }}</h2>
                <div class="muted">Reference: {{ $deposit->transaction_id }}</div>
            </div>
            <div class="card-b">
                <div class="label">Exact amount to pay</div>
                <div class="amount">{{ number_format($amount, 2) }} USDT</div>
                <div class="muted">Send this exact amount to avoid delayed matching.</div>

                <div class="timer">
                    <div class="label">Payment window</div>
                    <div id="countdown" class="timer-num">{{ $secondsRemaining !== null ? gmdate('i:s', $secondsRemaining) : '15:00' }}</div>
                    <div id="timerHelp" class="muted">
                        Pay within 15 minutes. If the timer reaches 00:00, do not send to this invoice; create a new payment request.
                    </div>
                </div>

                <div style="height:18px"></div>

                <div class="label">USDT TRC20 payment address</div>
                <div id="payAddress" class="value">{{ $address }}</div>
                <div class="row">
                    <button class="btn btn-green" type="button" data-copy="payAddress">Copy address</button>
                    <button class="btn btn-dark" type="button" data-copy-text="{{ $paymentUrl }}">Copy payment URL</button>
                </div>

                <div class="warn">
                    <strong>Important:</strong> Only send <strong>USDT on TRON/TRC20</strong>. Do not send ERC20, BEP20, TRX, BTC, or any other asset to this address.
                </div>

                <div id="expiredBox" class="warn" style="{{ $isExpired ? '' : 'display:none;' }}">
                    <strong>This payment window has expired.</strong><br>
                    If you already sent USDT before the timer reached 00:00, wait for the scanner to confirm it. Otherwise, do not pay this invoice.
                    <form action="{{ route('payment.directPackage') }}" method="POST" style="margin-top:10px;">
                        @csrf
                        <input type="hidden" name="package_type" value="{{ $deposit->package_type }}">
                        <input type="hidden" name="package_id" value="{{ $deposit->package_id }}">
                        <input type="hidden" name="amount" value="{{ number_format($amount, 2, '.', '') }}">
                        <button type="submit" class="btn btn-blue">Create new 15-minute payment</button>
                    </form>
                </div>

                <div class="info">
                    <strong>What happens next?</strong>
                    <ol>
                        <li>Pay <strong>{{ number_format($amount, 2) }} USDT</strong> to the address above before the 15-minute timer ends.</li>
                        <li>The system scans TronGrid automatically every 2 minutes.</li>
                        <li>If the payment is received inside the timer window, your <strong>{{ $packageName }}</strong> package is activated automatically.</li>
                    </ol>
                </div>

                <div class="row" style="margin-top:18px;">
                    <button id="checkBtn" class="btn btn-blue" type="button">I've paid — check status</button>
                    <span id="statusText" class="muted">Waiting for blockchain confirmation...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }
        var input = document.createElement('textarea');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        return Promise.resolve();
    }

    document.querySelectorAll('[data-copy]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var el = document.getElementById(btn.getAttribute('data-copy'));
            copyText(el ? el.textContent.trim() : '').then(function(){
                btn.textContent = 'Copied';
                setTimeout(function(){ btn.textContent = 'Copy address'; }, 1600);
            });
        });
    });

    document.querySelectorAll('[data-copy-text]').forEach(function(btn){
        btn.addEventListener('click', function(){
            copyText(btn.getAttribute('data-copy-text')).then(function(){
                btn.textContent = 'URL copied';
                setTimeout(function(){ btn.textContent = 'Copy payment URL'; }, 1600);
            });
        });
    });

    var statusUrl = @json($statusUrl);
    var expiresAt = @json($expiresAtIso);
    var statusText = document.getElementById('statusText');
    var statusBadge = document.getElementById('statusBadge');
    var checkBtn = document.getElementById('checkBtn');
    var countdown = document.getElementById('countdown');
    var timerHelp = document.getElementById('timerHelp');
    var expiredBox = document.getElementById('expiredBox');

    function pad(n) { return String(n).padStart(2, '0'); }
    function formatSeconds(total) {
        total = Math.max(0, total || 0);
        var m = Math.floor(total / 60);
        var s = total % 60;
        return pad(m) + ':' + pad(s);
    }
    function renderTimer() {
        if (!expiresAt || !countdown) return;
        var remaining = Math.floor((new Date(expiresAt).getTime() - Date.now()) / 1000);
        countdown.textContent = formatSeconds(remaining);
        if (remaining <= 0) {
            countdown.textContent = '00:00';
            countdown.style.color = '#fca5a5';
            if (timerHelp) timerHelp.textContent = 'This invoice has expired. Do not send a new payment to this invoice. Create a new 15-minute payment request below.';
            if (expiredBox) expiredBox.style.display = '';
            if (statusBadge && statusBadge.textContent.trim() === 'PENDING') {
                statusBadge.textContent = 'EXPIRED';
                statusBadge.className = 'pill expired';
            }
        }
    }

    function checkStatus(manual) {
        if (manual) statusText.textContent = 'Checking blockchain status...';
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r){ return r.json(); })
            .then(function(data){
                statusBadge.textContent = String(data.status || 'pending').toUpperCase();
                if (typeof data.seconds_remaining === 'number') {
                    countdown.textContent = formatSeconds(data.seconds_remaining);
                }
                if (data.activated) {
                    statusBadge.className = 'pill approved';
                    statusText.textContent = 'Payment confirmed. Package activated. Redirecting...';
                    setTimeout(function(){ window.location.href = data.redirect_url; }, 1800);
                } else if (data.expired && data.status === 'pending') {
                    statusBadge.textContent = 'EXPIRED';
                    statusBadge.className = 'pill expired';
                    statusText.textContent = 'Invoice expired. If you paid before expiry, wait for confirmation; otherwise create a new payment.';
                    if (expiredBox) expiredBox.style.display = '';
                } else if (data.status === 'approved') {
                    statusBadge.className = 'pill approved';
                    statusText.textContent = 'Payment confirmed. Activation is being finalized...';
                } else {
                    statusBadge.className = 'pill pending';
                    statusText.textContent = 'Still pending. Keep this page open or come back using the copied URL.';
                }
            })
            .catch(function(){
                statusText.textContent = 'Could not check status right now. Please try again.';
            });
    }

    renderTimer();
    setInterval(renderTimer, 1000);
    checkBtn.addEventListener('click', function(){ checkStatus(true); });
    setInterval(function(){ checkStatus(false); }, 30000);
})();
</script>
</body>
</html>

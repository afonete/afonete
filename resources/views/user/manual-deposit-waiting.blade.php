<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Deposit Approval</title>
    <style>
        * { box-sizing: border-box; }
        body { margin:0; font-family:Arial, Helvetica, sans-serif; background:#0f1115; color:#f8fafc; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:18px; }
        .card { max-width:720px; width:100%; background:#161a22; border:1px solid #293244; border-radius:18px; box-shadow:0 20px 60px rgba(0,0,0,.32); overflow:hidden; }
        .head { background:#111827; border-bottom:1px solid #293244; padding:22px; }
        .body { padding:22px; }
        .muted { color:#94a3b8; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin:16px 0; }
        .box { background:#0b0f16; border:1px solid #334155; border-radius:12px; padding:14px; }
        .label { color:#94a3b8; font-size:12px; text-transform:uppercase; font-weight:800; margin-bottom:5px; }
        .value { font-size:18px; font-weight:900; }
        .timer { text-align:center; background:#111827; border:1px solid #334155; border-radius:16px; padding:18px; margin:18px 0; }
        .timer-num { font-size:48px; color:#facc15; font-weight:900; letter-spacing:.04em; }
        .pill { display:inline-block; border-radius:999px; padding:6px 12px; font-size:12px; font-weight:900; }
        .pending { background:#78350f; color:#fef3c7; }
        .approved { background:#14532d; color:#dcfce7; }
        .rejected, .cancelled, .expired { background:#7f1d1d; color:#fee2e2; }
        .under-review { background:#1e3a8a; color:#dbeafe; }
        .btn { border:0; border-radius:8px; padding:10px 14px; cursor:pointer; font-weight:700; text-decoration:none; display:inline-block; }
        .btn-blue { background:#2563eb; color:white; }
        .btn-dark { background:#1f2937; color:white; border:1px solid #374151; }
        .alert { padding:12px; border-radius:12px; margin-top:14px; }
        .info { background:#082f49; border:1px solid #0369a1; color:#bae6fd; }
        .warn { background:#431407; border:1px solid #9a3412; color:#fed7aa; }
        .actions { display:flex; gap:10px; flex-wrap:wrap; margin-top:18px; }
        @media(max-width:650px){ .grid{ grid-template-columns:1fr; } .timer-num{font-size:38px;} }
    </style>
</head>
<body>
@php
    $statusUrl = route('user.manual-deposit.status', $deposit->id);
    $expiresAtIso = $deposit->expires_at ? $deposit->expires_at->toIso8601String() : null;
    $secondsRemaining = $deposit->expires_at ? max(0, now()->diffInSeconds($deposit->expires_at, false)) : null;
    $isExpired = $deposit->status === 'pending' && $deposit->expires_at && now()->greaterThan($deposit->expires_at);
@endphp
<div class="card">
    <div class="head">
        <span id="statusBadge" class="pill {{ $isExpired ? 'expired' : $deposit->status }}">
            {{ $isExpired ? 'EXPIRED' : strtoupper($deposit->status) }}
        </span>
        <h1 style="margin:12px 0 6px;">Waiting for Admin Approval</h1>
        <div class="muted">Your manual deposit request has been submitted.</div>
    </div>
    <div class="body">
        @if(session('message'))
            <div class="alert info">{{ session('message') }}</div>
        @endif

        <div class="grid">
            <div class="box">
                <div class="label">Reference</div>
                <div class="value" style="font-size:14px; word-break:break-all;">{{ $deposit->transaction_id }}</div>
            </div>
            <div class="box">
                <div class="label">Amount</div>
                <div class="value">${{ number_format($deposit->amount_deposited, 2) }} {{ $deposit->currency_type }}</div>
            </div>
            <div class="box">
                <div class="label">Method</div>
                <div class="value">{{ $deposit->deposit_method }}</div>
            </div>
            <div class="box">
                <div class="label">Submitted</div>
                <div class="value">{{ $deposit->created_at->format('Y-m-d H:i') }}</div>
            </div>
        </div>

        <div class="timer">
            <div class="label">15-minute review countdown</div>
            <div id="countdown" class="timer-num">{{ $secondsRemaining !== null ? gmdate('i:s', $secondsRemaining) : '15:00' }}</div>
            <div id="timerHelp" class="muted">Admin can see this same countdown while reviewing your proof.</div>
        </div>

        <div id="expiredBox" class="alert warn" style="{{ $isExpired ? '' : 'display:none;' }}">
            The 15-minute countdown has ended. If you already submitted valid proof, please wait for admin review. If needed, create a new manual deposit request.
        </div>

        <div id="statusText" class="alert info">
            We are checking your approval status automatically. You can keep this page open.
        </div>

        <div class="actions">
            <button id="checkBtn" class="btn btn-blue" type="button">Check status now</button>
            <a class="btn btn-dark" href="{{ route('user.manual-deposit') }}">Create another deposit</a>
            <a class="btn btn-dark" href="{{ route('user.venture') }}">Back to packages</a>
        </div>
    </div>
</div>

<script>
(function(){
    var statusUrl = @json($statusUrl);
    var expiresAt = @json($expiresAtIso);
    var countdown = document.getElementById('countdown');
    var timerHelp = document.getElementById('timerHelp');
    var expiredBox = document.getElementById('expiredBox');
    var statusBadge = document.getElementById('statusBadge');
    var statusText = document.getElementById('statusText');
    var checkBtn = document.getElementById('checkBtn');

    function pad(n) { return String(n).padStart(2, '0'); }
    function formatSeconds(total) {
        total = Math.max(0, total || 0);
        return pad(Math.floor(total / 60)) + ':' + pad(total % 60);
    }
    function setBadge(text, cls) {
        statusBadge.textContent = text;
        statusBadge.className = 'pill ' + cls;
    }
    function renderTimer() {
        if (!expiresAt) return;
        var remaining = Math.floor((new Date(expiresAt).getTime() - Date.now()) / 1000);
        countdown.textContent = formatSeconds(remaining);
        if (remaining <= 0) {
            countdown.textContent = '00:00';
            countdown.style.color = '#fca5a5';
            timerHelp.textContent = 'Countdown ended. Admin may still review the proof you submitted.';
            expiredBox.style.display = '';
            if (statusBadge.textContent.trim() === 'PENDING') setBadge('EXPIRED', 'expired');
        }
    }
    function checkStatus(manual) {
        if (manual) statusText.textContent = 'Checking approval status...';
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if (typeof data.seconds_remaining === 'number') countdown.textContent = formatSeconds(data.seconds_remaining);

                if (data.status === 'approved') {
                    setBadge('APPROVED', 'approved');
                    statusText.textContent = 'Approved. Redirecting to dashboard...';
                    setTimeout(function(){ window.location.href = data.redirect_url; }, 1200);
                } else if (data.status === 'under-review') {
                    setBadge('UNDER REVIEW', 'under-review');
                    statusText.textContent = 'Admin is reviewing your deposit.';
                } else if (data.status === 'rejected' || data.status === 'cancelled') {
                    setBadge(String(data.status).toUpperCase(), data.status);
                    statusText.textContent = 'Your deposit was ' + data.status + '. Please contact support or submit a new request.';
                } else if (data.expired) {
                    setBadge('EXPIRED', 'expired');
                    expiredBox.style.display = '';
                    statusText.textContent = 'Countdown ended. Please wait for admin review if you already submitted payment proof.';
                } else {
                    setBadge('PENDING', 'pending');
                    statusText.textContent = 'Still pending admin approval.';
                }
            })
            .catch(function(){ statusText.textContent = 'Could not check status right now. Please refresh or try again.'; });
    }

    renderTimer();
    setInterval(renderTimer, 1000);
    checkBtn.addEventListener('click', function(){ checkStatus(true); });
    setInterval(function(){ checkStatus(false); }, 15000);
})();
</script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bifonex Contract Summary</title>
    <style>
        @page { margin: 36px 42px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #0f172a; font-size: 11px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 14px; }
        .brand { font-size: 20px; font-weight: 800; letter-spacing: 1px; color: #0f172a; }
        .subtitle { font-size: 11px; color: #475569; margin-top: 3px; font-weight: 700; }
        .meta { width: 100%; border-collapse: collapse; margin: 10px 0 14px; }
        .meta td { border: 1px solid #cbd5e1; padding: 5px 8px; vertical-align: top; font-size: 10px; }
        .meta .label { width: 28%; background: #f1f5f9; font-weight: 700; color: #334155; }
        h1, h2, h3, h4, h5, h6 { color: #0f172a; font-weight: 700; margin: 12px 0 6px; }
        p { margin: 0 0 6px; text-align: justify; line-height: 1.6; }
        ol, ul { margin: 4px 0 8px 18px; padding: 0; }
        li { margin-bottom: 5px; text-align: justify; }
        strong, b { font-weight: 700; color: #0f172a; }
        em { color: #475569; }
        .signature-section { margin-top: 24px; page-break-inside: avoid; background: #f8fafc; border: 1px solid #cbd5e1; padding: 12px; border-radius: 6px; }
        .signature-box { width: 280px; min-height: 70px; border: 1px dashed #94a3b8; padding: 4px; margin-top: 6px; background: #ffffff; text-align: center; }
        .signature-box img { max-width: 260px; max-height: 65px; }
        .signature-line { width: 280px; border-top: 1px solid #0f172a; margin-top: 6px; padding-top: 4px; font-weight: 700; font-size: 10px; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; font-size: 8px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">BIFONEX</div>
        <div class="subtitle">Contract Summary — Independent Marketing Affiliate (IMA) Agreement</div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Company</td>
            <td><strong>Bifonex</strong> (the "COMPANY" / Disclosing Party)</td>
        </tr>
        <tr>
            <td class="label">Mr./ Mrs. / Ms.</td>
            <td><strong>{{ $userName ?? 'N/A' }}</strong> @if(!empty($userHandle)) (@ {{ $userHandle }}) @endif</td>
        </tr>
        <tr>
            <td class="label">Nationality</td>
            <td><strong>{{ $userCountry ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <td class="label">User ID</td>
            <td>{{ $transferCode ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Contract Status</td>
            <td>{{ $contract ? 'SIGNED & VERIFIED' . ($contract->created_at ? ' · Signed ' . $contract->created_at->format('Y-m-d H:i') : '') : 'NOT YET SIGNED' }}</td>
        </tr>
        <tr>
            <td class="label">Generated</td>
            <td>{{ now()->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>

    {!! $summaryHtml !!}

    @if(!empty($signatureDataUri ?? null))
        <div class="signature-section">
            <strong>Signed by:</strong> {{ $userName ?? 'N/A' }}
            <div class="signature-box"><img src="{{ $signatureDataUri }}" alt="User signature"></div>
            <div class="signature-line">
                {{ $userName ?? '' }} — Signed {{ $contract && $contract->created_at ? $contract->created_at->format('Y-m-d H:i') : '' }}
            </div>
        </div>
    @endif

    <div class="footer">
        Bifonex — Contract Summary (for convenience only; the full signed agreement prevails) · Generated {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>

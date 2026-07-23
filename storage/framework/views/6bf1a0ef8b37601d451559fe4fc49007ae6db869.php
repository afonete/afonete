<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bifonex Contract</title>
    <style>
        @page  { margin: 36px 42px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #111827; font-size: 12px; line-height: 1.55; }
        .header { text-align: center; border-bottom: 2px solid #111827; padding-bottom: 12px; margin-bottom: 18px; }
        .brand { font-size: 24px; font-weight: 800; letter-spacing: 1px; }
        .subtitle { font-size: 14px; color: #374151; margin-top: 4px; }
        .meta { width: 100%; border-collapse: collapse; margin: 12px 0 18px; }
        .meta td { border: 1px solid #d1d5db; padding: 7px 8px; vertical-align: top; }
        .meta .label { width: 28%; background: #f3f4f6; font-weight: 700; }
        h2 { font-size: 15px; margin: 16px 0 8px; border-bottom: 1px solid #d1d5db; padding-bottom: 4px; }
        p { margin: 0 0 8px; text-align: justify; }
        ol { margin: 4px 0 10px 18px; padding: 0; }
        li { margin-bottom: 6px; text-align: justify; }
        .signature-section { margin-top: 34px; page-break-inside: avoid; }
        .signature-box { width: 300px; min-height: 94px; border: 1px solid #d1d5db; padding: 8px; margin-top: 8px; }
        .signature-box img { max-width: 280px; max-height: 80px; }
        .signature-line { width: 300px; border-top: 1px solid #111827; margin-top: 8px; padding-top: 5px; }
        .small { font-size: 10px; color: #4b5563; }
        .footer { position: fixed; bottom: -16px; left: 0; right: 0; text-align: center; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
<?php
    $signedAt = $contract->created_at ? $contract->created_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i');
?>
<div class="header">
    <div class="brand">BIFONEX CONTRACT</div>
    <div class="subtitle">Independent Marketing Affiliate / Client Agreement</div>
</div>

<table class="meta">
    <tr>
        <td class="label">Client Name</td>
        <td><?php echo e($user->name ?? $contract->name); ?></td>
    </tr>
    <tr>
        <td class="label">Username / ID</td>
        <td><?php echo e($user->user ?? '—'); ?> / #<?php echo e($user->id ?? $contract->user_id); ?></td>
    </tr>
    <tr>
        <td class="label">Email</td>
        <td><?php echo e($user->email ?? '—'); ?></td>
    </tr>
    <tr>
        <td class="label">Country / Nationality</td>
        <td><?php echo e($user->country ?? '—'); ?></td>
    </tr>
    <tr>
        <td class="label">Signed Date</td>
        <td><?php echo e($signedAt); ?></td>
    </tr>
</table>

<h2>Agreement</h2>
<p>
    This Bifonex Contract, together with the Compensation Plan and the General Terms and Conditions, governs the relationship
    between Bifonex and the client, member, investor, affiliate, or independent marketing affiliate identified above. By signing
    this contract, the client confirms that they have read, understood, and accepted the terms and obligations of participation in
    the Bifonex ecosystem.
</p>
<p>
    Bifonex may revise its terms, platform rules, product descriptions, package rules, and operating policies from time to time.
    Continued participation in the platform means the client agrees to comply with the latest applicable policies and procedures.
</p>

<h2>Confidentiality and Platform Use</h2>
<ol>
    <li>The client agrees to protect Bifonex confidential information, business methods, platform documentation, commercial data, technology processes, and any proprietary information made available through the platform.</li>
    <li>The client agrees not to misuse, copy, publish, distribute, resell, reverse engineer, or disclose restricted Bifonex information without written authorization.</li>
    <li>The client confirms that access to dashboards, packages, token/account features, commissions, and other services is subject to Bifonex rules, verification, compliance review, and applicable law.</li>
    <li>The client understands that package activation, deposit approval, automatic blockchain payment, manual payment, or activation-code use may require this signed contract before full dashboard access is granted.</li>
</ol>

<h2>Client Confirmation</h2>
<p>
    By placing the signature below, the client confirms acceptance of this Bifonex Contract and agrees that the digital signature
    has the same force and effect as a handwritten signature for the purpose of using the Bifonex platform.
</p>

<div class="signature-section">
    <strong>Client Signature</strong>
    <div class="signature-box">
        <?php if($signatureDataUri): ?>
            <img src="<?php echo e($signatureDataUri); ?>" alt="Client signature">
        <?php else: ?>
            <span class="small">Signature image not found.</span>
        <?php endif; ?>
    </div>
    <div class="signature-line">
        <?php echo e($user->name ?? $contract->name); ?><br>
        <span class="small">Signed on <?php echo e($signedAt); ?></span>
    </div>
</div>

<div class="footer">Bifonex Contract — Generated by the system. Contract ID #<?php echo e($contract->id); ?></div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/contracts/pdf/bifonex-contract.blade.php ENDPATH**/ ?>
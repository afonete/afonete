<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bifonex Contract - Full Agreement</title>
    <style>
        @page { margin: 36px 42px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #0f172a; font-size: 11px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 16px; }
        .brand { font-size: 22px; font-weight: 800; letter-spacing: 1px; color: #0f172a; }
        .subtitle { font-size: 13px; color: #475569; margin-top: 3px; font-weight: 700; }
        .meta { width: 100%; border-collapse: collapse; margin: 12px 0 16px; }
        .meta td { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: top; }
        .meta .label { width: 28%; background: #f1f5f9; font-weight: 700; color: #334155; }
        .sec-title { font-size: 13px; font-weight: 800; text-transform: uppercase; color: #0f172a; margin: 16px 0 6px; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; }
        p { margin: 0 0 8px; text-align: justify; }
        ol { margin: 4px 0 10px 18px; padding: 0; }
        li { margin-bottom: 6px; text-align: justify; }
        .font-bold { font-weight: 700; color: #0f172a; }
        .signature-section { margin-top: 28px; page-break-inside: avoid; background: #f8fafc; border: 1px solid #cbd5e1; padding: 14px; border-radius: 8px; }
        .signature-box { width: 300px; min-height: 80px; border: 1px dashed #94a3b8; padding: 6px; margin-top: 6px; background: #ffffff; text-align: center; }
        .signature-box img { max-width: 280px; max-height: 70px; }
        .signature-line { width: 300px; border-top: 1px solid #0f172a; margin-top: 8px; padding-top: 4px; font-weight: 700; }
        .small { font-size: 10px; color: #64748b; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
@php
    $signedAt = $contract->created_at ? $contract->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');
    $clientName = $user->name ?? $contract->name;
    $countryName = $user->country ?? 'N/A';
    $usernameStr = $user->user ?? 'N/A';
    $userIdStr = $user->id ?? $contract->user_id;
@endphp

<div class="header">
    <div class="brand">BIFONEX ECOSYSTEM PLATFORM</div>
    <div class="subtitle">INDEPENDENT MARKETING AFFILIATE (IMA) &amp; CLIENT AGREEMENT</div>
</div>

<table class="meta">
    <tr>
        <td class="label">Client / IMA Name</td>
        <td><strong>{{ $clientName }}</strong></td>
    </tr>
    <tr>
        <td class="label">Username / ID</td>
        <td><strong>@ {{ $usernameStr }}</strong> (User ID: #{{ $userIdStr }})</td>
    </tr>
    <tr>
        <td class="label">Email Address</td>
        <td>{{ $user->email ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label">Nationality / Country</td>
        <td>{{ $countryName }}</td>
    </tr>
    <tr>
        <td class="label">Contract Status</td>
        <td><strong>SIGNED &amp; VERIFIED</strong></td>
    </tr>
    <tr>
        <td class="label">Date &amp; Time Signed</td>
        <td><strong>{{ $signedAt }}</strong></td>
    </tr>
</table>

<div class="sec-title">1. AGREEMENT PREAMBLE &amp; RECITALS</div>
<p>
    The Independent Marketing Affiliate ("IMA") Agreement, the Compensation Plan, and the General Terms and Conditions
    (forming an inseparable part of one document and the entire agreement between the Company and its IMA) explain and govern
    the relationship between each IMA/Client and COMPANY (referred to hereafter as "Bifonex"). Each IMA is required to read,
    understand, and comply with all terms and conditions of this Agreement.
</p>
<p>
    The Agreement is subject to revision by the Company from time to time at its sole discretion. The Agreement governs all aspects
    of the relationships between the Company and its IMAs and is available on the registered website of the Company.
    These Terms of Service constitute the entire agreement between you and COMPANY and govern your use of the Services,
    superseding any prior agreements.
</p>

<div class="sec-title">2. NON-DISCLOSURE &amp; CONFIDENTIALITY AGREEMENT</div>
<p>
    THIS NON-DISCLOSURE AND CONFIDENTIALITY AGREEMENT is effective as of the date of show or acknowledgement of accepting
    the Agreement as provided through a registered website of the COMPANY, by and between:
    <strong>Bifonex</strong> (hereinafter referred to as the "COMPANY" affiliate system ECOSYSTEM PLATFORM, "OUR", "US", "WE", or Disclosing Party)
    <strong>AND</strong> <strong>{{ $clientName }}</strong> (Nationality: {{ $countryName }}, User ID: #{{ $userIdStr }},
    hereinafter referred to as "IMA / YOU / Investor / Shareholder / User / Founder / Customer / Member / Client / Affiliate").
</p>
<ol>
    <li>
        COMPANY has offered various information, documents, and explications to the User/Founder, and the User/Founder has accepted
        the terms and conditions of the COMPANY with effect from and during the period of this Agreement. The User/Founder will have
        access to Confidential and Proprietary Information of the COMPANY and its customers, partners, and investors. The use of
        Confidential Information by the User/Founder shall be treated in strict confidence.
    </li>
    <li>
        COMPANY Confidential Information shall mean any and all information available to the Company that is not available in a published
        form at the Effective Date, including, but not limited to, research ideas, projects, research results, trade secrets, business models,
        business forecasts, marketing strategies, financial data, customer lists, investors, contractual relationships, and documentation.
    </li>
    <li>
        It is important for the COMPANY to protect its Intellectual Property, Confidential, and Proprietary Information to the fullest extent,
        and the User/Founder agrees to comply with all provisions herein.
    </li>
</ol>

<div class="sec-title">3. BECOMING AN IMA &amp; OBLIGATIONS</div>
<ol>
    <li>
        <strong>Legal Age:</strong> Any individual who is of legal age (18 years) and residing in a country where the Company is doing
        business is eligible to become an IMA.
    </li>
    <li>
        <strong>ID Number:</strong> An Identification Number is inserted on the application. This number shall be either the Individual's
        Social Security Number, passport number, or government-issued ID.
    </li>
    <li>
        <strong>Independent Contractors:</strong> IMAs are independent contractors. They are not franchisees, joint venturers, partners,
        employees, or agents of the Company. IMAs have no authority to bind the Company to any obligation. IMAs set their own hours and
        determine how to conduct their business.
    </li>
</ol>

<div class="sec-title">4. COMPLIANCE, CONDUCT &amp; TERMINATION</div>
<p>
    IMAs shall at all times conduct their business with the highest level of integrity and professional ethics.
    Any form of violence, cross-recruiting, misrepresentation, or prohibited activity affecting the business will result in the immediate
    termination of this agreement without prior notice in writing.
</p>

<div class="sec-title">5. DISCLAIMER OF WARRANTIES &amp; LIMITATION OF LIABILITY</div>
<p>
    Your use of the services is at your sole risk. The services are provided on an "as is" and "as available" basis. COMPANY and its
    affiliates expressly disclaim all warranties, conditions, and representations of any kind, whether express, implied, or statutory,
    including, but not limited to, the implied warranties of merchantability, fitness for a particular purpose, title, and non-infringement.
</p>

<div class="sec-title">6. MISCELLANEOUS &amp; EXECUTION IN COUNTERPARTS</div>
<ol>
    <li>The IMA/Customer shall not be entitled to assign its rights and/or obligations under this Agreement without prior written consent.</li>
    <li>No waiver of breach or failure of condition shall be effective unless in writing and signed by the waiving party.</li>
    <li>If any provision herein is held to be void or unenforceable, the validity and enforceability of the remaining provisions shall remain unaffected.</li>
    <li>This Agreement constitutes the entire Agreement between the parties relating to the subject matter hereof.</li>
    <li>This Agreement may be executed in one or more signed digital or physical counterparts, which together form one binding instrument.</li>
</ol>

<p>
    The Founder/Customer herewith confirms reading all terms of this agreement and understanding it completely, having received
    independent legal advice, and by affixing the signature below represents that this agreement is executed with free will and consent
    without any force, duress, or coercion.
</p>

<div class="signature-section">
    <strong>IN WITNESS WHEREOF, the client has executed this agreement by digital signature:</strong>
    <div class="signature-box">
        @if($signatureDataUri)
            <img src="{{ $signatureDataUri }}" alt="Client Digital Signature">
        @else
            <span class="small">Digital signature recorded on system.</span>
        @endif
    </div>
    <div class="signature-line">
        Executed By: {{ $clientName }}<br>
        <span class="small">Digitally Signed on {{ $signedAt }} (System Ref #{{ $contract->id }})</span>
    </div>
</div>

<div class="footer">Bifonex Client Contract — Official Full System Copy. Contract ID #{{ $contract->id }}</div>
</body>
</html>

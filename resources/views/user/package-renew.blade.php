<div class="wrapper">
    @include('user.user-dashboard-base')

    <div class="content-wrapper">
        <div class="container-fluid py-4">

            @if(isset($isSingleView) && $isSingleView && isset($activePayment))
                {{-- ───────────────────────────────────────────────────────────────── --}}
                {{--  1. DETAILED SINGLE-PACKAGE RENEWAL VIEW                         --}}
                {{-- ───────────────────────────────────────────────────────────────── --}}
                
                {{-- Page title --}}
                <div class="content-header mb-3">
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                        <i class="fas fa-sync-alt mr-2 text-info"></i> Renew Package: {{ $activePayment->package ?? '' }}
                    </h1>
                    <small class="text-muted">
                        Renewal {{ $renewalNumber ?? 0 }} of {{ $maxRenewals ?? 0 }} &mdash;
                        Package runs {{ $pkgDuration ?? 0 }} days, renewed every 30 days
                        @if(isset($isPartialFee) && $isPartialFee)
                            &mdash; <strong>Final renewal covers {{ $leftoverDays ?? 0 }} leftover days</strong>
                        @endif
                    </small>
                </div>

                {{-- How the renewal math works --}}
                <div class="alert alert-info py-2 mb-3" style="font-size:13px; border-radius: 8px;">
                    <i class="fas fa-calculator mr-1"></i>
                    <strong>How renewal works:</strong>
                    Your daily Trading Voucher (75% of daily ROI) is added every day.
                    After every 30 days you have enough to renew this package.
                    The renewal fee is pro-rated for the final partial window
                    (only {{ $leftoverDays ?? 0 }} day{{ ($leftoverDays ?? 0) > 1 ? 's' : '' }} left if applicable).
                </div>

                {{-- Session messages --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show" style="border-radius: 8px;">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- Not due yet notice --}}
                @if(isset($isDue) && !$isDue)
                    <div class="alert alert-warning" style="border-radius: 8px;">
                        <i class="fas fa-clock mr-1"></i>
                        Your next renewal for this package is not due yet. It becomes available on
                        <strong>{{ isset($renewalDueDate) ? $renewalDueDate->format('d M Y') : '' }}</strong>.
                    </div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7">

                        {{-- ─── Renewal Summary Card ─── --}}
                        <div class="card card-outline card-info shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <div class="card-header bg-info text-white">
                                <h3 class="card-title font-weight-bold mb-0">
                                    <i class="fas fa-ticket-alt mr-2"></i>Trading Voucher Renewal Details
                                </h3>
                            </div>

                            <div class="card-body">

                                {{-- Progress bar --}}
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted font-weight-bold">Package Renewal Progress</small>
                                        <small class="text-info font-weight-bold">{{ ($renewalNumber ?? 1) - 1 }} / {{ $maxRenewals ?? 1 }} completed</small>
                                    </div>
                                    <div class="progress" style="height: 12px; border-radius: 6px; background-color: #e9ecef;">
                                        @php $progress = (($maxRenewals ?? 1) > 0) ? ((($renewalNumber ?? 1) - 1) / ($maxRenewals ?? 1)) * 100 : 0; @endphp
                                        <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: {{ $progress }}%; border-radius: 6px;"></div>
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.78rem;">
                                        Day 30 &bull; Day 60 &bull; Day 90 &mdash; Package expires at Day {{ $pkgDuration ?? 0 }}
                                        @if(isset($leftoverDays) && $leftoverDays > 0 && isset($isPartialFee) && $isPartialFee)
                                            &mdash; Final renewal covers Day {{ ($pkgDuration ?? 0) - $leftoverDays + 1 }}–{{ $pkgDuration ?? 0 }} ({{ $leftoverDays }} days)
                                        @endif
                                    </small>
                                </div>

                                {{-- Full Renewal Schedule --}}
                                @if(isset($schedule) && is_array($schedule) && count($schedule) > 0)
                                <div class="mb-4">
                                    <h6 class="mb-2 text-dark font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-1 text-info"></i> Full Renewal Schedule
                                    </h6>
                                    <div class="table-responsive" style="border-radius: 8px; border: 1px solid #dee2e6;">
                                        <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Due</th>
                                                    <th class="text-right">Days covered</th>
                                                    <th class="text-right">Fee</th>
                                                    <th class="text-right">Tokens</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($schedule as $row)
                                                @php
                                                    $isPast       = $row['renewal_number'] <  ($renewalNumber ?? 1);
                                                    $isCurrent    = $row['renewal_number'] == ($renewalNumber ?? 1);
                                                    $rowBg        = $isCurrent ? 'table-warning' : ($isPast ? 'table-success' : '');
                                                    $statusBadge  = $isPast
                                                        ? '<span class="badge badge-success"><i class="fa fa-check mr-1"></i> Done</span>'
                                                        : ($isCurrent
                                                            ? '<span class="badge badge-warning"><i class="fa fa-arrow-right mr-1"></i> Now</span>'
                                                            : '<span class="badge badge-secondary">Upcoming</span>');
                                                @endphp
                                                <tr class="{{ $rowBg }}">
                                                    <td><strong>{{ $row['renewal_number'] }}</strong></td>
                                                    <td>
                                                        <small class="font-weight-bold">Day {{ $row['due_at_day'] }}</small><br>
                                                        <small class="text-muted">{{ $row['due_at_date'] }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $row['days_covered'] }}
                                                        @if($row['is_partial'])
                                                            <br><small class="text-warning font-weight-bold">(partial)</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-right font-weight-bold">
                                                        ${{ number_format($row['fee'], 2) }}
                                                    </td>
                                                    <td class="text-right text-success font-weight-bold">
                                                        {{ number_format($row['tokens'], 0) }}
                                                    </td>
                                                    <td class="text-center">{!! $statusBadge !!}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <hr>

                                {{-- Summary rows --}}
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-wallet mr-1 text-info"></i> Your Trading Voucher Balance
                                                <small class="d-block text-muted">(Central wallet balance for all renewals)</small>
                                            </td>
                                            <td class="text-right font-weight-bold" style="font-size: 1.15rem; vertical-align: middle;">
                                                <span class="{{ ($tradingVoucherBalance ?? 0) >= ($renewalFee ?? 0) ? 'text-success' : 'text-danger' }}">
                                                    ${{ number_format($tradingVoucherBalance ?? 0, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-minus-circle mr-1 text-danger"></i> Renewal Fee
                                                <small class="d-block text-muted">
                                                    @if(isset($isPartialFee) && $isPartialFee)
                                                        Pro-rated for {{ $daysCovered ?? 0 }} days &divide; 30
                                                        (monthly: ${{ number_format($monthlyFee ?? 0, 2) }})
                                                    @else
                                                        Standard monthly fee (30 days)
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="text-right font-weight-bold text-danger" style="font-size: 1.1rem; vertical-align: middle;">
                                                &minus; ${{ number_format($renewalFee ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-coins mr-1 text-warning"></i> New Token Price
                                                <small class="d-block text-muted">(set by admin)</small>
                                            </td>
                                            <td class="text-right font-weight-bold" style="vertical-align: middle;">
                                                ${{ number_format($tokenPrice ?? 0, 4) }} / token
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-plus-circle mr-1 text-success"></i> Tokens You Will Receive
                                                <small class="d-block text-muted">= Fee &divide; Token Price</small>
                                            </td>
                                            <td class="text-right" style="vertical-align: middle;">
                                                <span class="badge badge-success px-3 py-2" style="font-size: 0.95rem;">
                                                    {{ number_format($tokensToReceive ?? 0, 4) }} tokens
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-calendar-alt mr-1"></i> Renewal Cycle
                                            </td>
                                            <td class="text-right font-weight-bold" style="vertical-align: middle;">
                                                #{{ $renewalNumber ?? 0 }} of {{ $maxRenewals ?? 0 }}
                                            </td>
                                        </tr>
                                        @if(($renewalNumber ?? 0) < ($maxRenewals ?? 0))
                                        <tr>
                                            <td class="text-muted pl-0">
                                                <i class="fas fa-forward mr-1"></i> Next Renewal Due
                                            </td>
                                            <td class="text-right text-muted font-weight-bold" style="vertical-align: middle;">
                                                ~{{ \Carbon\Carbon::now()->addDays(30)->format('d M Y') }}
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-2 bg-light rounded mt-2">
                                                <i class="fas fa-flag-checkered mr-1 text-info"></i>
                                                @if(isset($isPartialFee) && $isPartialFee)
                                                    This is your <strong>final renewal</strong> for this package cycle
                                                    &mdash; covers {{ $leftoverDays ?? 0 }} leftover days.
                                                @else
                                                    This is your <strong>final renewal</strong> for this package cycle.
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>

                            {{-- Balance warning or Pay Button --}}
                            @if(($tradingVoucherBalance ?? 0) < ($renewalFee ?? 0))
                                <div class="card-footer bg-danger text-white text-center py-3">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Insufficient Trading Voucher balance. You need
                                    <strong>${{ number_format($renewalFee ?? 0, 2) }}</strong>
                                    but only have
                                    <strong>${{ number_format($tradingVoucherBalance ?? 0, 2) }}</strong>.
                                </div>
                            @else
                                <div class="card-footer p-0">
                                    @if(isset($isDue) && $isDue)
                                        {{-- Confirm modal trigger --}}
                                        <button type="button"
                                                class="btn btn-info btn-block py-3 font-weight-bold"
                                                style="font-size: 1.1rem; border-radius: 0;"
                                                data-toggle="modal"
                                                data-target="#confirmRenewModal">
                                            <i class="fas fa-sync-alt mr-2"></i>
                                            Buy &mdash; Renew Package Now
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-block py-3 font-weight-bold" disabled
                                                style="font-size: 1rem; border-radius: 0; cursor: not-allowed;">
                                            <i class="fas fa-clock mr-2"></i>
                                            Available on {{ isset($renewalDueDate) ? $renewalDueDate->format('d M Y') : '' }}
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Back link --}}
                        <div class="text-center mt-3">
                            <a href="{{ route('packageRenew') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Renewals Directory
                            </a>
                        </div>

                    </div>
                </div>

            @else
                {{-- ───────────────────────────────────────────────────────────────── --}}
                {{--  2. NEW DASHBOARD VIEW FOR ALL PACKAGES                          --}}
                {{-- ───────────────────────────────────────────────────────────────── --}}

                <div class="content-header mb-4">
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.8rem;">
                        <i class="fas fa-sync-alt mr-2 text-info"></i> My Active Packages &amp; Renewals
                    </h1>
                    <p class="text-muted mb-0">
                        Track all your active investments, check 30-day renewal countdowns, and pay your renewal fees to keep earning ROI.
                    </p>
                </div>

                {{-- Summary Cards Row --}}
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card border-0 shadow-sm text-white" style="border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <div class="card-body py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.05em; opacity: 0.9;">
                                            My Trading Voucher Balance
                                        </span>
                                        <h2 class="font-weight-bold mb-0 mt-1" style="font-size: 2.2rem;">
                                            ${{ number_format($tradingVoucherBalance ?? 0, 2) }}
                                        </h2>
                                        <small style="opacity: 0.85;">Central balance used to cover all package renewal fees.</small>
                                    </div>
                                    <i class="fas fa-wallet fa-3x" style="opacity: 0.25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm text-white" style="border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                            <div class="card-body py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-block text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.05em; opacity: 0.9;">
                                            Total Active Packages
                                        </span>
                                        <h2 class="font-weight-bold mb-0 mt-1" style="font-size: 2.2rem;">
                                            {{ isset($packagesPaginator) ? $packagesPaginator->total() : (isset($packagesData) && is_array($packagesData) ? count($packagesData) : 0) }}
                                        </h2>
                                        <small style="opacity: 0.85;">Packages currently generating daily yields and active interest.</small>
                                    </div>
                                    <i class="fas fa-box-open fa-3x" style="opacity: 0.25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main List of Packages --}}
                <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="card-title font-weight-bold mb-0 text-dark">
                            <i class="fas fa-list-ul mr-2 text-primary"></i> Package Renewal Status &amp; Controls
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if(!isset($packagesData) || empty($packagesData))
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <h5 class="font-weight-bold text-secondary">No Active Packages Found</h5>
                                <p class="text-muted px-4 mb-4">
                                    You do not currently have any active packages that require renewal.
                                </p>
                                <a href="{{ route('user.venture') }}" class="btn btn-primary px-4 py-2 font-weight-bold" style="border-radius: 8px;">
                                    <i class="fas fa-plus mr-1"></i> Buy a Package Now
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead class="bg-light text-uppercase text-secondary" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                        <tr>
                                            <th class="pl-4">Package details</th>
                                            <th>Date Purchased</th>
                                            <th class="text-center">Renewal Progress</th>
                                            <th>Next Renewal Status</th>
                                            <th class="text-right pr-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($packagesData as $data)
                                            @php
                                                $payment = $data['payment'] ?? null;
                                                $renewalsDone = $data['renewalsDone'] ?? 0;
                                                $maxRenewals = $data['maxRenewals'] ?? 0;
                                                $allRenewalsDone = $data['allRenewalsDone'] ?? false;
                                                $isDue = $data['isDue'] ?? false;
                                                $renewalDueDate = $data['renewalDueDate'] ?? null;
                                            @endphp
                                            @if($payment)
                                                <tr style="height: 90px; vertical-align: middle;">
                                                    {{-- Package Details --}}
                                                    <td class="pl-4" style="vertical-align: middle;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3 bg-light rounded text-center p-2 d-none d-sm-block" style="width: 45px; height: 45px;">
                                                                <i class="fas fa-box text-primary fa-lg" style="margin-top: 5px;"></i>
                                                            </div>
                                                            <div>
                                                                <span class="d-block font-weight-bold text-dark" style="font-size: 1.05rem;">
                                                                    {{ $payment->package }}
                                                                </span>
                                                                <small class="text-muted">
                                                                    Invested Capital: <strong>${{ number_format($payment->amount, 2) }}</strong>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- Date Purchased --}}
                                                    <td style="vertical-align: middle;">
                                                        <span class="d-block text-dark font-weight-bold" style="font-size: 0.9rem;">
                                                            {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y') }}
                                                        </span>
                                                        <small class="text-muted">
                                                            Expires: {{ \Carbon\Carbon::parse($payment->expiration_date)->format('d M Y') }}
                                                        </small>
                                                    </td>

                                                    {{-- Progress --}}
                                                    <td class="text-center" style="width: 220px; vertical-align: middle;">
                                                        <div class="px-3">
                                                            <div class="d-flex justify-content-between mb-1" style="font-size: 0.78rem;">
                                                                <span class="text-muted font-weight-bold">Completed:</span>
                                                                <span class="text-primary font-weight-bold">{{ $renewalsDone }} / {{ $maxRenewals }}</span>
                                                            </div>
                                                            <div class="progress" style="height: 8px; border-radius: 4px; background-color: #e9ecef;">
                                                                @php $pct = $maxRenewals > 0 ? ($renewalsDone / $maxRenewals) * 100 : 0; @endphp
                                                                <div class="progress-bar bg-primary" style="width: {{ $pct }}%; border-radius: 4px;"></div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    {{-- Status --}}
                                                    <td style="vertical-align: middle;">
                                                        @if($allRenewalsDone)
                                                            <span class="badge badge-success px-3 py-2 font-weight-bold" style="border-radius: 6px; font-size: 0.85rem;">
                                                                <i class="fas fa-check-circle mr-1"></i> All Renewals Complete!
                                                            </span>
                                                        @elseif($isDue)
                                                            {{-- Pulsing yellow/orange reminder alert --}}
                                                            <div class="d-inline-block p-2 bg-warning text-dark font-weight-bold text-center rounded border border-warning shadow-sm" 
                                                                 style="font-size: 0.82rem; border-radius: 6px; line-height: 1.3; animation: pulseGlow 2s infinite;">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> RENEWAL DUE NOW
                                                            </div>
                                                        @else
                                                            <span class="d-block text-dark font-weight-bold" style="font-size: 0.88rem;">
                                                                Upcoming Cycle #{{ $data['renewalNumber'] ?? 1 }}
                                                            </span>
                                                            <small class="text-muted d-block">
                                                                Due: {{ $renewalDueDate ? $renewalDueDate->format('d M Y') : '—' }}
                                                            </small>
                                                        @endif
                                                    </td>

                                                    {{-- Action Button --}}
                                                    <td class="text-right pr-4" style="vertical-align: middle;">
                                                        @if($allRenewalsDone)
                                                            <button class="btn btn-sm btn-light font-weight-bold text-muted px-3 py-2" disabled style="border-radius: 8px; cursor: not-allowed; border: 1px solid #dee2e6;">
                                                                <i class="fas fa-flag-checkered mr-1 text-success"></i> Finished
                                                            </button>
                                                        @elseif($isDue)
                                                            {{-- HIGH ATTENTION Reminding Button --}}
                                                            <a href="{{ route('packageRenew', $payment->id) }}" 
                                                               class="btn btn-warning font-weight-bold text-dark px-3 py-2 shadow border-0" 
                                                               style="border-radius: 8px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); transition: transform 0.2s;">
                                                                <i class="fas fa-sync-alt mr-1"></i> Renew Now (${{ number_format($data['renewalFee'] ?? 0, 2) }})
                                                            </a>
                                                        @else
                                                            <a href="{{ route('packageRenew', $payment->id) }}" class="btn btn-outline-secondary font-weight-bold px-3 py-2" style="border-radius: 8px; font-size: 0.85rem;">
                                                                <i class="fas fa-eye mr-1"></i> Preview Details
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($packagesPaginator) && $packagesPaginator->hasPages())
                                <div class="d-flex justify-content-center py-3 border-top">
                                    {{ $packagesPaginator->links('pagination::bootstrap-4') }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Pulse animation for Due Alerts --}}
                <style>
                    @keyframes pulseGlow {
                        0% {
                            transform: scale(1);
                            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
                        }
                        70% {
                            transform: scale(1.03);
                            box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
                        }
                        100% {
                            transform: scale(1);
                            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
                        }
                    }
                </style>
            @endif

        </div>
    </div>
</div>

{{-- ───────────────────────────────────────────────────────────────── --}}
{{--  3. CONFIRMATION MODAL (Placing at absolute bottom of page)     --}}
{{-- ───────────────────────────────────────────────────────────────── --}}
@if(isset($isSingleView) && $isSingleView && isset($activePayment))
    <div class="modal fade" id="confirmRenewModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999; display: none;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div class="modal-header bg-info text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold mb-0">
                        <i class="fas fa-sync-alt mr-2"></i> Confirm Package Renewal
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <p class="text-dark mb-4">You are about to renew your package using your <strong>Trading Voucher</strong> balance.</p>
                    <div class="p-3 bg-light rounded-lg mb-4" style="border-radius: 8px;">
                        <table class="table table-sm table-borderless mb-0" style="color: #4b5563;">
                            <tr style="height: 30px; vertical-align: middle;">
                                <td class="text-muted pl-0">Amount to deduct:</td>
                                <td class="font-weight-bold text-danger text-right pr-0" style="font-size: 1.15rem;">${{ number_format($renewalFee ?? 0, 2) }}</td>
                            </tr>
                            <tr style="height: 30px; vertical-align: middle;">
                                <td class="text-muted pl-0">Token price:</td>
                                <td class="font-weight-bold text-dark text-right pr-0">${{ number_format($tokenPrice ?? 0, 4) }}</td>
                            </tr>
                            <tr style="height: 30px; vertical-align: middle;">
                                <td class="text-muted pl-0">Tokens you receive:</td>
                                <td class="font-weight-bold text-success text-right pr-0" style="font-size: 1.15rem;">{{ number_format($tokensToReceive ?? 0, 4) }}</td>
                            </tr>
                            <tr style="height: 30px; vertical-align: middle;">
                                <td class="text-muted pl-0">Renewal cycle:</td>
                                <td class="font-weight-bold text-dark text-right pr-0">#{{ $renewalNumber ?? 0 }} of {{ $maxRenewals ?? 0 }}</td>
                            </tr>
                        </table>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.82rem; line-height: 1.4;">
                        <i class="fas fa-info-circle mr-1 text-info"></i>
                        Your package renewal will unlock the income for the next 30-day window.
                        This action <strong>cannot be undone</strong>.
                    </p>
                </div>
                <div class="modal-footer bg-light border-0 py-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary font-weight-bold px-4 py-2" data-dismiss="modal" style="border-radius: 8px; font-size: 0.88rem;">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <form action="{{ route('packageRenewPay') }}" method="POST" class="d-inline mb-0">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{ $activePayment->id ?? 0 }}">
                        <button type="submit" class="btn btn-info font-weight-bold px-4 py-2" style="border-radius: 8px; font-size: 0.88rem; background-color: #0dcaf0; border: none;">
                            <i class="fas fa-check mr-1"></i> Confirm &amp; Pay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@include('user.user-dashboard-base')

<div class="content-wrapper">
    <div class="container-fluid py-4">

        {{-- Page title --}}
        <div class="content-header mb-3">
            <h1 class="m-0 text-dark">
                <i class="fas fa-sync-alt mr-2 text-info"></i> Renew Package
            </h1>
            <small class="text-muted">
                Renewal {{ $renewalNumber }} of {{ $maxRenewals }} &mdash;
                Package runs {{ $pkgDuration }} days, renewed every 30 days
                @if($isPartialFee)
                    &mdash; <strong>Final renewal covers {{ $leftoverDays }} leftover days</strong>
                @endif
            </small>
        </div>

        {{-- How the renewal math works — Issue 2 spec --}}
        <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
            <i class="fas fa-calculator mr-1"></i>
            <strong>How renewal works:</strong>
            Your daily Trading Voucher (75% of daily ROI) is added every day.
            After every 30 days you have enough to renew this package.
            The renewal fee is pro-rated for the final partial window
            (only {{ $leftoverDays }} day{{ $leftoverDays > 1 ? 's' : '' }} left if applicable).
        </div>

        {{-- Session messages --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        {{-- Not due yet notice --}}
        @if(!$isDue)
            <div class="alert alert-warning">
                <i class="fas fa-clock mr-1"></i>
                Your next renewal is not due yet. It becomes available on
                <strong>{{ $renewalDueDate->format('d M Y') }}</strong>.
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">

                {{-- ─── Renewal Summary Card ─── --}}
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">
                            <i class="fas fa-ticket-alt mr-2"></i>Trading Voucher Renewal Details
                        </h3>
                    </div>

                    <div class="card-body">

                        {{-- Progress bar showing which renewal this is --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Package Renewal Progress</small>
                                <small class="text-muted">{{ $renewalNumber - 1 }} / {{ $maxRenewals }} completed</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                @php $progress = (($renewalNumber - 1) / $maxRenewals) * 100; @endphp
                                <div class="progress-bar bg-info" style="width: {{ $progress }}%"></div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Day 30 &bull; Day 60 &bull; Day 90 &mdash; Package expires at Day {{ $pkgDuration }}
                                @if($leftoverDays > 0 && $isPartialFee)
                                    &mdash; Final renewal covers Day {{ $pkgDuration - $leftoverDays + 1 }}–{{ $pkgDuration }} ({{ $leftoverDays }} days)
                                @endif
                            </small>
                        </div>

                        {{-- ─── Full Renewal Schedule (Issue 2 / 6) ─── --}}
                        @if(isset($schedule) && count($schedule) > 0)
                        <div class="mb-3">
                            <h6 class="mb-2 text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i> Full Renewal Schedule
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size:13px;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Due</th>
                                            <th class="text-right">Days covered</th>
                                            <th class="text-right">Fee</th>
                                            <th class="text-right">Tokens</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedule as $row)
                                        @php
                                            $isPast       = $row['renewal_number'] <  $renewalNumber;
                                            $isCurrent    = $row['renewal_number'] == $renewalNumber;
                                            $rowBg        = $isCurrent ? 'table-warning' : ($isPast ? 'table-success' : '');
                                            $statusBadge  = $isPast
                                                ? '<span class="badge badge-success"><i class="fa fa-check"></i> Done</span>'
                                                : ($isCurrent
                                                    ? '<span class="badge badge-warning"><i class="fa fa-arrow-right"></i> Now</span>'
                                                    : '<span class="badge badge-secondary">Upcoming</span>');
                                        @endphp
                                        <tr class="{{ $rowBg }}">
                                            <td><strong>{{ $row['renewal_number'] }}</strong></td>
                                            <td>
                                                <small>Day {{ $row['due_at_day'] }}</small><br>
                                                <small class="text-muted">{{ $row['due_at_date'] }}</small>
                                            </td>
                                            <td class="text-right">
                                                {{ $row['days_covered'] }}
                                                @if($row['is_partial'])
                                                    <br><small class="text-warning">(partial)</small>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                ${{ number_format($row['fee'], 2) }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($row['tokens'], 0) }}
                                            </td>
                                            <td>{!! $statusBadge !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i>
                                Each renewal unlocks 30 days of income (or the leftover days for the final one).
                                If you miss a renewal, those days' cashout <strong>and</strong> trading voucher are skipped.
                            </small>
                        </div>
                        @endif

                        <hr>

                        {{-- Summary rows --}}
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-wallet mr-1"></i> Your Trading Voucher Balance
                                    </td>
                                    <td class="text-right font-weight-bold
                                        {{ $tradingVoucherBalance >= $renewalFee ? 'text-success' : 'text-danger' }}">
                                        ${{ number_format($tradingVoucherBalance, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-minus-circle mr-1 text-danger"></i> Renewal Fee
                                        <small class="d-block text-muted">
                                            @if($isPartialFee)
                                                Pro-rated for {{ $daysCovered }} days &divide; 30
                                                (monthly: ${{ number_format($monthlyFee, 2) }})
                                            @else
                                                Standard monthly fee (30 days)
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        &minus; ${{ number_format($renewalFee, 2) }}
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-coins mr-1 text-warning"></i> New Token Price
                                        <small class="d-block text-muted">(set by admin)</small>
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        ${{ number_format($tokenPrice, 4) }} / token
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-plus-circle mr-1 text-success"></i> Tokens You Will Receive
                                        <small class="d-block text-muted">= Fee &divide; Token Price</small>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-success px-3 py-2" style="font-size: 1rem;">
                                            {{ number_format($tokensToReceive, 4) }} tokens
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-calendar-alt mr-1"></i> Renewal Cycle
                                    </td>
                                    <td class="text-right font-weight-bold">
                                        #{{ $renewalNumber }} of {{ $maxRenewals }}
                                    </td>
                                </tr>
                                @if($renewalNumber < $maxRenewals)
                                <tr>
                                    <td class="text-muted pl-0">
                                        <i class="fas fa-forward mr-1"></i> Next Renewal Due
                                    </td>
                                    <td class="text-right text-muted">
                                        ~{{ \Carbon\Carbon::now()->addDays(30)->format('d M Y') }}
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="2" class="text-center text-muted">
                                        <i class="fas fa-flag-checkered mr-1"></i>
                                        @if($isPartialFee)
                                            This is your <strong>final renewal</strong> for this package cycle
                                            &mdash; covers {{ $leftoverDays }} leftover days.
                                        @else
                                            This is your <strong>final renewal</strong> for this package cycle.
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>

                    {{-- Balance warning --}}
                    @if($tradingVoucherBalance < $renewalFee)
                        <div class="card-footer bg-danger text-white text-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Insufficient Trading Voucher balance. You need
                            <strong>${{ number_format($renewalFee, 2) }}</strong>
                            but only have
                            <strong>${{ number_format($tradingVoucherBalance, 2) }}</strong>.
                        </div>
                    @else
                        <div class="card-footer p-0">
                            @if($isDue)
                                {{-- Confirm modal trigger --}}
                                <button type="button"
                                        class="btn btn-info btn-block py-3"
                                        style="font-size: 1.1rem; border-radius: 0;"
                                        data-toggle="modal"
                                        data-target="#confirmRenewModal">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Buy &mdash; Renew Package Now
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary btn-block py-3" disabled
                                        style="font-size: 1rem; border-radius: 0;">
                                    <i class="fas fa-clock mr-2"></i>
                                    Available on {{ $renewalDueDate->format('d M Y') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Back link --}}
                <div class="text-center mt-2">
                    <a href="{{ route('user.dashboard') }}" class="text-muted">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ─── Confirmation Modal ─── --}}
<div class="modal fade" id="confirmRenewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sync-alt mr-2"></i> Confirm Package Renewal
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to renew your package using your <strong>Trading Voucher</strong> balance.</p>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Amount to deduct:</td>
                        <td class="font-weight-bold text-danger">${{ number_format($renewalFee, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Token price:</td>
                        <td class="font-weight-bold">${{ number_format($tokenPrice, 4) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tokens you receive:</td>
                        <td class="font-weight-bold text-success">{{ number_format($tokensToReceive, 4) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Renewal cycle:</td>
                        <td class="font-weight-bold">#{{ $renewalNumber }} of {{ $maxRenewals }}</td>
                    </tr>
                </table>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle mr-1"></i>
                    Your package expiration will be extended by <strong>30 days</strong>.
                    This action <strong>cannot be undone</strong>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
                <form action="{{ route('packageRenewPay') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-check mr-1"></i> Confirm &amp; Pay
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/a/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/a/dist/js/adminlte.min.js') }}"></script>

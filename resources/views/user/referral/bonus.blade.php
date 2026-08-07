@include('user.user-dashboard-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    @include('user.referral._nav', ['active' => 'bonus'])

    @if(session('success'))<div class="alert alert-success mt-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mt-2">{{ session('error') }}</div>@endif

    {{-- ── Totals cards ── --}}
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Total Referral Bonus</small>
                    <div class="font-weight-bold" style="font-size:1.6rem;">${{ number_format($totals['total'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Withdrawable Now</small>
                    <div class="font-weight-bold text-success" style="font-size:1.6rem;">${{ number_format($totals['withdrawable'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Pending This Week</small>
                    <div class="font-weight-bold text-warning" style="font-size:1.6rem;">${{ number_format($totals['pending'], 2) }}</div>
                    <small class="text-muted">Withdrawable next {{ $nextMonday->format('D d M') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <small class="text-muted">Already Withdrawn</small>
                    <div class="font-weight-bold text-info" style="font-size:1.6rem;">${{ number_format($totals['lifetime_withdrawn'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Withdraw action ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="font-weight-bold mb-1"><i class="fas fa-calendar-week mr-2 text-primary"></i>Weekly Referral Withdrawal</h5>
                    <small class="text-muted">
                        @if($isMonday)
                            ✅ <span class="text-success font-weight-bold">Today is Monday</span> — transfer earnings to your Cashout wallet now.
                        @else
                            Next withdrawal window opens on <strong>{{ $nextMonday->format('l, d M Y') }}</strong>.
                        @endif
                    </small>
                </div>
                <div>
                    @if($isMonday && $totals['withdrawable'] > 0 && !$alreadyReq)
                        <form method="POST" action="{{ route('user.referral.withdraw') }}" class="d-inline js-transaction-password-form">
                            @csrf
                            <input type="hidden" name="transaction_password" class="js-transaction-password-value">
                            <button type="submit" class="btn btn-success btn-lg font-weight-bold"
                                    onclick="return confirm('Transfer ${{ number_format($totals['withdrawable'], 2) }} referral bonus to your Cashout wallet for withdrawal?')">
                                <i class="fas fa-wallet mr-1"></i>
                                Transfer ${{ number_format($totals['withdrawable'], 2) }} to Cashout
                            </button>
                        </form>
                    @elseif($alreadyReq)
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-check-circle mr-1"></i> Transferred to Cashout this Monday
                        </button>
                    @elseif(!$isMonday)
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-lock mr-1"></i> Available on Monday only
                        </button>
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-ban mr-1"></i> Nothing to withdraw
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── History ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Bonus History</div>
        <div class="card-body p-0">
            @if($rows->isEmpty())
                <p class="text-muted p-3 mb-0">No referral bonuses yet. Invite people to start earning!</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Source</th>
                            <th>From</th>
                            <th>Level</th>
                            <th>Pkg Amt</th>
                            <th>Bonus</th>
                            <th>Withdrawable On</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $r)
                        @php
                            $statusClass = match($r->status) {
                                'withdrawn'   => 'success',
                                'withdrawable'=> 'info',
                                'pending'     => 'warning',
                                'reversed'    => 'danger',
                                default       => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td>{!! $r->sourceLabel() !!}</td>
                            <td>{{ $r->sourceUser->user ?? $r->sourceUser->name ?? '—' }}</td>
                            <td>
                                @if($r->level > 0)
                                    <span class="badge badge-primary">L{{ $r->level }} · {{ rtrim(rtrim(number_format($r->percentage, 2), '0'), '.') }}%</span>
                                @else
                                    <span class="badge badge-warning">—</span>
                                @endif
                            </td>
                            <td>${{ number_format($r->source_amount, 2) }}</td>
                            <td class="font-weight-bold text-success">${{ number_format($r->bonus_amount, 4) }}</td>
                            <td><small>{{ $r->week_start->format('d M Y') }}</small></td>
                            <td><span class="badge badge-{{ $statusClass }}">{{ ucfirst($r->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center border-top">
                {{ $rows->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

</div>
</div>
@include('user.components.transaction-password-modal')

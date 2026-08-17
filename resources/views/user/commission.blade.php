<div class="wrapper">
    @include('user.user-dashboard-base')
    <div class="content-wrapper">
    <div class="w-full p-4">

        {{-- Nav (shared Bootstrap finance navbar — Tailwind is not loaded on these pages) --}}
        @include('user.finance-nav', ['active' => 'commission'])

        {{-- Summary Cards --}}
        <div class="row px-2 mb-3">
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                    <h4 class="font-weight-bold">${{ number_format($totalCommission, 2) }}</h4>
                    <p class="text-muted small mb-0">Total Commission Balance</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                    <h4 class="font-weight-bold">{{ $totalDirectCount }}</h4>
                    <p class="text-muted small mb-0">Direct Referrals</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
                    <h4 class="font-weight-bold">{{ $activeDirectCount }}</h4>
                    <p class="text-muted small mb-0">Active Investors</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card border-0 shadow-sm text-center py-3">
                    <i class="fas fa-clock fa-2x text-secondary mb-2"></i>
                    <h4 class="font-weight-bold">${{ number_format($previousWeekTotal, 2) }}</h4>
                    <p class="text-muted small mb-0">Previous Week</p>
                </div>
            </div>
        </div>

        {{-- Bonus Breakdown --}}
        <div class="row px-2 mb-4">
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white font-weight-bold">
                        <i class="fas fa-user-check mr-1"></i> Direct Referral Bonus (10%)
                        <span class="float-right badge badge-light text-success">${{ number_format($directBonusTotal, 2) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($directReferrals->isEmpty())
                            <p class="text-muted p-3 mb-0">No direct referrals yet.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Username</th>
                                        <th>Invested</th>
                                        <th>Bonus (10%)</th>
                                        <th>Status</th>
                                        <th>Since</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($directReferrals as $ref)
                                    <tr>
                                        <td>
                                            <strong>{{ $ref->user ?? $ref->name }}</strong>
                                            <small class="d-block text-muted">{{ $ref->email }}</small>
                                        </td>
                                        <td>${{ number_format($ref->total_invested, 2) }}</td>
                                        <td class="font-weight-bold text-success">
                                            ${{ number_format($ref->bonus_earned, 2) }}
                                        </td>
                                        <td>
                                            @if($ref->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">No Investment</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $ref->created_at->format('d M Y') }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white font-weight-bold">
                        <i class="fas fa-users mr-1"></i> Indirect Referral Bonus (1%)
                        <span class="float-right badge badge-light text-info">${{ number_format($indirectBonusTotal, 2) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($indirectReferrals->isEmpty())
                            <p class="text-muted p-3 mb-0">No indirect referrals yet.</p>
                        @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Username</th>
                                        <th>Via</th>
                                        <th>Invested</th>
                                        <th>Bonus (1%)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($indirectReferrals as $ref)
                                    <tr>
                                        <td><strong>{{ $ref->user ?? $ref->name }}</strong></td>
                                        <td><small class="text-muted">{{ $ref->referred_through }}</small></td>
                                        <td>${{ number_format($ref->total_invested, 2) }}</td>
                                        <td class="font-weight-bold text-info">
                                            ${{ number_format($ref->bonus_earned, 2) }}
                                        </td>
                                        <td>
                                            @if($ref->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">No Investment</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="px-2 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark font-weight-bold">
                    <i class="fas fa-history mr-1"></i> Commission Transaction History
                </div>
                <div class="card-body p-0">
                    @if($commissionTrx->isEmpty())
                        <p class="text-muted p-3 mb-0">No commission transactions yet.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Reference</th>
                                    <th>From</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissionTrx as $t)
                                <tr>
                                    <td><small class="text-muted">{{ $t->transaction_no }}</small></td>
                                    <td>{{ $t->from_user }}</td>
                                    <td><small>{{ $t->parsed_description }}</small></td>
                                    <td class="font-weight-bold text-success">${{ number_format($t->parsed_amount, 2) }}</td>
                                    <td><small>{{ $t->created_at->format('d M Y H:i') }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    </div>
</div>

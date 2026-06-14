@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4 px-4">

    <div class="mb-3">
        <a href="{{ route('admin.referral-bonuses') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to All Users
        </a>
    </div>

    <header class="bg-blue-50 py-6 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            <i class="fas fa-user mr-2"></i> {{ $user->name }} — Referral Bonus Detail
        </h1>
        <p class="text-gray-500 mt-1 text-sm">{{ $user->email }} &bull; Joined {{ $user->created_at->format('d M Y') }}</p>
    </header>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                <h4 class="font-weight-bold">${{ number_format($commissionBalance, 2) }}</h4>
                <p class="text-muted small mb-0">Commission Balance</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-user-plus fa-2x text-success mb-2"></i>
                <h4 class="font-weight-bold">{{ $directReferrals->count() }}</h4>
                <p class="text-muted small mb-0">Direct Referrals</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-users fa-2x text-info mb-2"></i>
                <h4 class="font-weight-bold">{{ $indirectReferrals->count() }}</h4>
                <p class="text-muted small mb-0">Indirect Referrals</p>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-receipt fa-2x text-secondary mb-2"></i>
                <h4 class="font-weight-bold">{{ $transactions->count() }}</h4>
                <p class="text-muted small mb-0">Bonus Transactions</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Direct Referrals --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white font-weight-bold">
                    <i class="fas fa-user-check mr-1"></i> Direct Referrals (10% bonus)
                    <span class="float-right">${{ number_format($directBonusTotal, 2) }}</span>
                </div>
                <div class="card-body p-0">
                    @if($directReferrals->isEmpty())
                        <p class="text-muted p-3 mb-0">None.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Name</th><th>Email</th><th>Invested</th><th>Bonus</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($directReferrals as $r)
                                <tr>
                                    <td><strong>{{ $r->name }}</strong></td>
                                    <td><small>{{ $r->email }}</small></td>
                                    <td>${{ number_format($r->total_invested, 2) }}</td>
                                    <td class="text-success font-weight-bold">${{ number_format($r->bonus_earned, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $r->is_active ? 'success' : 'secondary' }}">
                                            {{ $r->is_active ? 'Active' : 'No Investment' }}
                                        </span>
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

        {{-- Indirect Referrals --}}
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white font-weight-bold">
                    <i class="fas fa-users mr-1"></i> Indirect Referrals (1% bonus)
                    <span class="float-right">${{ number_format($indirectBonusTotal, 2) }}</span>
                </div>
                <div class="card-body p-0">
                    @if($indirectReferrals->isEmpty())
                        <p class="text-muted p-3 mb-0">None.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>Name</th><th>Via</th><th>Invested</th><th>Bonus</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                @foreach($indirectReferrals as $r)
                                <tr>
                                    <td><strong>{{ $r->name }}</strong></td>
                                    <td><small class="text-muted">{{ $r->referred_through }}</small></td>
                                    <td>${{ number_format($r->total_invested, 2) }}</td>
                                    <td class="text-info font-weight-bold">${{ number_format($r->bonus_earned, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $r->is_active ? 'success' : 'secondary' }}">
                                            {{ $r->is_active ? 'Active' : 'No Investment' }}
                                        </span>
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
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-history mr-1"></i> Commission Transaction History
        </div>
        <div class="card-body p-0">
            @if($transactions->isEmpty())
                <p class="text-muted p-3 mb-0">No commission transactions recorded.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>Reference</th><th>From User</th><th>Description</th><th>Amount</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
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
@endsection

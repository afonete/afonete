@extends('admin.sidebar')

@section('contents')
<div class="container-fluid py-4 px-4">

    <header class="bg-blue-50 py-8 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold">
            <i class="fas fa-users mr-2"></i> Referral Bonuses — All Users
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Overview of every user's referral network and commission balance.
        </p>
    </header>

    {{-- Summary --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                <h3 class="font-weight-bold">${{ number_format($totalCommissionPaid, 2) }}</h3>
                <p class="text-muted mb-0">Total Commission Balances</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-user-friends fa-2x text-info mb-2"></i>
                <h3 class="font-weight-bold">{{ $users->count() }}</h3>
                <p class="text-muted mb-0">Users with Referrals or Bonuses</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center py-3">
                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                <h3 class="font-weight-bold">{{ $users->where('active_referrals', '>', 0)->count() }}</h3>
                <p class="text-muted mb-0">Users with Active Referrals</p>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white font-weight-bold border-bottom">
            <i class="fas fa-list mr-1"></i> Users & Their Referral Bonuses
            <small class="text-muted ml-2">Sorted by highest commission balance</small>
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <p class="text-muted p-4">No users with referral activity yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Package Status</th>
                            <th>Total Referrals</th>
                            <th>Active Referrals</th>
                            <th>Commission Balance</th>
                            <th>Joined</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $u)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $u->name }}</strong>
                                <small class="d-block text-muted">{{ $u->email }}</small>
                                <small class="text-muted">ID: {{ $u->id }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $u->has_paid_package !== 'no' ? 'success' : 'secondary' }}">
                                    {{ $u->has_paid_package !== 'no' ? 'Active' : 'No Package' }}
                                </span>
                            </td>
                            <td>{{ $u->total_referrals }}</td>
                            <td>
                                <span class="font-weight-bold text-success">{{ $u->active_referrals }}</span>
                                <small class="text-muted">/ {{ $u->total_referrals }}</small>
                            </td>
                            <td>
                                <span class="font-weight-bold {{ $u->commission_balance > 0 ? 'text-warning' : 'text-muted' }}">
                                    ${{ number_format($u->commission_balance, 2) }}
                                </span>
                            </td>
                            <td><small>{{ $u->created_at->format('d M Y') }}</small></td>
                            <td>
                                <a href="{{ route('admin.referral-bonus-detail', $u->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
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
@endsection

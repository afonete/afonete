@include('admin.admin-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-user-check text-success mr-2"></i> Users Who Meet Rank Criteria</h3>
    <p class="text-muted">
        Live criteria check. Users here qualify but have not yet had a rank application created
        (the <code>ranks:check</code> cron will pick them up at 00:10 daily).
    </p>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body p-0">
            @if(empty($rows))
                <p class="text-muted p-3 mb-0">No users currently meet any rank criteria.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>User</th><th>Email</th><th>Rank</th><th>Reward</th>
                            <th>Active Directs</th><th>Direct Inv.</th><th>Total Inv.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr>
                                <td><strong>{{ $row['user']->name }}</strong></td>
                                <td><small>{{ $row['user']->email }}</small></td>
                                <td><span class="badge badge-success">{{ $row['rank']->name }}</span></td>
                                <td>{{ $row['rank']->rewardLabel() }}</td>
                                <td>{{ $row['actual']['active_direct_referrals'] }}</td>
                                <td>${{ number_format($row['actual']['direct_referral_investment'], 2) }}</td>
                                <td>${{ number_format($row['actual']['total_investment'], 2) }}</td>
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

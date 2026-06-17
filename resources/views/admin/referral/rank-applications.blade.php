@include('admin.admin-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-trophy text-primary mr-2"></i> Rank Applications</h3>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    {{-- ── PENDING ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-warning text-dark font-weight-bold">
            <i class="fas fa-hourglass-half mr-1"></i>
            Pending Review ({{ $pending->count() }})
        </div>
        <div class="card-body p-0">
            @if($pending->isEmpty())
                <p class="text-muted p-3 mb-0">No pending applications. System checks daily via <code>php artisan ranks:check</code>.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Email</th><th>Rank</th><th>Reward</th><th>Detected</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $ur)
                            <tr>
                                <td><strong>{{ $ur->user->name }}</strong></td>
                                <td><small>{{ $ur->user->email }}</small></td>
                                <td><span class="badge badge-primary">{{ $ur->rank_name }}</span></td>
                                <td>{{ $ur->rank->rewardLabel() }}</td>
                                <td><small>{{ optional($ur->detected_at)->format('d M Y H:i') }}</small></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.rank.approve', $ur->id) }}" enctype="multipart/form-data" class="d-inline-block">
                                        @csrf
                                        <input type="file" name="congratulation_image" accept="image/*" required class="form-control form-control-sm mb-1" style="width:220px">
                                        <input type="text" name="admin_notes" class="form-control form-control-sm mb-1" placeholder="Congratulation message" style="width:220px">
                                        <button class="btn btn-sm btn-success"><i class="fas fa-check mr-1"></i> Approve + Upload</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.rank.reject', $ur->id) }}" class="d-inline-block">
                                        @csrf
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── HISTORY ── --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light font-weight-bold"><i class="fas fa-history mr-1"></i> Approved</div>
        <div class="card-body p-0">
            @if($approved->isEmpty())
                <p class="text-muted p-3 mb-0">No approved ranks yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>User</th><th>Rank</th><th>Reward</th><th>Reviewed</th><th>Picture</th></tr>
                    </thead>
                    <tbody>
                        @foreach($approved as $ur)
                            <tr>
                                <td>{{ $ur->user->name }}</td>
                                <td><span class="badge badge-primary">{{ $ur->rank_name }}</span></td>
                                <td>${{ number_format($ur->reward_amount, 2) }}</td>
                                <td><small>{{ optional($ur->reviewed_at)->format('d M Y') }}</small></td>
                                <td>
                                    @if($ur->congratulation_image)
                                        <a href="{{ asset('storage/' . $ur->congratulation_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
                                    @else — @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $approved->links() }}</div>
            @endif
        </div>
    </div>

</div>
</div>

@include('admin.admin-base')
<div class="content-wrapper">
<div class="container-fluid py-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h3 class="font-weight-bold"><i class="fas fa-cog text-secondary mr-2"></i> Rank Criteria Settings</h3>
    <p class="text-muted">Edit the 5 ranks. Changes apply to all future eligibility checks.</p>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('admin.rank.settings.update') }}">
        @csrf
        @method('PUT')

        @foreach($ranks as $rank)
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-primary text-white font-weight-bold">
                {{ $rank->order }}. {{ $rank->name }}
                <small class="float-right">slug: <code>{{ $rank->slug }}</code> · level {{ $rank->level }}</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small">Min Active Direct Referrals</label>
                        <input type="number" min="0" name="ranks[{{ $rank->id }}][min_active_direct_referrals]" class="form-control"
                               value="{{ $rank->min_active_direct_referrals }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Associates from Direct</label>
                        <input type="number" min="0" name="ranks[{{ $rank->id }}][min_associates_from_direct]" class="form-control"
                               value="{{ $rank->min_associates_from_direct }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Directors from Direct</label>
                        <input type="number" min="0" name="ranks[{{ $rank->id }}][min_directors_from_direct]" class="form-control"
                               value="{{ $rank->min_directors_from_direct }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small">Min Regional Supervisors from Direct</label>
                        <input type="number" min="0" name="ranks[{{ $rank->id }}][min_regional_supervisors_from_direct]" class="form-control"
                               value="{{ $rank->min_regional_supervisors_from_direct }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label class="small">Min Direct Ref Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][min_direct_referral_investment]" class="form-control"
                               value="{{ $rank->min_direct_referral_investment }}">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="small">Min Total Network Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][min_total_investment]" class="form-control"
                               value="{{ $rank->min_total_investment }}">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label class="small">Reward Amount ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][reward_amount]" class="form-control"
                               value="{{ $rank->reward_amount }}">
                    </div>

                    <div class="col-md-3 mt-2">
                        <label class="small">Reward % (for AM weekly)</label>
                        <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][reward_percentage]" class="form-control"
                               value="{{ $rank->reward_percentage }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small">AM: Min Qualifying Direct Refs</label>
                        <input type="number" min="0" name="ranks[{{ $rank->id }}][am_min_active_direct_investment_users]" class="form-control"
                               value="{{ $rank->am_min_active_direct_investment_users }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small">AM: Per-Ref Min Investment ($)</label>
                        <input type="number" min="0" step="0.01" name="ranks[{{ $rank->id }}][am_per_user_min_investment]" class="form-control"
                               value="{{ $rank->am_per_user_min_investment }}">
                    </div>
                    <div class="col-md-3 mt-2 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="ranks[{{ $rank->id }}][is_active]" value="1"
                                   id="active_{{ $rank->id }}" {{ $rank->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="active_{{ $rank->id }}">Active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Rank Criteria
            </button>
        </div>
    </form>

</div>
</div>

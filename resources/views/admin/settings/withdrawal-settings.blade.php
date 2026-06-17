@extends('admin.sidebar')
@section('contents')
<div class="container-fluid py-4 px-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-money-bill-wave text-warning mr-2"></i> Withdrawal Settings</h3>
    <p class="text-muted">Set limits and validation rules applied to all user withdrawals.</p>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('admin.settings.withdrawal-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="small text-muted">Minimum amount ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="min_amount" class="form-control"
                               value="{{ $settings->min_amount }}" required>
                        <small class="text-muted">Per spec: $10</small>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Max per transaction ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="max_per_transaction" class="form-control"
                               value="{{ $settings->max_per_transaction }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Daily limit ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="daily_limit" class="form-control"
                               value="{{ $settings->daily_limit }}" placeholder="leave blank for no limit">
                    </div>
                    <div class="col-md-3">
                        <label class="small text-muted">Monthly limit ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="monthly_limit" class="form-control"
                               value="{{ $settings->monthly_limit }}" placeholder="leave blank for no limit">
                    </div>

                    <div class="col-md-3 mt-3">
                        <label class="small text-muted">Default TRC-20 length</label>
                        <input type="number" min="26" max="64" name="default_trc20_min_length" class="form-control"
                               value="{{ $settings->default_trc20_min_length }}">
                    </div>
                    <div class="col-md-3 mt-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="require_admin_approval" value="1" id="require_approval"
                                   {{ $settings->require_admin_approval ? 'checked' : '' }}>
                            <label class="form-check-label" for="require_approval">
                                Manual withdrawals require admin approval
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="validate_trc20_format" value="1" id="validate_trc20"
                                   {{ $settings->validate_trc20_format ? 'checked' : '' }}>
                            <label class="form-check-label" for="validate_trc20">
                                Validate TRC-20 address format
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label class="small text-muted">Notes (internal)</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $settings->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save Settings
            </button>
        </div>
    </form>

</div>
@endsection

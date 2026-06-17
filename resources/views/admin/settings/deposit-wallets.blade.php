@extends('admin.sidebar')
@section('contents')
<div class="container-fluid py-4 px-4">

    <a href="{{ route('admin.referral.bonuses') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>

    <h3 class="font-weight-bold mb-3"><i class="fas fa-wallet text-warning mr-2"></i> Deposit Wallets</h3>
    <p class="text-muted">These are the company wallet addresses that users send USDT to when making deposits.</p>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('admin.settings.deposit-wallets.update') }}">
        @csrf
        @method('PUT')

        @foreach($wallets as $w)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-{{ $w->is_active ? 'success' : 'secondary' }} text-white">
                <strong>{{ $w->network }}</strong> · {{ $w->label }}
                <span class="float-right">{{ $w->currency }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="small text-muted">Wallet Address <span class="text-danger">*</span></label>
                        <input type="text" name="wallets[{{ $w->id }}][wallet_address]" class="form-control"
                               value="{{ $w->wallet_address }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted">Label</label>
                        <input type="text" name="wallets[{{ $w->id }}][label]" class="form-control"
                               value="{{ $w->label }}">
                    </div>
                    <div class="col-md-2">
                        <label class="small text-muted">Active?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                   name="wallets[{{ $w->id }}][is_active]" value="1"
                                   id="active_{{ $w->id }}" {{ $w->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="active_{{ $w->id }}">Yes</label>
                        </div>
                    </div>

                    <div class="col-md-3 mt-2">
                        <label class="small text-muted">Min amount ($)</label>
                        <input type="number" step="0.01" min="0" name="wallets[{{ $w->id }}][min_amount]" class="form-control"
                               value="{{ $w->min_amount }}">
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="small text-muted">Max amount ($, optional)</label>
                        <input type="number" step="0.01" min="0" name="wallets[{{ $w->id }}][max_amount]" class="form-control"
                               value="{{ $w->max_amount }}">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="small text-muted">Notes (internal)</label>
                        <input type="text" name="wallets[{{ $w->id }}][notes]" class="form-control"
                               value="{{ $w->notes }}">
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="text-right">
            <button type="submit" class="btn btn-success btn-lg font-weight-bold">
                <i class="fas fa-save mr-1"></i> Save All Wallets
            </button>
        </div>
    </form>

</div>
@endsection

@extends('admin.sidebar')
@section('contents')
<div class="container-fluid py-4 px-4">

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('message') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <header class="bg-blue-50 py-6 rounded px-4 mb-4">
        <h1 class="text-2xl uppercase text-slate-700 font-bold"><i class="fas fa-coins mr-2"></i>Token Price Settings</h1>
        <p class="text-gray-500 text-sm mt-1">All 5 token price types + coin value. Each is used in a specific part of the system.</p>
    </header>

    <div class="row">
        {{-- Current Values --}}
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark font-weight-bold"><i class="fas fa-tag mr-1"></i>Current Values</div>
                <div class="card-body">
                    @if($setting)
                    @php $s = $setting; @endphp
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted">Symbol</td><td class="font-weight-bold"><span class="badge badge-info">{{ $s->token_symbol }}</span></td></tr>
                        <tr class="border-top"><td colspan="2" class="text-muted small pt-2">Token Prices</td></tr>
                        <tr><td class="text-muted">UVP Purchase</td><td class="font-weight-bold">${{ number_format($s->uvp_price,6) }}</td></tr>
                        <tr><td class="text-muted">Renewal (30-day)</td><td class="font-weight-bold">${{ number_format($s->renewal_price,6) }}</td></tr>
                        <tr><td class="text-muted">Swap</td><td class="font-weight-bold">${{ number_format($s->swap_price,6) }}</td></tr>
                        <tr><td class="text-muted">Trading <small class="text-muted">(reserved)</small></td><td class="font-weight-bold">${{ number_format($s->trading_price,6) }}</td></tr>
                        <tr><td class="text-muted">Package <small class="text-muted">(reserved)</small></td><td class="font-weight-bold">${{ number_format($s->package_price,6) }}</td></tr>
                        <tr class="border-top"><td class="text-muted">Coin Value</td><td class="font-weight-bold text-success">${{ number_format($s->coin_value,6) }}</td></tr>
                    </table>
                    <hr>
                    <p class="text-muted small mb-1">Example — $1,000 investment at UVP price:</p>
                    @php $ex = $s->uvp_price > 0 ? 1000/$s->uvp_price : 0; @endphp
                    <p class="mb-0"><strong>{{ number_format($ex,0) }}</strong> {{ $s->token_symbol }} locked tokens</p>
                    <p class="text-muted small">Swap 1,000 tokens → ${{ number_format(1000*$s->swap_price,2) }} cashout
                        <small class="text-muted">(uses <code>swap_price</code>; <code>coin_value</code> is display-only)</small>
                    </p>
                    <p class="text-muted small mb-0">Last updated: {{ $s->updated_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Update Form --}}
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white font-weight-bold"><i class="fas fa-edit mr-1"></i>Update Token Prices</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.token-settings.update') }}">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Token Symbol <span class="text-danger">*</span></label>
                                    <input type="text" name="token_symbol" class="form-control" value="{{ old('token_symbol', $setting->token_symbol ?? 'FONE') }}" required maxlength="20">
                                    <small class="text-muted">Display symbol e.g. FONE</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Coin Value (USD per token) <span class="text-danger">*</span></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="coin_value" class="form-control" step="0.000001" min="0.000001" value="{{ old('coin_value', $setting->coin_value ?? '0.002000') }}" required></div>
                                    <small class="text-muted">Used for swap & token withdrawal value display</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p class="font-weight-bold text-muted mb-2">Token Purchase & Usage Prices</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">UVP Purchase Price <span class="text-danger">*</span></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="uvp_price" class="form-control" step="0.000001" min="0.000001" value="{{ old('uvp_price', $setting->uvp_price ?? '0.002500') }}" required></div>
                                    <small class="text-muted">investment ÷ this = LOCKED tokens on purchase</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Renewal Price (every 30 days) <span class="text-danger">*</span></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="renewal_price" class="form-control" step="0.000001" min="0.000001" value="{{ old('renewal_price', $setting->renewal_price ?? '0.002500') }}" required></div>
                                    <small class="text-muted">trading_voucher ÷ this = AVAILABLE tokens on renewal</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Swap Price <span class="text-danger">*</span></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="swap_price" class="form-control" step="0.000001" min="0.000001" value="{{ old('swap_price', $setting->swap_price ?? '0.002000') }}" required></div>
                                    <small class="text-muted">
                                        <strong>Used by /user/token/swap</strong> — tokens × this = USD credited to user's Cashout.
                                        Example: 100,000 tokens × $0.0025 = $250.00 cashout.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-muted">Trading Price <small>(reserved — not in use)</small></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="trading_price" class="form-control" step="0.000001" min="0.000001" value="{{ old('trading_price', $setting->trading_price ?? '0.002500') }}"></div>
                                    <small class="text-muted"><i class="fas fa-clock mr-1"></i>Reserved for the future token buy/sell trading module.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-muted">Package Price <small>(reserved — not in use)</small></label>
                                    <div class="input-group"><div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                    <input type="number" name="package_price" class="form-control" step="0.000001" min="0.000001" value="{{ old('package_price', $setting->package_price ?? '0.002500') }}"></div>
                                    <small class="text-muted"><i class="fas fa-clock mr-1"></i>Reserved for the future referral-package purchase flow.</small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Notes <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Reason for price change"></textarea>
                        </div>
                        <div class="alert alert-warning py-2 small">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Price changes affect <strong>future</strong> transactions only. Existing balances are not recalculated.
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Save All Prices
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Reusable card for one deposit_wallets row. Used by the 3 sections
     (crypto / advcash / perfect_money) on the admin deposit-wallets page. --}}
@php
    $addrInvalid = $w->wallet_address && method_exists($w, 'addressLooksValid') && ! $w->addressLooksValid();
@endphp
<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-{{ $w->is_active ? 'success' : 'secondary' }} text-white d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ $w->kindLabel() }}</strong>
            @if($showNetwork && $w->network)
                · <span class="badge badge-light text-dark">{{ $w->network }}</span>
            @endif
            · {{ $w->label }}
        </div>
        <span class="badge badge-light text-dark">{{ $w->currency }}</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <label class="small text-muted">
                    @if($w->type === 'crypto') Wallet Address
                    @elseif($w->type === 'advcash') Advcash Account Number
                    @else Perfect Money Account Number
                    @endif
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="wallets[{{ $w->id }}][wallet_address]"
                       class="form-control{{ $addrInvalid ? ' is-invalid' : '' }}"
                       value="{{ $w->wallet_address }}"
                       placeholder="{{ $w->type === 'crypto' ? $w->network . ' address' : 'Account number' }}">
                @if($addrInvalid)
                    <small class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        Address doesn't match the expected {{ $w->network }} format.
                    </small>
                @endif
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

            @if($showNetwork)
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Display order</label>
                <input type="number" min="0" name="wallets[{{ $w->id }}][display_order]"
                       class="form-control" value="{{ $w->display_order }}">
            </div>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Min amount ($)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[{{ $w->id }}][min_amount]" class="form-control"
                       value="{{ $w->min_amount }}">
            </div>
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Max amount ($, optional)</label>
                <input type="number" step="0.01" min="0"
                       name="wallets[{{ $w->id }}][max_amount]" class="form-control"
                       value="{{ $w->max_amount }}">
            </div>
            @else
            <div class="col-md-3 mt-2">
                <label class="small text-muted">Display order</label>
                <input type="number" min="0" name="wallets[{{ $w->id }}][display_order]"
                       class="form-control" value="{{ $w->display_order }}">
            </div>
            @endif

            <div class="col-md-3 mt-2">
                <label class="small text-muted">Notes (internal)</label>
                <input type="text" name="wallets[{{ $w->id }}][notes]" class="form-control"
                       value="{{ $w->notes }}">
            </div>

            @if(!$showNetwork)
            <div class="col-md-12 mt-2">
                <label class="small text-muted">
                    Step-by-step instructions shown to user
                </label>
                <textarea name="wallets[{{ $w->id }}][instructions]" class="form-control" rows="6"
                          placeholder="Each line will be numbered for the user.">{{ $w->instructions }}</textarea>
            </div>
            @endif
        </div>
    </div>
</div>

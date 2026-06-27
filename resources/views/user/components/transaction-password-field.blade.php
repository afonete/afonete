<div class="form-group mt-3">
    <label class="font-weight-bold">Second Transaction Password <span class="text-danger">*</span></label>
    <input type="password"
           name="transaction_password"
           class="form-control"
           required
           autocomplete="current-password"
           placeholder="Enter your second transaction password">
    @error('transaction_password')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>
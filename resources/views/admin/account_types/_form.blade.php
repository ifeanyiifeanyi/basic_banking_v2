<div class="mb-3">
    <label for="account_type" class="form-label">Account Type Name</label>
    <input type="text" class="form-control @error('account_type') is-invalid @enderror" id="account_type"
           name="account_type" value="{{ old('account_type', $accountType->account_type ?? '') }}" >
    @error('account_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="code" class="form-label">Code</label>
    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code"
           name="code" value="{{ old('code', $accountType->code ?? '') }}" >
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
              name="description" rows="3">{{ old('description', $accountType->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="minimum_balance" class="form-label">Minimum Balance</label>
    <input type="number" step="0.01" class="form-control @error('minimum_balance') is-invalid @enderror"
           id="minimum_balance" name="minimum_balance"
           value="{{ old('minimum_balance', $accountType->minimum_balance ?? '0.00') }}" >
    @error('minimum_balance')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="interest_rate" class="form-label">Interest Rate (%)</label>
    <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror"
           id="interest_rate" name="interest_rate"
           value="{{ old('interest_rate', $accountType->interest_rate ?? '0.00') }}" >
    @error('interest_rate')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
           {{ old('is_active', $accountType->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active</label>
</div>

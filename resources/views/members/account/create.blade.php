@extends('members.layouts.member')

@section('title', 'Open New Account')

@section('member')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Open New Account</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4 card-title">Account Details</h4>

                    <form action="{{ route('member.accounts.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <select class="form-select @error('account_type_id') is-invalid @enderror"
                                    name="account_type_id" required>
                                <option value="">Select Account Type</option>
                                @foreach($accountTypes as $type)
                                    <option value="{{ $type->id }}"
                                            data-minimum-balance="{{ $type->minimum_balance }}">
                                        {{ $type->account_type }}
                                        (Min. Balance: {{ number_format($type->minimum_balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('account_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Currency</label>
                            <select class="form-select @error('currency_id') is-invalid @enderror"
                                    name="currency_id" required>
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}">
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Initial Deposit</label>
                            <input type="number" class="form-control @error('initial_balance') is-invalid @enderror"
                                   name="initial_balance" step="0.01" required>
                            @error('initial_balance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                            <a href="{{ route('member.accounts.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4 card-title">Account Type Information</h4>
                    <div class="table-responsive">
                        <table class="table mb-0 table-centered">
                            <thead>
                                <tr>
                                    <th>Account Type</th>
                                    <th>Minimum Balance</th>
                                    <th>Interest Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accountTypes as $type)
                                <tr>
                                    <td>{{ $type->account_type }}</td>
                                    <td>{{ number_format($type->minimum_balance, 2) }}</td>
                                    <td>{{ $type->interest_rate }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
document.querySelector('select[name="account_type_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const minimumBalance = selectedOption.dataset.minimumBalance;
    const initialBalanceInput = document.querySelector('input[name="initial_balance"]');
    initialBalanceInput.min = minimumBalance;
    initialBalanceInput.setAttribute('placeholder', `Minimum ${minimumBalance}`);
});
</script>
@endsection

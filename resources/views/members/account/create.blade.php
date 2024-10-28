@extends('members.layouts.member')

@section('title', 'Open New Account')

@section('member')
    <div class="container-fluid">
        <x-alert-info/>
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4 card-title">Account Details</h4>

                        <form action="{{ route('member.account.store') }}" method="POST">
                            <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Account Type</label>
                                <select class="form-select @error('account_type_id') is-invalid @enderror"
                                    name="account_type_id" >
                                    <option value="">Select Account Type</option>
                                    @foreach ($accountTypes as $type)
                                        @if (!$userAccounts->contains('account_type_id', $type->id))
                                            <option value="{{ $type->id }}"
                                                data-minimum-balance="{{ $type->minimum_balance }}">
                                                {{ $type->account_type }}
                                                (Min. Balance: {{ number_format($type->minimum_balance, 2) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('account_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="initial_balance">Initial Deposit</label>
                                <div class="input-group">
                                    <span  class="input-group-text">{{ $currency->symbol }}</span>
                                    <input type="number" step="0.01" name="initial_balance" id="initial_balance"
                                        class="form-control @error('initial_balance') is-invalid @enderror"
                                        >
                                </div>
                                @error('initial_balance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Create Account</button>
                                <a href="{{ route('member.account.index') }}" class="btn btn-secondary">Cancel</a>
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
                                        <th>Status</th>
                                        <th>Acc</th>
                                        <th>Min</th>
                                        <th>Int`R</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accountTypes as $type)
                                        <tr>
                                            <td>
                                                @if ($userAccounts->contains('account_type_id', $type->id))
                                                    <span class="badge bg-success">Created</span>
                                                @else
                                                    <span class="badge bg-info">Available</span>
                                                @endif
                                            </td>
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

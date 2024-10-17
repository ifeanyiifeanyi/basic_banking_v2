@extends('admin.layouts.admin')

@section('title', 'Create Account')

@section('admin')
    <div class="container">
        <x-alert-info/>
        <div class="card">
            <div class="card-header">
                <h2>Create Account for {{ $user->full_name }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store_account', $user) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="account_type_id">Account Type</label>
                        <select class="form-control" id="account_type_id" name="account_type_id" required>
                            @foreach ($accountTypes as $accountType)
                                <option value="{{ $accountType->id }}">{{ $accountType->account_type }}</option>
                            @endforeach
                        </select>
                        @error('account_type_id')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="currency_id">Currency</label>
                        <select class="form-control" id="currency_id" name="currency_id" required>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="initial_balance">Initial Balance</label>
                        <input type="number" class="form-control" id="initial_balance" name="initial_balance"
                            step="0.01" required>
                        @error('initial_balance')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </form>
            </div>
        </div>
    </div>
@endsection

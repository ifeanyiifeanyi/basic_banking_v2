@extends('members.layouts.member')

@section('title', 'Dashboard')

@section('member')
    <div class="container-fluid">

        @if (!$hasAnyAccounts)
            <div class="alert alert-info">
                <h5>Welcome to {{ config('app.name') }}!</h5>
                <p>To start using our banking services, you'll need to create your first bank account.</p>
                <a href="" class="btn btn-primary">Create Your First Account</a>
            </div>
        @elseif($canCreateMoreAccounts)
            <div class="card">
                <div class="card-body">
                    <p>You currently have {{ $accountCount }} out of {{ $maxAccounts }} possible accounts.</p>
                    <a href="" class="btn btn-secondary">Create Another Account</a>
                </div>
            </div>
        @endif
        <!-- Account Summary Cards -->
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="mb-3 card-title">Total Balance</h5>
                                <h4 class="mb-0">{{ $currency->symbol }}{{ number_format($totalBalance, 2) }}</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="avatar">
                                    <div class="rounded avatar-title bg-primary">
                                        <i class="bx bx-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accounts List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 d-flex align-items-center">
                            <h5 class="card-title flex-grow-1">Your Accounts</h5>
                            @if ($accounts->count() < 3)
                                <a href="" class="btn btn-primary">Open New Account</a>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table mb-0 table-centered table-nowrap">
                                <thead>
                                    <tr>
                                        <th>Account Number</th>
                                        <th>Type</th>
                                        <th>Currency</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ $account->account_number }}</td>
                                            <td>{{ $account->accountType->account_type }}</td>
                                            <td>{{ $account->currency->currency }}</td>
                                            <td>{{ number_format($account->account_balance, 2) }}</td>
                                            <td>
                                                @if ($account->is_suspended)
                                                    <span class="badge bg-danger">Suspended</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=''>
                                                    View Details
                                                </button>
                                            </td>
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

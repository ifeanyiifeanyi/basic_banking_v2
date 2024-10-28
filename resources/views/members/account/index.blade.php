@extends('members.layouts.member')

@section('title', 'My Accounts Dashboard')

@section('css')
    <style>
        .card {
            transition: transform 0.2s ease-in-out;
            border-radius: 0.75rem;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-light {
            background: #f8f9fa;
            border: 1px solid #eee;
        }

        .btn-light:hover {
            background: #e9ecef;
        }

        .account-type-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(var(--bs-primary-rgb), 0.1);
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
@endsection
@section('member')
    <div class="container-fluid">
        <x-alert-info />

        <!-- Page Title & Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0">Manage and monitor your banking accounts</p>
                    </div>
                    @if (auth()->user()->accounts->count() < 3)
                        <a href="{{ route('member.account.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus-circle me-1"></i> Open New Account
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if ($accounts->isEmpty())
            <!-- Empty State Card -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bx bx-bank text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="mb-3">Welcome to Your Banking Journey</h4>
                            <p class="text-muted mb-4">Start your financial journey by opening your first account with us.
                                Enjoy secure banking with great benefits.</p>
                            <a href="{{ route('member.account.create') }}" class="btn btn-primary px-4">
                                <i class="bx bx-plus-circle me-2"></i> Open Your First Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Account Summary Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 bg-primary text-white">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="text-white-50">Total Balance</h5>
                                    <h2 class="text-white mb-0">
                                        ${{ number_format($accounts->sum('account_balance'), 2) }}
                                    </h2>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-white-50">Active Accounts</h5>
                                    <h2 class="text-white mb-0">{{ $accounts->count() }}</h2>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="text-white-50">Last Transaction</h5>
                                    <h2 class="text-white mb-0">
                                        @if ($lastTransaction = $accounts->flatMap->transactions->sortByDesc('created_at')->first())
                                            ${{ number_format($lastTransaction->amount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Cards -->
            <div class="row">
                @foreach ($accounts as $account)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="card-title text-dark mb-1">{{ $account->accountType->account_type }}</h5>
                                        <p class="text-muted small mb-0">
                                            <i class="bx bx-credit-card me-1"></i>
                                            {{ substr_replace($account->account_number, '****', 4, 4) }}
                                        </p>
                                    </div>
                                    <div class="account-type-icon">
                                        @switch($account->accountType->account_type)
                                            @case('Savings')
                                                <i class="bx bx-piggy-bank text-success fs-2"></i>
                                            @break

                                            @case('Checking')
                                                <i class="bx bx-wallet text-primary fs-2"></i>
                                            @break

                                            @default
                                                <i class="bx bx-bank text-info fs-2"></i>
                                        @endswitch
                                    </div>
                                </div>

                                <div class="account-details mt-4">
                                    <div class="row g-3 text-center">
                                        <div class="col-6">
                                            <label class="text-muted small">Available Balance</label>
                                            <h4 class="mb-0">${{ number_format($account->account_balance, 2) }}</h4>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-muted small">Interest Rate</label>
                                            <h4 class="mb-0">{{ $account->accountType->interest_rate }}%</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="account-actions mt-4">
                                    <div class="row g-2 text-center">
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-light btn-sm w-100">
                                                <i class="bx bx-transfer-alt"></i>
                                                <span class="d-md-inline ms-1">Transfer</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('member.account.show', $account->id) }}" class="btn btn-light btn-sm w-100">
                                                <i class="bx bx-history"></i>
                                                <span class="d-md-inline ms-1">History</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($account->transactions->isNotEmpty())
                                <div class="card-footer bg-light border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Last Transaction</small>
                                        <small
                                            class="text-{{ $account->transactions->first()->type === 'credit' ? 'success' : 'danger' }}">
                                            {{ $account->transactions->first()->type === 'credit' ? '+' : '-' }}
                                            ${{ number_format($account->transactions->first()->amount, 2) }}
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Quick Actions</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-light w-100 py-3">
                                        <i class="bx bx-transfer text-primary mb-2 fs-2"></i>
                                        <p class="mb-0">Transfer Money</p>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('member.account.report') }}" class="btn btn-light w-100 py-3">
                                        <i class="bx bx-history text-info mb-2 fs-2"></i>
                                        <p class="mb-0">Transaction History</p>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn btn-light w-100 py-3">
                                        <i class="bx bx-cog text-warning mb-2 fs-2"></i>
                                        <p class="mb-0">Account Settings</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

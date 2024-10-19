@extends('admin.layouts.admin')

@section('title', 'Account Details')

@section('admin')
    <div class="container py-4">
        <x-alert-info/>
        <!-- Credit Card Style Account Details -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="credit-card">
                    <div class="credit-card-inner">
                        <div class="card-chip">
                            <img src="{{ asset('chip.png') }}" alt="Card Chip">
                        </div>
                        <div class="card-number">
                            {{ chunk_split($account->account_number, 4, ' ') }}
                        </div>
                        <div class="card-details">
                            <div class="card-holder">
                                <div class="label">ACCOUNT HOLDER</div>
                                <div class="value">{{ $account->user->full_name }}</div>
                            </div>
                            <div class="card-expires">
                                <div class="label">ACCOUNT TYPE</div>
                                <div class="value">{{ $account->accountType->account_type }}</div>
                            </div>
                            <div class="card-balance">
                                <div class="label">BALANCE</div>
                                <div class="value">{{ $account->currency->symbol }}
                                    {{ number_format($account->account_balance, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Transaction Forms -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success bg-opacity-10">
                        <h4 class="card-title text-white mb-0">Credit Account</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.users.credit_account', [$account->user->id, $account->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="transaction_type" value="credit">
                            <div class="form-group mb-3">
                                <label for="credit_amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $account->currency->symbol }}</span>
                                    <input type="number" step="0.01" min="0.01"
                                        class="form-control @error('amount') is-invalid @enderror" id="credit_amount"
                                        name="amount" >
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="credit_description" class="form-label">Description</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="credit_description" name="description" >
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success w-100">Credit Account</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-danger bg-opacity-10">
                        <h4 class="card-title text-white mb-0">Debit Account</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.users.debit_account', [$account->user->id, $account->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="transaction_type" value="credit">
                            <div class="form-group mb-3">
                                <label for="debit_amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ $account->currency->symbol }}</span>
                                    <input type="number" step="0.01" min="0.01"
                                        class="form-control @error('amount') is-invalid @enderror" id="debit_amount"
                                        name="amount" >
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="debit_description" class="form-label">Description</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="debit_description" name="description" >
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Debit Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="card-title mb-0">Transaction History</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Date</th>
                                <th>Reference No.</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($account->transactions()->latest()->get() as $transaction)
                                <tr>
                                    <td class="px-4">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $transaction->reference_number }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->transaction_type === 'credit' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $account->currency->symbol }} {{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <style>
        /* Credit Card Responsive Styles */
        .credit-card {
            perspective: 1000px;
            width: 100%;
            padding-bottom: 56.25%;
            /* 16:9 Aspect Ratio */
            position: relative;
        }

        .credit-card-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #1a1a1a, #434343);
            border-radius: clamp(10px, 3vw, 25px);
            padding: clamp(1rem, 4vw, 2.5rem);
            color: white;
            overflow: hidden;
        }

        .credit-card-inner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='rgba(255,255,255,0.05)' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 1;
        }

        .card-chip {
            width: clamp(30px, 8vw, 60px);
            aspect-ratio: 1.5/1;
            margin-bottom: clamp(1rem, 3vw, 2rem);
        }

        .card-chip img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-size: clamp(1rem, 3vw, 1.8rem);
            letter-spacing: 0.2em;
            margin-bottom: clamp(1rem, 4vw, 2rem);
            word-spacing: 0.6em;
        }

        .card-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: clamp(0.5rem, 2vw, 1.5rem);
        }

        .card-holder,
        .card-expires,
        .card-balance {
            min-width: 0;
            /* Prevents text overflow in grid items */
        }

        .label {
            font-size: clamp(0.6rem, 1.5vw, 0.8rem);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.3em;
        }

        .value {
            font-size: clamp(0.8rem, 2vw, 1.2rem);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Additional responsive adjustments */
        @media (max-width: 576px) {
            .credit-card {
                padding-bottom: 65%;
                /* Slightly taller aspect ratio for mobile */
            }

            .card-details {
                grid-template-columns: 1fr;
                /* Stack on very small screens */
                gap: 0.8rem;
            }
        }

        @media (min-width: 992px) {
            .credit-card {
                padding-bottom: 50%;
                /* Slightly shorter aspect ratio for larger screens */
            }
        }

        /* Add a hover effect for desktop */
        @media (hover: hover) {
            .credit-card-inner {
                transition: transform 0.3s ease-in-out;
            }

            .credit-card:hover .credit-card-inner {
                transform: scale(1.02);
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .credit-card-inner {
                background: #000;
                border: 2px solid #fff;
            }

            .label {
                color: #fff;
            }
        }

        /* Reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            .credit-card-inner {
                transition: none;
            }
        }
    </style>
    <style>
        .bg-gradient-primary {
            position: relative;
            overflow: hidden;
        }

        .bg-gradient-primary::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='rgba(255,255,255,0.05)' fill-rule='evenodd'/%3E%3C/svg%3E");
        }
    </style>
@endsection

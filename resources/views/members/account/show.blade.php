@extends('members.layouts.member')

@section('title', 'Account Details')

@section('member')
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-dark fw-bold mb-0">Transaction History</h4>
                    <p class="text-muted mb-0">View and track all your account transactions</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary me-2 mt-2" onclick="window.print()">
                        <i class="bx bx-printer me-1"></i> Print
                    </button>
                    <a href="{{ route('member.account.export-transactions', $accountDetails->id) }}" class="btn btn-primary mt-2" id="exportBtn">
                        <i class="bx bx-download me-1"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Print-only account details -->
    <div class="account-details-print">
        <h3>Account Statement</h3>
        <p>Account Number: {{ $accountDetails->account_number }}</p>
        <p>Account Type: {{ $accountDetails->accountType->account_type }}</p>
        <p>Statement Date: {{ now()->format('M d, Y') }}</p>
    </div>
    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('member.account.show', $accountDetails->id) }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    <a href="{{ route('member.account.show', $accountDetails->id) }}" class="btn btn-light">
                        <i class="bx bx-reset me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    <!-- Transactions Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>REF</th>
                            <th>Account</th>
                            <th>DES</th>
                            <th>Type</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($account->transactions) --}}
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="font-monospace">{{ $transaction->reference_number }}</span>
                                </td>
                                <td>
                                    <div>{{ substr_replace($transaction->account->account_number, '****', 4, 4) }}
                                    </div>
                                    <small
                                        class="text-muted">{{ $transaction->account->accountType->account_type }}</small>
                                </td>
                                <td>
                                    <div>{{ $transaction->description }}</div>
                                    @if ($transaction->recipient_name)
                                        <small class="text-muted">To: {{ $transaction->recipient_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $transaction->transaction_type === 'credit' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $transaction->transaction_type === 'credit' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaction->transaction_type) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span
                                        class="text-{{ $transaction->transaction_type === 'credit' ? 'success' : 'danger' }}">
                                        {{ $transaction->transaction_type === 'credit' ? '+' : '-' }}
                                        ${{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-receipt fs-1 mb-2"></i>
                                        <p>No transactions found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>



@endsection
@section('css')
    <style>
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        /* Print styles */
        @media print {

            /* Hide non-printable elements */
            .navbar,
            .sidebar,
            .btn,
            .card .card-body form {
                display: none !important;
            }

            /* Show account details at the top */
            .account-details-print {
                display: block !important;
                margin-bottom: 20px;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            /* Format table for printing */
            .table {
                font-size: 12px;
                width: 100%;
            }

            .table td,
            .table th {
                padding: 8px;
            }

            /* Ensure status badges are visible */
            .badge {
                border: 1px solid #000;
                padding: 2px 5px;
            }

            @page {
                margin: 2cm;
                size: portrait;
            }
        }

        /* Hide print-only elements during normal viewing */
        .account-details-print {
            display: none;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle export button
            document.getElementById('exportBtn').addEventListener('click', function() {
                // Get current filter parameters
                const urlParams = new URLSearchParams(window.location.search);
                const filterParams = urlParams.toString();

                // Redirect to export route with current filters
                window.location.href =
                    '{{ route('member.account.export-transactions', $accountDetails->id) }}?' +
                    filterParams;
            });

            // Handle print button
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });
        });
    </script>
@endsection

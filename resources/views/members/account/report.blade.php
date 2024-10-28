@extends('members.layouts.member')

@section('title', 'Transaction Report')

@section('member')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-dark fw-bold mb-0">Transaction Report</h4>
                        <p class="text-muted mb-0">Complete overview of all your account transactions</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary me-2" id="printBtn">
                            <i class="bx bx-printer me-1"></i> Print
                        </button>
                        <button class="btn btn-primary" id="exportBtn">
                            <i class="bx bx-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print-only header -->
        <div class="print-only">
            <h3>Transaction Report</h3>
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4 no-print">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Account</label>
                        <select name="account_id" class="form-select">
                            <option value="">All Accounts</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->account_number }} ({{ $account->accountType->account_type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Transactions</h6>
                        <h4 class="mb-0">{{ number_format($summary['total_transactions']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Credits</h6>
                        <h4 class="mb-0 text-success">${{ number_format($summary['total_credit'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Total Debits</h6>
                        <h4 class="mb-0 text-danger">${{ number_format($summary['total_debit'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">Net Balance</h6>
                        <h4 class="mb-0 text-primary">${{ number_format($summary['net_balance'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account-wise Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Account Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th>Total Transactions</th>
                                <th>Total Credits</th>
                                <th>Total Debits</th>
                                <th>Net Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accountSummaries as $accountSummary)
                                <tr>
                                    <td>
                                        {{ $accountSummary['account']->account_number }}
                                        <br>
                                        <small
                                            class="text-muted">{{ $accountSummary['account']->accountType->account_type }}</small>
                                    </td>
                                    <td>{{ number_format($accountSummary['total_transactions']) }}</td>
                                    <td class="text-success">${{ number_format($accountSummary['total_credit'], 2) }}</td>
                                    <td class="text-danger">${{ number_format($accountSummary['total_debit'], 2) }}</td>
                                    <td class="text-primary">${{ number_format($accountSummary['net_balance'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">All Transactions</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Account</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $transaction->account->account_number }}</div>
                                        <small
                                            class="text-muted">{{ $transaction->account->accountType->account_type }}</small>
                                    </td>
                                    <td>
                                        <span class="font-monospace">{{ $transaction->reference_number }}</span>
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
                                            {{ $transaction->transaction_type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-search-alt bx-lg mb-3 d-block"></i>
                                            <h6>No transactions found</h6>
                                            <p class="mb-0">Try adjusting your filters or selecting a different date
                                                range</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('css')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table {
                border-color: #dee2e6 !important;
            }

            .badge {
                border: 1px solid #dee2e6 !important;
            }
        }

        .print-only {
            display: none;
        }
    </style>
@endsection
@section('javascript')
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            window.print();
        });

        document.getElementById('exportBtn').addEventListener('click', function() {
            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            // Build export URL with current filters
            let exportUrl = '{{ route('member.account.exportReport') }}';
            if (urlParams.toString()) {
                exportUrl += '?' + urlParams.toString();
            }

            // Trigger download
            window.location.href = exportUrl;
        });

        // Initialize date inputs with better date picker if available
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            if (flatpickr) {
                flatpickr(input, {
                    dateFormat: "Y-m-d",
                    maxDate: "today"
                });
            }
        });
    </script>
@endsection

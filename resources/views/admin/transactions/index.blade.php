{{-- resources/views/admin/transactions/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Transactions')

@section('admin')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h2>Transactions</h2>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown">
                    Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.transaction.export.csv') }}">Export CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.transaction.export.excel') }}">Export Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.transaction.export.pdf') }}">Export PDF</a></li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Transactions</h5>
                        <h2>{{ number_format($transactionCount) }}</h2>
                        <p class="mb-0">Value: {{ $currency->symbol }}{{ number_format($totalAmount, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Completed</h5>
                        <h2>{{ number_format($transactions->where('status', 'completed')->count()) }}</h2>
                        <p class="mb-0">Value:
                            {{ $currency->symbol }}{{ number_format($transactions->where('status', 'completed')->sum('amount'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <h2>{{ number_format($transactions->where('status', 'pending')->count()) }}</h2>
                        <p class="mb-0">Value:
                            {{ $currency->symbol }}{{ number_format($transactions->where('status', 'pending')->sum('amount'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Failed/Cancelled</h5>
                        <h2>{{ number_format($transactions->whereIn('status', ['failed', 'cancelled'])->count()) }}</h2>
                        <p class="mb-0">Value:
                            {{ $currency->symbol }}{{ number_format($transactions->whereIn('status', ['failed', 'cancelled'])->sum('amount'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.transaction.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Reference, Customer name, email...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" style="width: 100%">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaction_type">Type</label>
                                <select name="transaction_type" id="transaction_type" class="form-control" style="width: 100%">
                                    <option value="">All Types</option>
                                    <option value="deposit"
                                        {{ request('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                    <option value="withdrawal"
                                        {{ request('transaction_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal
                                    </option>
                                    <option value="transfer"
                                        {{ request('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" name="date_from" id="date_from" class="form-control"
                                    value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" name="date_to" id="date_to" class="form-control"
                                    value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Reference</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_transactions[]"
                                            value="{{ $transaction->id }}" class="form-check-input transaction-checkbox">
                                    </td>
                                    <td>{{ $transaction->reference_number }}</td>
                                    <td>
                                        {{ $transaction->user->full_name ?? 'N/A' }}
                                        <br>
                                        <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->transaction_type == 'deposit'
                                                ? 'success'
                                                : ($transaction->transaction_type == 'withdrawal'
                                                    ? 'danger'
                                                    : 'info') }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $transaction->status == 'completed'
                                                ? 'success'
                                                : ($transaction->status == 'pending'
                                                    ? 'warning'
                                                    : ($transaction->status == 'failed'
                                                        ? 'danger'
                                                        : 'secondary')) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.transaction.show', $transaction) }}"
                                                class="btn btn-sm btn-info">View</a>
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Action
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form
                                                        action="{{ route('admin.transaction.update-status', $transaction) }}"
                                                        method="POST" class="status-update-form">
                                                        @csrf
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="dropdown-item">Mark
                                                            Completed</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form
                                                        action="{{ route('admin.transaction.update-status', $transaction) }}"
                                                        method="POST" class="status-update-form">
                                                        @csrf
                                                        <input type="hidden" name="status" value="failed">
                                                        <button type="submit" class="dropdown-item">Mark Failed</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="bulk-actions" style="display: none;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    Bulk Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item bulk-action" data-action="completed" href="#">Mark
                                            Completed</a></li>
                                    <li><a class="dropdown-item bulk-action" data-action="failed" href="#">Mark
                                            Failed</a></li>
                                    <li><a class="dropdown-item bulk-action" data-action="export" href="#">Export
                                            Selected</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="float-end">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle select all checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            const transactionCheckboxes = document.querySelectorAll('.transaction-checkbox');
            const bulkActionsDiv = document.querySelector('.bulk-actions');

            selectAllCheckbox.addEventListener('change', function() {
                transactionCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsVisibility();
            });

            transactionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActionsVisibility);
            });

            function updateBulkActionsVisibility() {
                const checkedBoxes = document.querySelectorAll('.transaction-checkbox:checked');
                bulkActionsDiv.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
            }

            // Handle bulk actions
            document.querySelectorAll('.bulk-action').forEach(action => {
                action.addEventListener('click', function(e) {
                    e.preventDefault();
                    const actionType = this.dataset.action;
                    const selectedIds = Array.from(document.querySelectorAll(
                            '.transaction-checkbox:checked'))
                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        alert('Please select at least one transaction');
                        return;
                    }

                    if (confirm(
                            'Are you sure you want to perform this action on the selected transactions?'
                        )) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route('admin.transaction.bulk-action') }}';

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';

                        const actionInput = document.createElement('input');
                        actionInput.type = 'hidden';
                        actionInput.name = 'action';
                        actionInput.value = actionType;

                        const idsInput = document.createElement('input');
                        idsInput.type = 'hidden';
                        idsInput.name = 'ids';
                        idsInput.value = JSON.stringify(selectedIds);

                        form.appendChild(csrfInput);
                        form.appendChild(actionInput);
                        form.appendChild(idsInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // Handle status update confirmations
            document.querySelectorAll('.status-update-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (confirm(
                            'Are you sure you want to update the status of this transaction?')) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection

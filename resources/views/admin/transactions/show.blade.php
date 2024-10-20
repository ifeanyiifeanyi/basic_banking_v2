@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('css')

@endsection


@section('admin')
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col">
                <h2>Transaction Details</h2>
            </div>
            <div class="col text-end">
                <a href="{{ route('admin.transaction.index') }}" class="btn btn-secondary">
                    Back to Transactions
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <a href="{{ route('admin.transaction.index') }}" style="background: purple;color:white" class="btn btn-sm">All Transactions</a>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Reference Number:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $transaction->reference_number }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Customer:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $transaction->user->full_name ?? '' }}
                                <br>
                                <small class="text-muted">{{ $transaction->user->email ?? '' }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Account:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $transaction->account->account_number ?? '' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Amount:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $currency->symbol }}{{ number_format($transaction->amount, 2) }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Type:</strong>
                            </div>
                            <div class="col-md-8">
                                <span
                                    class="badge bg-{{ $transaction->transaction_type == 'deposit' ? 'success' : ($transaction->transaction_type == 'withdrawal' ? 'danger' : 'info') }}">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-md-8">
                                <form action="{{ route('admin.transaction.update-status', $transaction) }}" method="POST"
                                    class="d-flex gap-2">
                                    @csrf
                                    <select name="status" class="form-control w-auto">
                                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="completed"
                                            {{ $transaction->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>
                                            Failed</option>
                                        <option value="cancelled"
                                            {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary ml-3">Update Status</button>
                                </form>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Date:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                            </div>
                        </div>

                        @if ($transaction->description)
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Str::title($transaction->description) }}
                                </div>
                            </div>
                        @endif

                        @foreach (json_decode($transaction->submitted_requirements) as $key => $value)
                            <p><b>{{ Str::title(str_replace('_', ' ', $key)) }}</b> : {{ $value }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection


@section('javascript')

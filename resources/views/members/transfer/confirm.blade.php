@extends('members.layouts.member')

@section('title', 'Confirm Transfer')

@section('member')
<div class="container my-5">
    <div class="card border-0 shadow-sm" style="border-top: 5px solid #007bff!important;">
        <div class="card-body p-5">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <img src="{{ asset('img/bank-logo.png') }}" alt="Bank Logo" height="50" class="mb-4">
                    <h3 class="mb-4">Transfer Receipt</h3>
                    <p class="mb-1 font-weight-bold">Transaction ID: {{ $transfer->reference }}</p>
                    <p class="mb-1">{{ $transfer->created_at->format('M d, Y') }}</p>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <h5 class="text-muted mb-2">From Account</h5>
                    <p class="h4 font-weight-bold mb-1">{{ $fromAccount->account_number }}</p>
                    <p class="text-muted mb-4">{{ $fromAccount->user->full_name }}</p>

                    <h5 class="text-muted mb-2">To Account</h5>
                    <p class="h4 font-weight-bold mb-1">{{ $toAccount->account_number }}</p>
                    <p class="text-muted mb-4">{{ $toAccount->user->full_name }}</p>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h5 class="text-muted mb-2">Transfer Amount</h5>
                    <p class="h3 font-weight-bold text-primary mb-0">{{ $currency->symbol }}{{ number_format($transfer->amount, 2) }}</p>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <h5 class="text-muted mb-2">Transfer Fee</h5>
                    <p class="h3 font-weight-bold text-primary mb-0">{{ $currency->symbol }}{{ number_format($transfer->fee, 2) }}</p>
                </div>
            </div>

            <hr class="my-4">

            <div>
                <h5 class="text-muted mb-2">Narration</h5>
                <p class="lead mb-0">{{ $transfer->narration }}</p>
            </div>

            <div class="text-center mt-5">
                <button class="btn btn-primary btn-lg" onclick="window.print()">
                    <i class="bi bi-printer-fill me-2"></i>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BankTransaction;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Support\Facades\Response;

class AdminViewAllTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $currency = Currency::active()->first();
        $query = BankTransaction::with(['user', 'account', 'bank'])
            ->when($request->search, function ($query, $search) {
                $query->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->transaction_type, function ($query, $type) {
                $query->where('transaction_type', $type);
            })
            ->when($request->date_from, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->date_to, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            });

        $transactions = $query->latest()->paginate(15);

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'totalAmount' => $query->sum('amount'),
            'transactionCount' => $query->count(),
            'currency' => $currency
        ]);
    }

    public function show(BankTransaction $transaction)
    {
        $transaction->load(['user', 'account', 'bank']);
        $currency = Currency::active()->first();

        return view('admin.transactions.show', [
            'transaction' => $transaction,
            'currency' => $currency

        ]);
    }

    public function updateStatus(Request $request, BankTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled'
        ]);

        $transaction->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Transaction status updated successfully');
    }

    public function exportCsv(Request $request)
    {
        $filename = 'transactions-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $transactions = BankTransaction::with(['user', 'account', 'bank'])->get();

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Reference',
                'Date',
                'Customer',
                'Account Number',
                'Type',
                'Amount',
                'Status'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->reference_number,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->user->full_name,
                    $transaction->account->account_number,
                    $transaction->transaction_type,
                    number_format($transaction->amount, 2),
                    $transaction->status
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}

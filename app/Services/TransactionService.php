<?php
namespace App\Services;

use App\Models\BankTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function updateTransactionStatus(BankTransaction $transaction, string $status)
    {
        try {
            DB::beginTransaction();

            $transaction->update(['status' => $status]);

            // Add any additional logic based on status change
            switch ($status) {
                case 'completed':
                    // Handle completion logic
                    $this->handleCompletedTransaction($transaction);
                    break;
                case 'failed':
                    // Handle failure logic
                    $this->handleFailedTransaction($transaction);
                    break;
                case 'cancelled':
                    // Handle cancellation logic
                    $this->handleCancelledTransaction($transaction);
                    break;
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction status update failed: ' . $e->getMessage());
            return false;
        }
    }

    private function handleCompletedTransaction(BankTransaction $transaction)
    {
        // Add completion logic here
        // e.g., update account balances, send notifications, etc.
    }

    private function handleFailedTransaction(BankTransaction $transaction)
    {
        // Add failure handling logic here
    }

    private function handleCancelledTransaction(BankTransaction $transaction)
    {
        // Add cancellation logic here
    }

    public function getTransactionStats()
    {
        return [
            'total_count' => BankTransaction::count(),
            'total_amount' => BankTransaction::sum('amount'),
            'pending_count' => BankTransaction::where('status', 'pending')->count(),
            'completed_count' => BankTransaction::where('status', 'completed')->count(),
            'failed_count' => BankTransaction::where('status', 'failed')->count(),
            'today_count' => BankTransaction::whereDate('created_at', today())->count(),
            'today_amount' => BankTransaction::whereDate('created_at', today())->sum('amount'),
        ];
    }
}

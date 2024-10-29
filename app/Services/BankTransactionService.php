<?php

namespace App\Services;

use App\Models\Account;
use App\Models\BankTransaction;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\UserAccountNotificationService;

// HANDLE THE MANUAL CREDIT/DEBIT DONE BY ADMIN ON USER ACCOUNT FROM ADMIN AREA
class BankTransactionService
{
    private $notificationService;

    public function __construct(UserAccountNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }


    public function processTransaction(Account $account, array $data)
    {
        DB::beginTransaction();

        try {
            $this->validateTransaction($account, $data);

            $oldBalance = $account->account_balance;
            $transaction = $this->createTransaction($account, $data);
            $this->updateAccountBalance($account, $data);

            // Log the activity
            activity()
                ->causedBy(request()->user())
                ->performedOn($account)
                ->withProperties([
                    'transaction_type' => $data['transaction_type'],
                    'amount' => $data['amount'],
                    'old_balance' => $oldBalance,
                    'new_balance' => $account->account_balance,
                    'transaction_id' => $transaction->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log(ucfirst($data['transaction_type']) . ' transaction processed');

            DB::commit();

            $this->sendTransactionNotification($account->user, $transaction);

            return [
                'success' => true,
                'message' => ucfirst($data['transaction_type']) . ' processed successfully',
                'transaction' => $transaction
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());

            activity()
                ->causedBy(request()->user())
                ->performedOn($account)
                ->withProperties([
                    'error' => $e->getMessage(),
                    'transaction_type' => $data['transaction_type'],
                    'amount' => $data['amount'],
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ])
                ->log('Transaction failed');

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function validateTransaction(Account $account, array $data)
    {
        if ($data['transaction_type'] === 'debit' && $account->account_balance < $data['amount']) {
            throw new \Exception('Insufficient funds. Available balance: ' .
                $account->currency->symbol . number_format($account->account_balance, 2));
        }
    }

    private function createTransaction(Account $account, array $data)
    {
        return BankTransaction::create([
            'reference_number' => $data['reference_number'],
            'bank_id' => $data['bank_id'] ?? null,
            'user_id' => $account->user_id,
            'account_id' => $account->id,
            'amount' => $data['amount'],
            'transaction_type' => $data['transaction_type'],
            'status' => 'completed',
            'description' => $data['description'],
            'transfer_id' => $data['transfer_id'] ?? null,  // Handle the transfer_id
            'submitted_requirements' => json_encode([
                'admin_initiated' => true,
                'transaction_date' => now(),
                'previous_balance' => $account->account_balance,
                'new_balance' => $this->calculateNewBalance($account, $data),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
        ]);
    }

    private function updateAccountBalance(Account $account, array $data)
    {
        $account->account_balance = $this->calculateNewBalance($account, $data);
        $account->save();
    }

    private function calculateNewBalance(Account $account, array $data)
    {
        return $data['transaction_type'] === 'credit'
            ? $account->account_balance + $data['amount']
            : $account->account_balance - $data['amount'];
    }

    private function generateTransactionReference()
    {
        do {
            $reference = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (BankTransaction::where('reference_number', $reference)->exists());

        return $reference;
    }

    private function sendTransactionNotification(User $user, BankTransaction $transaction)
    {
        $account = $transaction->account;
        $currencySymbol = $account->currency->symbol;

        $subject = sprintf(
            "%s Alert: %s %s%.2f - %s",
            config('app.name'),
            $transaction->transaction_type === 'credit' ? 'Credit:' : 'Debit:',
            $currencySymbol,
            $transaction->amount,
            $account->account_number
        );

        $emailData = [
            'user' => $user,
            'transaction' => $transaction,
            'account' => $account,
            'currency_symbol' => $currencySymbol,
            'date_time' => $transaction->created_at->format('d M, Y h:i A'),
            'available_balance' => number_format($account->account_balance, 2)
        ];

        $this->notificationService->sendTransactionAlert($user, $subject, $emailData);
    }
}

<?php

namespace App\Services;

use App\Http\Requests\CreateBankAccountRequest;
use App\Models\User;
use App\Models\Account;
use App\Models\BankTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\UserAccountNotificationService;

// CREATE NEW BANK ACCOUNT FOR USER FROM THE ADMIN AREA
class AccountCreationService
{
    private $notificationService;
    private const MAX_ACCOUNTS_PER_USER = 3;

    public function __construct(UserAccountNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function createAccount(User $user, array $validatedData, Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validateAccountCreation($user, $validatedData['account_type_id']);

            $account = $this->createAccountModel($user, $validatedData);
            $transaction = $this->createInitialTransaction($account, $validatedData['initial_balance']);
            $this->logActivity($account, $validatedData, $request);

            DB::commit();

            $this->sendNotifications($user, $account, $transaction);

            return [
                'success' => true,
                'account' => $account,
                'message' => 'Account created and funded successfully'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Account creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to create and fund account: ' . $e->getMessage()
            ];
        }
    }

    private function validateAccountCreation(User $user, $accountTypeId)
    {
        // Check if user already has 3 accounts
        if ($user->accounts()->count() >= self::MAX_ACCOUNTS_PER_USER) {
            throw new \Exception('User has reached the maximum number of accounts allowed (' . self::MAX_ACCOUNTS_PER_USER . ').');
        }

        // Check if user already has an account of this type
        if ($user->accounts()->where('account_type_id', $accountTypeId)->exists()) {
            throw new \Exception('User already has an account of this type.');
        }
    }

    private function createAccountModel(User $user, array $data)
    {
        $account = new Account($data);
        $account->user_id = $user->id;
        $account->account_number = $this->generateAccountNumber();
        $account->account_balance = $data['initial_balance'];
        $account->save();

        return $account;
    }

    private function createInitialTransaction(Account $account, $amount)
    {
        return BankTransaction::create([
            'reference_number' => $this->generateUniqueReferenceNumber(),
            'bank_id' => null, // Internal transaction, no external bank involved
            'user_id' => $account->user_id,
            'account_id' => $account->id,
            'amount' => $amount,
            'transaction_type' => 'credit',
            'status' => 'completed',
            'description' => 'Initial account funding',
            'submitted_requirements' => json_encode(['initial_funding' => true]),
        ]);
    }

    private function generateUniqueReferenceNumber()
    {
        do {
            $reference = 'TRX-' . Str::upper(Str::random(10));
        } while (BankTransaction::where('reference_number', $reference)->exists());

        return $reference;
    }

    private function logActivity(Account $account, array $data, Request $request)
    {
        activity()
            ->causedBy($request->user())
            ->performedOn($account)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old' => null,
                'new' => $data
            ])
            ->log('Bank Account created and funded');
            
    }

    private function sendNotifications(User $user, Account $account, $transaction)
    {
        $this->notificationService->sendAccountCreationNotification($user, $account);
        $this->notificationService->sendTransactionNotification($user, $transaction);
    }

    private function generateAccountNumber()
    {
        // Implement your account number generation logic here
        return str_pad(mt_rand(1, 9999999), 10, '0', STR_PAD_LEFT);
    }
}

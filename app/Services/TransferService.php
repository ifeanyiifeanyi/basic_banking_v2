<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Support\Str;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransferService
{

    public function validateInternalAccount(string $accountNumber, int $fromAccountId): array
    {
        $account = Account::where('account_number', $accountNumber)
            ->where('is_suspended', false)
            ->with('user:id,first_name,last_name,email')
            ->first();

        if (!$account) {
            throw ValidationException::withMessages([
                'account_number' => ['Account not found or inactive'],
            ]);
        }

        if ($account->id === $fromAccountId) {
            throw ValidationException::withMessages([
                'account_number' => ['Cannot transfer to the same account'],
            ]);
        }

        return [
            'account_name' => $account->user->full_name,
            'account_type' => $account->accountType->account_type,
            'account_status' => $account->is_suspended == false ? 'Active' : 'Suspended'
        ];
    }


    public function validateTransferAmount(Account $fromAccount, float $amount): void
    {
        if ($fromAccount->account_balance < $amount) {
            throw ValidationException::withMessages([
                'amount' => ['Insufficient balance. Available: ' . number_format($fromAccount->account_balance, 2)],
            ]);
        }
    }

    public function processExternalTransfer(array $data): Transfer
    {
        return DB::transaction(function () use ($data) {
            // Get account and bank
            $fromAccount = Account::findOrFail($data['from_account_id']);
            $bank = Bank::findOrFail($data['bank_id']);

            // Validate balance
            $this->validateTransferAmount($fromAccount, $data['amount']);

            // Create transfer record with meta data
            $transfer = Transfer::create([
                'from_account_id' => $fromAccount->id,
                'to_account_number' => $data['to_account_number'],
                'bank_id' => $bank->id,
                'amount' => $data['amount'],
                'transfer_type' => 'external',
                'narration' => $data['narration'] ?? null,
                'status' => 'pending',
                'meta_data' => $this->extractBankRequirements($data, $bank)
            ]);

            // Deduct amount from account
            $fromAccount->decrement('account_balance', $data['amount']);

            // Here you would typically integrate with external bank API
            // For now, we'll just mark it as completed
            $transfer->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return $transfer->load('fromAccount', 'bank');
        });
    }


    public function processInternalTransfer(array $data): Transfer
    {
        return DB::transaction(function () use ($data) {
            // Get accounts
            $fromAccount = Account::findOrFail($data['from_account_id']);
            $toAccount = Account::where('account_number', $data['to_account_number'])->firstOrFail();

            // Validate balance
            $this->validateTransferAmount($fromAccount, $data['amount']);

            // Create transfer record
            $transfer = Transfer::create([
                'from_account_id' => $fromAccount->id,
                'to_account_number' => $toAccount->account_number,
                'amount' => $data['amount'],
                'transfer_type' => 'internal',
                'narration' => $data['narration'] ?? null,
                'status' => 'completed',
                'completed_at' => now()
            ]);

            // Update account balances
            $fromAccount->decrement('account_balance', $data['amount']);
            $toAccount->increment('account_balance', $data['amount']);

            return $transfer->load('fromAccount', 'bank');
        });
    }


    private function extractBankRequirements(array $data, Bank $bank): array
    {
        $requirements = [];
        foreach ($bank->requirements as $requirement) {
            if (isset($data[$requirement->field_name])) {
                $requirements[$requirement->field_name] = $data[$requirement->field_name];
            }
        }
        return $requirements;
    }

















    // public function process(array $data)
    // {
    //     return DB::transaction(function () use ($data) {
    //         $fromAccount = Account::findOrFail($data['from_account_id']);

    //         // Validate sufficient balance
    //         if ($fromAccount->account_balance < $data['amount']) {
    //             throw new \Exception('Insufficient funds');
    //         }

    //         if ($data['transfer_type'] === 'internal') {
    //             return $this->processInternalTransfer($fromAccount, $data);
    //         }

    //         return $this->processExternalTransfer($fromAccount, $data);
    //     });
    // }

    // private function processInternalTransfer(Account $fromAccount, array $data)
    // {
    //     $toAccount = Account::where('account_number', $data['to_account_number'])->firstOrFail();

    //     // Create debit transaction
    //     $debitTransaction = $this->createTransaction([
    //         'account_id' => $fromAccount->id,
    //         'amount' => $data['amount'],
    //         'transaction_type' => 'debit',
    //         'description' => $data['narration'] ?? 'Internal Transfer',
    //         'reference_number' => $this->generateReference(),
    //         'status' => 'completed'
    //     ]);

    //     // Create credit transaction
    //     $creditTransaction = $this->createTransaction([
    //         'account_id' => $toAccount->id,
    //         'amount' => $data['amount'],
    //         'transaction_type' => 'credit',
    //         'description' => $data['narration'] ?? 'Internal Transfer',
    //         'reference_number' => $debitTransaction->reference_number,
    //         'status' => 'completed'
    //     ]);

    //     // Update balances
    //     $fromAccount->decrement('account_balance', $data['amount']);
    //     $toAccount->increment('account_balance', $data['amount']);

    //     return $debitTransaction;
    // }

    // private function processExternalTransfer(Account $fromAccount, array $data)
    // {
    //     // Create pending external transfer transaction
    //     $transaction = $this->createTransaction([
    //         'account_id' => $fromAccount->id,
    //         'bank_id' => $data['bank_id'],
    //         'amount' => $data['amount'],
    //         'transaction_type' => 'debit',
    //         'description' => $data['narration'] ?? 'External Transfer',
    //         'reference_number' => $this->generateReference(),
    //         'status' => 'pending',
    //         'submitted_requirements' => array_diff_key($data, array_flip([
    //             'from_account_id',
    //             'amount',
    //             'narration',
    //             'transfer_type',
    //             'bank_id'
    //         ]))
    //     ]);

    //     // Reserve the amount
    //     $fromAccount->decrement('account_balance', $data['amount']);

    //     return $transaction;
    // }

    private function createTransaction(array $data)
    {
        return BankTransaction::create($data);
    }

    private function generateReference()
    {
        do {
            $reference = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (BankTransaction::where('reference_number', $reference)->exists());

        return $reference;
    }
}

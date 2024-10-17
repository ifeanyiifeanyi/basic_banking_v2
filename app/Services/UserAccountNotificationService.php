<?php

namespace App\Services;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\BankTransaction;
use App\Mail\AccountCreatedMail;
use App\Mail\TransactionAlertMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionNotificationMail;
use App\Notifications\TransactionNotification;
use App\Notifications\AccountCreatedNotification;
use App\Notifications\TransactionAlertNotification;

class UserAccountNotificationService
{
    public function sendAccountCreationNotification(User $user, Account $account)
    {
        // Send email
        Mail::to($user->email)->send(new AccountCreatedMail($user, $account));

        // Send in-app notification
        $user->notify(new AccountCreatedNotification($account));
    }

    public function sendTransactionNotification(User $user, BankTransaction $transaction)
    {
        // Send email
        Mail::to($user->email)->send(new TransactionNotificationMail($user, $transaction));

        // Send in-app notification
        $user->notify(new TransactionNotification($transaction));
    }

    public function sendTransactionAlert(User $user, string $subject, array $emailData)
    {
        // Send email alert
        Mail::to($user->email)->send(new TransactionAlertMail($subject, $emailData));

        // Send in-app notification
        $user->notify(new TransactionAlertNotification($emailData));
    }
}

<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\BankTransaction;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionAlertNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->data['transaction']->id,
            'type' => $this->data['transaction']->transaction_type,
            'amount' => $this->data['currency_symbol'] . number_format($this->data['transaction']->amount, 2),
            'account_number' => $this->data['account']->account_number,
            'date' => $this->data['date_time'],
            'balance' => $this->data['currency_symbol'] . $this->data['available_balance']
        ];
    }
}

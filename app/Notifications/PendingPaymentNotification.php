<?php

namespace App\Notifications;

use App\Events\PusherNotificationPrivateEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PendingPaymentNotification extends Notification
{
    use Queueable;

    protected $paymentId;

    protected $totalAmount;

    protected $itemsCount;

    /**
     * Create a new notification instance.
     */
    public function __construct($paymentId, $totalAmount, $itemsCount)
    {
        $this->paymentId = $paymentId;
        $this->totalAmount = $totalAmount;
        $this->itemsCount = $itemsCount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [DatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('You have a pending order waiting for payment.')
            ->action('Complete Payment', url('/payment/checkout/' . $this->paymentId))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $data = [
            'id' => $this->id,
            'title' => __('Pending Order - Complete Payment'),
            'message' => __('You have :count item(s) waiting for payment. Total: :amount AED', [
                'count' => $this->itemsCount,
                'amount' => number_format($this->totalAmount, 2),
            ]),
            'link' => url('/payment/checkout/' . $this->paymentId),
            'type' => 'pending_payment',
            'payment_id' => $this->paymentId,
            'amount' => $this->totalAmount,
        ];

        event(new PusherNotificationPrivateEvent($this->id, $data, $notifiable));

        return [
            'id' => $this->id,
            'for_admin' => 0,
            'notification' => $data,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->paymentId,
            'total_amount' => $this->totalAmount,
            'items_count' => $this->itemsCount,
        ];
    }
}

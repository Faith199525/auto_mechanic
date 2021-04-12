<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmationFailed extends Notification
{
    use Queueable;

    public $failureDetails;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($failureDetails)
    {
        $this->failureDetails = $failureDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $failureDetails
     * @return array
     */
    public function via($failureDetails)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $failureDetails
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($failureDetails)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $failureDetails
     * @return array
     */
    public function toArray($failureDetails)
    {
        return [
             'autoshop_id' => $this->failureDetails->autoshop_id,
             'subscription_id' => $this->failureDetails->subscription_id,
             'error' => $this->failureDetails->error,
        ];
    }
}

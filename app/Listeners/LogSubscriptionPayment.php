<?php

namespace App\Listeners;

use App\Events\SubscriptionPaid;
use App\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSubscriptionPayment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriptionPaid  $event
     * @return void
     */
    public function handle(SubscriptionPaid $event)
    {
        $paymentDetails = $event->paymentDetails['data'];
        
        Payment::create([
            'autoshop_id' => $paymentDetails['metadata']['autoshop_id'],
            'payment_details' => $paymentDetails,
            'status' => 'pending'
        ]);
    }
}



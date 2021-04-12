<?php

namespace App\Listeners;

use App\Events\SubscriptionPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Payment;
use App\AutoshopSubscription;
use App\Notifications\PaymentConfirmationFailed;
use App\User;

class ConfirmSubscriptionPayment
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
        $reference = $paymentDetails['reference'];
        $autoshop_id = $paymentDetails['metadata']['autoshop_id'];
        $subscription_id = $paymentDetails['metadata']['subscription_id'];

        $curl = curl_init();
  
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_a7feadec236cc42378306241a28d97c29643e96d",
                "Cache-Control: no-cache",
                ),
            ));

            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);

            if ($error) {
                //Send error Notification to Admin
                /*
                $failureDetails = [
                    'autoshop_id' => $autoshop_id,
                    'subscription_id' => $subscription_id,
                    'error' => $error
                ];
                $user that is admin
                $user->notify(new PaymentConfirmationFailed($failureDetails));
                
                */
            }

        $payment = Payment::where('autoshop_id', $autoshop_id);
           
            if (json_decode($response)->data->amount !== $paymentDetails['requested_amount']){
                $payment->update([
                     'status' => 'failed'
                 ]);

            //Send error Notification to Admin
    
            /*
            $failureDetails = [
                    'autoshop_id' => $autoshop_id,
                    'subscription_id' => $subscription_id,
                    'error' => "Required amount not paid"
            ];
                $user that is admin
                $user->notify(new PaymentConfirmationFailed($failureDetails));
            */
            }
            
        $autoshopsubscription = AutoshopSubscription::where('autoshop_id',$autoshop_id);
            //update Payment transaction status to confirmed and subscription status to active
            
                $payment->update([
                    'status' => 'confirmed'
                ]);

                $autoshopsubscription->update([
                    'status' => 'active'
                ]);           
                
    }
               
}


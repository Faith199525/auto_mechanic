<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Paystack;
use App\Subscription;
use App\AutoShop;
use App\Events\SubscriptionPaid;
use App\Traits\StoreAutoshopSubscription;
use Illuminate\Http\Request;
use App\AutoshopSubscription;

class PaymentController extends Controller
{
    use StoreAutoshopSubscription;
    
    /**
     * Display Payment Details
    */
    public function show(Request $request)
    {
        $subscription = Subscription::findOrfail($request->subscription);
        
        $autoshop = AutoShop::findOrfail($request->autoshop_id);
        
        //$autoshop = (object) ['auto_shop_email'=>'autoshop@gmail.com','id'=>$request->autoshop_id];
        $duration = $request->duration;

        $discountPercentage = 1-(getenv('BASIC_AMOUNT_DISCOUNT')/100);
        
        if($request->discount){
            $amount = (int) (round($discountPercentage*$subscription->amount,0) * $duration); //20% discount
        } else {
            $amount = $subscription->amount * $request->duration;
        }

        
        return view('pay',[
            'autoshop' => $autoshop,
            'subscription' => $subscription,
            'amount' => $amount,
            'duration' => $duration
            ]);
    }

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway()
    {
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        $subscriptionDetails = $paymentDetails['data']['metadata'];

        $request = new Request();
        $request->setMethod('POST');
        $request->request->add([
            'subscription_id' => $subscriptionDetails['subscription_id'],
            'autoshop_id' => $subscriptionDetails['autoshop_id'],
            'duration' => $subscriptionDetails['duration'],
            'status' => 'pending'
        ]);

        $this->subscribeAutoshop($request);

        event(new SubscriptionPaid($paymentDetails));
        
        return response()->json([
          "data" => AutoshopSubscription::where('autoshop_id',$subscriptionDetails['autoshop_id'])->get(),
          "message" => "Subscribed!"
        ]);
        
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }
}

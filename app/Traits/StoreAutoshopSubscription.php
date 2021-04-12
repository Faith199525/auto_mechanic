<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\AutoshopSubscription;

trait StoreAutoshopSubscription {

     /**
     * Bind the autoshop to a subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribeAutoshop(Request $request) 
    {

         $autoshopsubscription = AutoshopSubscription::updateOrCreate([
            'autoshop_id' => $request->autoshop_id,
            'subscription_id' => $request->subscription_id,
            'start_date' => today(),
            'end_date' => today()->addMonths($request->duration),
            'status' => $request->status
        ]);  //bind the autoshop to the subscription

        return response()->json([
          "data" => $autoshopsubscription,
          "message" => "Subscribed!"
        ]);

    }
}
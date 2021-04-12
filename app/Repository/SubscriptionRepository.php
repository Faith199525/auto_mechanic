<?php

namespace App\Repository;

use App\Subscription;
use Validator;

class SubscriptionRepository {

    public function storeSubscription($request)
    {
         $validator = Validator::make($request->all(), [
         'name' => 'required',
         'description' => 'required',
         'amount' => 'required|integer|min:1',  //In kobo
         'duration' => 'required|integer|min:1'
         ]);

         if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
         }

         $subscription = Subscription::create([
             'name' => $request->name,
             'description' => $request->description,
             'amount' => $request->amount,
             'duration' => $request->duration
         ]); 
         
         return response()->json([
             "data" => $subscription,
             "message" => "Subscription Plan Created!"
         ], 201);
    }

            /**
     * Update the subscription plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function updateSubscription($request,$subscription)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'description' => 'required',
        'amount' => 'required|integer',
        'duration' => 'required|integer'
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        $subscription->update([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount,
            'duration' => $request->duration
        ]);

        return response()->json([
          "data" => $subscription,
          "message" => "Subscription Plan Updated!"
      ], 200);
    }
}
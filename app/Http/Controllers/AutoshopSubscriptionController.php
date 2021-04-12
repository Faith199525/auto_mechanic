<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\History;
use App\AutoshopSubscription;
use App\AutoShop;
use Illuminate\Http\Request;
use Validator;
use App\Traits\StoreAutoshopSubscription;

class AutoshopSubscriptionController extends Controller
{
    use StoreAutoshopSubscription;

    public function initiateSubscription(Request $request)
    {
        
        $validator = Validator::make($request->all(), 
            [
                'autoshop_id' => 'required|unique:autoshop_subscriptions,autoshop_id,NULL,id,deleted_at,NULL',
                'duration' => 'required|integer|min:1'
            ],
            [
                'autoshop_id.unique' => 'This autoshop has an active plan already!'
            ]
        );

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        $duration = $request->duration;
        if($duration > 2){
            $discount = True;
        } else {
            $discount  = False;
        }

        return redirect()->action(
            'PaymentController@show', [
                'autoshop_id' => $request->autoshop_id,
                'discount' => $discount,
                'subscription' => $request->subscription,
                'duration' => $duration
                ]
        );

    }
     /**
     * Bind the autoshop to a subscription.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [
                'autoshop_id' => 'required|unique:autoshop_subscriptions,autoshop_id,NULL,id,deleted_at,NULL',
                'duration' => 'required|integer|min:1',
                'status' => 'required|string'
            ],
            [
                'autoshop_id.unique' => 'This autoshop has an active plan already!'
            ]
        );

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        return $this->subscribeAutoshop($request);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $autoshopsubscription = AutoshopSubscription::where('autoshop_id',$request->autoshop_id)->get();
        $autoshopsubscription->delete();

    }
}

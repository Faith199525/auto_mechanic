<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\Repository\SubscriptionRepository;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(SubscriptionRepository $sub,Request $request)
    {
        $this->sub = $sub;
        $this->request = $request;
    }


    /**
     * Return all subscriptions plan available.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Subscription::all(), 200);
    }

    /**
     * Store the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->sub->storeSubscription($this->request);

    }

    /**
     * Update the subscription plan in storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Subscription $subscription)
    {
        return $this->sub->updateSubscription($this->request,$subscription);

    }

    /**
     * Remove the subscription plan from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
  
        $subscription->delete();

        return response()->json([
          "message" => "Subscription Plan Deleted!"
      ], 200);

    }
}

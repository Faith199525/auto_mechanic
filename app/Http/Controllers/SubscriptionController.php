<?php

namespace App\Http\Controllers;

use App\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Return all subscriptions plan available.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Subscription::all(), 200);
    }

}

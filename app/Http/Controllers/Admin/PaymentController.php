<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Payment::all(), 200);
    }

    /**
     * Fetch the payment record of an autoshop.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaymentByAutoshop($id)
    {
        $paymentRecord = Payment::where('autoshop_id',$id)->get();
        return response()->json(['data'=>$paymentRecord], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return response()->json(['data'=>$payment], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request->status: 'pending'.'confirmed','failed'
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => $request->status
        ]);

        return response()->json([
            'data' => $payment,
            'message' => 'Payment status updated!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        // $payment->delete();
    }
}

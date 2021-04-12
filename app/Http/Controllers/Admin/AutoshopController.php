<?php

namespace App\Http\Controllers\Admin;

use App\AutoShop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\AutoshopRepository;

class AutoshopController extends Controller
{

    public function __construct (AutoshopRepository $autoshop)
    {
        $this->autoshop = $autoshop;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(AutoShop::all(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AutoShop  $autoShop
     * @return \Illuminate\Http\Response
     */
    public function show(AutoShop $autoShop)
    {
        return response()->json(["data" => $autoShop], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AutoShop  $autoShop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AutoShop $autoShop)
    {
        $this->autoshop->updateAutoshop($request,$autoShop);

    	return response()->json([
            'data' => $autoShop,
            'message'=>'Autoshop Updated!'
        ], 200);
    }

    public function enable(AutoShop $autoShop)
    {
        $this->autoshop->enableAutoshop($autoShop);

        return response()->json([
            'data' => $autoShop,
            'message'=>'Autoshop Enabled!'
        ], 200);
    }

    public function disable(AutoShop $autoShop)
    {

       $this->autoshop->disableAutoshop($autoShop);
        return response()->json([
            'data' => $autoShop,
            'message'=>'Autoshop Disabled!'
        ], 200);
    }

    public function subscriptionStatus($id)
    {
        $status = \App\AutoshopSubscription::where('autoshop_id',$id)->latest()->first();

        return response()->json([
            'data' => $status
        ], 200);   
    }

    public function paymentRecord($id)
    {
       $paymentRecord = \App\Payment::where('autoshop_id',$id)->get();

        return response()->json([
            'data' => $paymentRecord
        ], 200);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AutoShop  $autoShop
     * @return \Illuminate\Http\Response
     */
    public function destroy(AutoShop $autoShop)
    {
        $autoShop->delete();
        return response()->json(["mesage" => 'Autoshop Deleted!'], 200);
    }
}

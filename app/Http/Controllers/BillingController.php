<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing;
use App\WorkOrder;
use App\VehicleOwner;
use Validator;

class BillingController extends Controller
{
    public function billings(Request $request, $workOrder, $vehicleowner)
   {
    	$rules =[
            'amount'=> 'required',
            'status'=> 'required'
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
        return response()->json($validator->errors(),400);
    }

    	$billing= new Billing();
    	$billing->amount=$request->input('amount');
    	$billing->status=$request->input('status');

    	$workorder=WorkOrder::find($workOrder);
    	$vehicleOwner=VehicleOwner::find($vehicleowner);

    	$billing->workOrder()->associate($workorder);
    	$billing->vehicleOwner()->associate($vehicleOwner);
    	$billing->save();

    	return response()->json(['message'=>'Billing successful'], 200);

    }

    public function viewBillingsById (Request $request, $Id)
    {
    	$billing= Billing::find($Id);

    	return response()->json(['data'=>$billing], 200);
    }

    public function viewBillings(Request $request)
    {
    	$billing= Billing::all();

    	return response()->json(['data'=>$billing], 200);
    }

    public function updateBillingsById (Request $request, $workOrder, $vehicleOwner)
    {
    	$billing= Billing::find($Id);
    	$billing->amount=$request->input('amount');
    	$billing->status=$request->input('status');
    	$billing->work_order_id=$workOrder;
    	$billing->vehicle_owner_id=$vehicleOwner;
    	$billing->save();

    	return response()->json(['message'=>'Billing successfully updated'], 200);
    }

    public function deleteBillingsById(Request $request, $Id)
    {
    	$billing= Billing::find($Id);
    	$billing->delete();

    	return response()->json(['message'=>'Billing successfully deleted'], 200);
    }
}

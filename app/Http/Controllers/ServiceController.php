<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Vehicle;
use App\Inventory;
use Validator;

class ServiceController extends Controller
{
    public function services(Request $request, $vehicleId, $inventoryId)
    {
    	$rules =[
            'diagnosis'=> 'required',
            'resolution'=> 'required'
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
        return response()->json($validator->errors(),400);
    }
    	$service = new Service;
    	$service->diagnosis=$request->diagnosis;
    	$service->resolution=$request->resolution;

    	$vehicle= Vehicle::find($vehicleId);
        $inventory= Inventory::find($inventoryId); 

        $service->vehicle()->associate($vehicle);
        $service->inventory()->associate($inventory); 
         
    	$service->save();

    	return response()->json([
    		'message'=>'Vehicle Servicing successfully captured'
    	], 200);
    }

    public function viewServicesId($id)
    {
        $service= Service::find($Id);

        return response()->json([
            'data'=>$service
        ], 200);
    }

    public function viewServices()
    {
        $service= Service::all();

        return response()->json([
            'data'=>$service
        ], 200);
    }

    public function updateServices(Request $request, $id, $inventoryId, $vehicleId)
    {
        $service= Service::find($Id);
        $service->diagnosis=$request->diagnosis;
        $service->resolution=$request->resolution;
        $service->inventory_id=$inventoryId;
        $service->vehicle_id= $vehicleId;

        $service->save();

        return response()->json([
            'message'=>'Vehicle Service successfully updated'
        ], 200);
    }
    public function deleteServices($id)
    {
        $service= Service::find($Id);
        $service->delete();

        return response()->json([
            'message'=>'Vehicle Service successfully deleted'
        ], 200);
    }
}

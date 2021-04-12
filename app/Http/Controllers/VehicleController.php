<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;
use App\VehicleOwner;
use Validator;

class VehicleController extends Controller
{
   public function register(Request $request, $vehicleOwner)
   {
    	$rules =[
            'maker'=> 'required',
            'production_year'=> 'required',
            'model'=> 'required',
    		'vin'=> 'required',
    		'license_plate_number'=> 'required'
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
        return response()->json($validator->errors(),400);
    }

    	$vehicle= new Vehicle();
    	$vehicle->maker=$request->input('maker');
    	$vehicle->production_year=$request->input('production_year');
    	$vehicle->vin=$request->input('vin');
    	$vehicle->model=$request->input('model');
    	$vehicle->license_plate_number=$request->input('license_plate_number');
    	$vehicle->engine_number=$request->input('engine_number');

    	$vehicleowner=VehicleOwner::find($vehicleOwner);
    	$vehicleowner->vehicles()->save($vehicle);

    	return response()->json(['message'=>'Vehicle registeration successful'], 200);

    }

    public function viewVehiclesById (Request $request, $Id)
    {
    	$vehicle= Vehicle::find($Id);

    	if(!$vehicle){
    	return response()->json(['message'=> 'Vehicle does not exist'], 400);
    	}

    	return response()->json(['data'=>$vehicle], 200);
    }

    public function viewVehicles(Request $request)
    {
    	$vehicle= Vehicle::all();

    	return response()->json(['data'=>$vehicle], 200);
    }

    public function updateVehiclesById (Request $request, $Id, $vehicleOwner)
    {
    	$vehicle= Vehicle::find($Id);

    	if(!$vehicle){
    		return response()->json(['message'=> 'Vehicle does not exist'], 400);
    	}

    	$vehicle->maker=$request->input('maker');
    	$vehicle->production_year=$request->input('production_year');
    	$vehicle->vin=$request->input('vin');
    	$vehicle->model=$request->input('model');
    	$vehicle->engine_number=$request->input('engine_number');
    	$vehicle->license_plate_number=$request->input('license_plate_number');
    	$vehicle->vehicle_owner_id=$vehicleOwner;
    	$vehicle->save();

    	return response()->json(['message'=>'Vehicle successfully updated'], 200);
    }

    public function deleteVehiclesById(Request $request, $Id)
    {
    	$vehicle= Vehicle::find($Id);

    	if(!$vehicle){
    		return response()->json(['message'=> 'Vehicle does not exist'], 400);
    	}
    	$vehicle->delete();

    	return response()->json(['message'=>'Vehicle successfully deleted'], 200);
    }
}

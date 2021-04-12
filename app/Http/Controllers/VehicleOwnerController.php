<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleOwner;
use App\AutoShop;
use Validator;

class VehicleOwnerController extends Controller
{
    public function register(Request $request, $autoShop)
    {
    	$rules =[
            'firstname'=> 'required',
            'lastname'=> 'required',
    		'email'=> 'nullable|email'
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
        return response()->json($validator->errors(),400);
    }

    	$owner= new VehicleOwner();
    	$owner->firstname=$request->input('firstname');
    	$owner->email=$request->input('email');
    	$owner->lastname=$request->input('lastname');
    	$owner->address=$request->input('address');
    	$owner->phone_number=$request->input('phone_number');

    	$autoshop=AutoShop::find($autoShop);
    	$autoshop->vehicleOwners()->save($owner);

    	return response()->json(['message'=>'Vehicle Owner registeration successful'], 200);

    }

    public function viewVehicleOwnersById (Request $request, $Id)
    {
    	$owner= VehicleOwner::find($Id);
    	if(!$owner){
    	return response()->json(['message'=> 'Vehicle Owner does not exist'], 400);
    	}

    	return response()->json(['data'=>$owner], 200);
    }

    public function viewVehicleOwners(Request $request)
    {
    	$owner= VehicleOwner::all();

    	return response()->json(['data'=>$owner], 200);
    }

    public function updateVehicleOwnersById (Request $request, $Id, $autoshop)
    {
    	$owner= VehicleOwner::find($Id);

    	$owner->firstname=$request->input('firstname');
    	$owner->auto_shop_id=$autoshop;
    	$owner->email=$request->input('email');
    	$owner->lastname=$request->input('lastname');
    	$owner->address=$request->input('address');
    	$owner->phone_number=$request->input('phone_number');
    	$owner->save();

    	return response()->json(['message'=>'Vehicle Owner successfully updated'], 200);
    }

    public function deleteVehicleOwnersById(Request $request, $Id)
    {
    	$owner= VehicleOwner::find($Id);
    	if(!$owner){
    	return response()->json(['message'=> 'Vehicle Owner does not exist'], 400);
    	}
    	
    	$owner->delete();

    	return response()->json(['message'=>'Vehicle Owner successfully deleted'], 200);

    }
}

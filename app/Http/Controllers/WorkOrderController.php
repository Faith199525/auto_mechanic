<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Service;
use App\Inventory;
use App\VehicleOwner;
use App\Vehicle;
use App\WorkOrder;
use App\ImageFile;
use Validator;
use Illuminate\Support\Facades\Mail;

class WorkOrderController extends Controller
{
    /*
     * register a new vehicle work order in the storage
     */
    public function register(Request $request, $serviceId)
    {
    	$rules =[
            'release_date'=> 'required',
            'image' => 'image|nullable|max:3000',
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
        return response()->json(
        	$validator->errors(),400);
    }
    	/*
    	calculate time spent on vehicle inspection
    	*/
    	$start= Carbon::now();
    	$stop=$request->input('release_date');
    	$worktime=$start->diff($stop)->format('%dday %hhour %mminute');

    	$work= new WorkOrder();

    	$work->admission_date=$start;
    	$work->release_date=$stop;	
    	$work->service_time=$worktime;

    	$service=Service::find($serviceId);

        $work->service()->associate($service);
        $work->save();

        //if image exist, add image using the established polymorphic relationship
       if($request->hasfile('image')){

            $file= $request->file('image'); 
            $name=$file->getClientOriginalName();
            $path = \Storage::putFile('image', $file);

            $image= new ImageFile();
            $image->name=$name;
            $image->path=$path;
            $work->images()->save($image);
       }
        /*
        Email notification of the breakdown is sent to the vehicle owner's email address
        */
        $vehicle=Vehicle::find($service->vehicle_id);
        $owner= VehicleOwner::find($vehicle->vehicle_owner_id);
        $owner_email=$owner->email;
        if(is_null($owner_email) ){

        return response()->json([
            'message'=>'Vehicle service successfully done'
        ], 201);

        }

        $data = [
           //'diagnosis' => $request->input('diagnosis'),
           //'resolution' => $request->input('resolution'),
           'vehicle_Maker'=> $vehicle->maker,
           'vehicle_Model'=> $vehicle->model,
           'vehicle_Licence_Plate_No'=> $vehicle->license_plate_number,
           'admission_date'=> $work->admission_date,
           'release_date'=> $work->release_date,
           'service_time'=> $work->service_time
];

        Mail::send('email.notification',$data, function($message) use ($owner_email) {
        $message
        ->to($owner_email)
        ->subject('Your vehicle work order invoice');
        });

    	
    	return response()->json([
    		'message'=>'Vehicle service successfully done'
    	], 201);

    }

    /*
     * view a vehicle work order 
     */
    public function viewVehicleWorkOrdersById (Request $request, $Id)
    {
    	$workOrder= WorkOrder::find($Id);

    	return response()->json([
    		'data'=>$workOrder
    	], 200);
    }

    public function viewVehicleWorkOrders(Request $request)
    {
    	$workOrder= WorkOrder::all();

    	return response()->json([
    		'data'=>$workOrder
    	], 200);
    }

    public function updateVehicleWorkOrdersById (Request $request, $Id, $serviceId)
    {
    	$workOrder= WorkOrder::find($Id);
    
    	$workOrder->service_id=$serviceId;
    	$workOrder->admission_date=Carbon::now();
    	$workOrder->release_date=$request->input('release_date');

        $start= Carbon::now();
        $stop=$request->input('release_date');
        $worktime=$start->diff($stop)->format('%dday %hhour %mminute');
        $work->service_time=$worktime;
    	$workOrder->save();

    	return response()->json([
    		'message'=>'Vehicle workOrder successfully updated'
    	], 200);
    }

    public function deleteVehicleWorkOrdersById(Request $request, $Id)
    {
    	$workOrder= WorkOrder::find($Id);
    	$workOrder->delete();

    	return response()->json([
    		'message'=>'Vehicle workOrder successfully deleted'
    	], 200);

    }
}

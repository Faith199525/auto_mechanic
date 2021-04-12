<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Estimate;
use App\Service;
use App\Vehicle;
use App\VehicleOwner;
use App\Check;
use Ramsey\Uuid\Uuid;
use Validator;
use Illuminate\Support\Facades\Mail;

class EstimateController extends Controller
{

    public function estimates(Request $request, $id)
    {

      $token = (string) Uuid::uuid4();
      $estimate = new Estimate([
            'items' => $request->items,
            'token' => $token
        ]);  
       $service = Service::find($id);
       $service->estimate()->save($estimate);

       $vehicle=Vehicle::find($service->vehicle_id);
       $owner=VehicleOwner::find($vehicle->vehicle_owner_id);
       $owner_email=$owner->email;

            $data = [
               'token' =>$token,
               'vehicle_Maker'=> $vehicle->maker,
               'vehicle_Model'=> $vehicle->model,
               'vehicle_Licence_Plate_No'=> $vehicle->license_plate_number
];
        Mail::send('email.estimate',$data, function($message) use ($owner_email) {
        $message
        ->to($owner_email)
        ->subject('Click on link to view and edit your vehicle service estimate');
        });

    		return response()->json([
            'message'=>'Service estimate successfully done'
        ], 200);
    }

    /*public function viewEstimates($token)
    {
      $token= Estimate::where('token',$token)->first();
      $items=$token->items;

      return view('email.estimateDetails')->with('estimate',$items) ;//front end will display this details on a the view page using javascript
    }*/

    public function estimatesApproved(Request $request, $token){

      Estimate::where('token',$token)->update([
                    'items'=>$request->items]);
    
      return response()->json([
            'message'=>'Service estimate successfully updated'
        ], 200);  
    }

   public function viewEstimates(){

      $estimate=Estimate::all();

      return response()->json([
        'data'=>$estimate
      ], 200);
    }

    public function deleteEstimates($id)
    {
      $estimate=Estimate::find($id);
      $estimate->delete();

      return response()->json([
            'message'=>'Service estimate successfully deleted'
        ], 200);  
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutoShop;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;

class AutoShopController extends Controller
{
    public function register(Request $request) 
    {
    	$rules =[
    		'auto_shop_name'=> 'required',
            'auto_shop_address'=> 'required',
            'firstname'=> 'required',
            'lastname'=> 'required',
    		'email'=> 'required|email|unique:users',
    		'password'=> 'required|min:5|confirmed'
    	];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    	return response()->json(
            $validator->errors(),
            400);
    }

    	$shop = new AutoShop();
    	$shop->auto_shop_name=$request->input('auto_shop_name');
    	$shop->auto_shop_address=$request->input('auto_shop_address');
    	$shop->auto_shop_email=$request->input('auto_shop_email');
    	$shop->staff_size=$request->input('staff_size');
    	$shop->save();

    	$user = new User();
    	$user->firstname=$request->input('firstname');
    	$user->lastname=$request->input('lastname');
    	$user->email=$request->input('email');
    	$user->password=Hash::make($request->input('password'));
    	$user->save();

		$user->autoshop()->attach($shop->id);

		//Assign user as shop owner
		$roleId = \App\Role::where('name','shopOwner')->first()->id;
        $user->roles()->attach($roleId, ['autoshop_id' => $shop->id]);
    
    	return response()->json([
            'message' => 'AutoMechanic Shop successfully registered'
        ], 200);
    
    }

    public function viewAutoShopsById (Request $request, $Id)
    {
    	$autoshop= AutoShop::find($Id);
    	if(!$autoshop){
    	return response()->json([
            'message'=> 'AutoShop does not exist'
        ], 400);
    	}

    	return response()->json([
            'data'=>$autoshop
        ], 200);
    }

    public function viewAutoShops(Request $request)
    {
    	$autoshop= AutoShop::all();

    	return response()->json([
            'data'=>$autoshop
        ], 200);
    }

    public function updateAutoShopsById (Request $request, $Id)
    {
    	$autoshop= AutoShop::find($Id);

    	if(!$autoshop){
    	return response()->json([
            'message'=> 'AutoShop does not exist'
        ], 400);
    	}

    	$autoshop->auto_shop_name=$request->input('auto_shop_name');
    	$autoshop->auto_shop_address=$request->input('auto_shop_address');
    	$autoshop->auto_shop_email=$request->input('auto_shop_email');
    	$autoshop->staff_size=$request->input('staff_size');
    	$autoshop->save();

    	return response()->json([
            'message'=>'Vehicle successfully updated'
        ], 200);
    }

    public function deleteAutoShopsById(Request $request, $Id)
    {
    	$autoshop= AutoShop::find($Id);
    	if(!$autoshop){
    	return response()->json([
            'message'=> 'AutoShop does not exist'
        ], 400);
    	}

    	$autoshop->delete();

    	return response()->json([
            'message'=>'Vehicle successfully deleted'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Repository\UserRepository;

class UserController extends Controller
{
    /**
     * Create New User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      return (new UserRepository)->store($request);
    }
 
   public function viewUsersById (Request $request, $Id)
    {
    	$user= User::find($Id);

    	if(!$user){
    	return response()->json(['message'=> 'User does not exist'], 400);
    	}

    	return response()->json(['data'=>$user], 200);
    }

    public function viewUsers(Request $request)
    {
    	$user= User::all();

    	return response()->json(['data'=>$user], 200);
    }

    public function updateUsersById (Request $request, $Id)
    {
    	$user= User::find($Id);

    	if(!$user){
    	return response()->json(['message'=> 'User does not exist'], 400);
    	}

    	$user->firstname=$request->input('firstname');
    	$user->lastname=$request->input('lastname');
    	$user->email=$request->input('email');
    	$user->password=Hash::make($request->input('password'));
    	$user->save();

    	return response()->json(['message'=>'Vehicle successfully updated'], 200);
    }

    public function deleteUsersById(Request $request, $Id)
    {
    	$user= User::find($Id);
    	if(!$user){
    	return response()->json(['message'=> 'User does not exist'], 400);
    	}
    	$user->delete();

    	return response()->json(['message'=>'Vehicle successfully deleted'], 200);
    } 

}

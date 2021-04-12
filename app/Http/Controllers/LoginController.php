<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    	$rules =[
            'email'=> 'required|email',
            'password'=> 'required|min:5'
        ];

        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()) {
        return response()->json($validator->errors(),400);
    }

    	if(Auth::attempt($request->only('email','password'))){
    	return response()->json(Auth::user(), 200);
    	}

    	return response()->json(['error'=>'User does not exist, pls sign up.'], 401);
}

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message'=>'User successfully logged out'], 200);
    }
}
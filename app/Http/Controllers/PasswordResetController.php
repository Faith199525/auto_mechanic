<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PasswordReset;
use App\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
     public function forgot(Request $request) 
    {
       $rules =[
    		'email'=> 'required|email'];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    	return response()->json($validator->errors(),400);
    }
        $confirmationToken = Str::random(60);
        $email=$request->input('email');
		$user= new PasswordReset();

		$user->email= $request->input('email');
		$user->token= $confirmationToken;
		$user->created_at= Carbon::now();
		$user->token_expiration=Carbon::now()->addDays(1);
		$user->save();

        $data = array("confirmationToken"=>$confirmationToken);

        Mail::send('email.passwordReset', $data, function($message) use ($email) {
       	$message
       	->to($email)
       	->subject('Click to reset your password');
        });

		return response()->json(['message' => 'Success, email has been sent'], 200);
}
	public function getToken(Request $request, $confirmationToken){

		$token= PasswordReset::where('token',$confirmationToken)->first();
		if(!$token){
			return response()->json(['message' => 'Sorry,token does not exist'], 400);
		}

		$date=Carbon::now();
		$expiry=$token->token_expiration;
		if($date > $expiry){
			return response()->json(['message' => 'Sorry, Token has expired'], 400);
		}
		return response()->json(['message' => 'Success, token exists'], 200);//return view form here 
	}

	public function resetPassword(Request $request, $confirmationToken){

		$rules =[
    		'password'=> 'required|min:5'];

        $validator= Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    	return response()->json($validator->errors(),400);
    }

		$token= PasswordReset::where('token',$confirmationToken)->first();
		if(!$token){
			return response()->json(['message' => 'Sorry,token does not exist'], 400);
		}

		$email=$token->email;
	    $user= User::where('email',$email)->first();
		if(!$user){
			return response()->json(['message' => 'Sorry,user does not exist'], 400);
		}

		$user->password=Hash::make($request->input('password'));
		$user->save();

		$token->delete();//delete token so it won't be reused

		return response()->json(['message' => 'Success, password successfully changed'], 400);
	}

}
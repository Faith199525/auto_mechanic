<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;
use App\Invite;
use App\Role;
use App\Mail\InviteMail;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Repository\UserRepository;

class InviteController extends Controller
{
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Store Invited Users and Send Email with link to complete User Registration
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  String $request->role: "superAdmin" 
     * @return \Illuminate\Http\Response
     * */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        
        $email = $request->email;

        if(Invite::where('email',$email)->exists()){
            return response()->json([
                "error" => $validator->errors()->add('email', 'This email has already been invited!')
            ], 422);
        }

        do {

           $uuid = (string)Str::uuid();

        } while (Invite::where('uuid', $uuid)->first());

         //superAdmin -5
        $roleId = Role::where('name',Str::camel($request->role))->pluck('id')->first();

        $invitedUser = Invite::create([
            'uuid' => $uuid,
            'email' => $email,
            'role_id' => $roleId,
        ]);

        //Generate Signed URL to Prevent tampered URL
        $url = URL::signedRoute('invite', ['invite' => $uuid]);


        $invite = [
            'email' => $invitedUser->email,
            'url' => $url
        ];

        Mail::to($invitedUser->email)->send(new InviteMail($invite));

        return response()->json(['message'=>'Successful! Invite email sent to '.$invitedUser->email], 200);
    }

     /**
     * Data needed for the Invited User Registration Form Page Completion.
     * Returns the email of the invited user which is used in display and must be uneditable for the user.
     * @param App\Invite $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        return $this->userRepo->showInvitePage($invite);
    }

}

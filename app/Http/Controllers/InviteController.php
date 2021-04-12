<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;
use App\Invite;
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
     * @param  String $request->role: "shop owner","technician","accountant","clerk" 
     * @return \Illuminate\Http\Response
     * */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|string',
            'autoshop_id' => 'required'
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        
        $email = $request->email;

        switch (true) {
                //Invited but yet to register
                case Invite::where('email',$email)->where('autoshop_id',$request->autoshop_id)->exists():
                    
                    return response()->json([
                        "error" => $validator->errors()->add('email', 'This email has already been invited to this AutoShop!')
                    ], 422);
                    break;
                
               
                //Registered already
                case Invite::onlyTrashed()->where('email',$email)->where('autoshop_id',$request->autoshop_id)->exists():
    
                    return response()->json([
                        "error" => $validator->errors()->add('email', 'This email has already registered with this AutoShop!')
                    ], 422);
                    break;
                
                default:
                    break;
            }

        do {

           $uuid = (string)Str::uuid();

        } while (Invite::where('uuid', $uuid)->first());

        $role = $request->role;

         //Shop owner - 1, Technician - 2, Accountant - 3, Clerk - 4

        switch ($role) {
                case 'shop owner':
                    $roleId = 1;
                    break;

                case 'technician':
                    $roleId = 2;
                    break;

                case 'accountant':
                    $roleId = 3;
                    break;
                
                case 'clerk':
                    $roleId = 4;
                    break;
                
                default:
                    break;
            }

        $invitedUser = Invite::create([
            'uuid' => $uuid,
            'email' => $request->email,
            'role_id' => $roleId,
            'autoshop_id' => $request->autoshop_id
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

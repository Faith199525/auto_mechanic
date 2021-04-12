<?php

namespace App\Repository;

use Illuminate\Http\Request;
use App\Invite;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UserProfile;
use App\Role;
use App\Traits\Image;
use Illuminate\Support\Str;

class UserRepository {

  use Image;

     /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ]);
        
        
        //Check if the user was invited
        if(Invite::where('email', $request->email)->doesntExist()){
           return response()->json(["error" => 'Unauthorised Access! You must be Invited to Sign Up'],401);
        }

        //Fetch request input
        $invitedUser = Invite::where('uuid', $request->invite)->first();
        $roleId = $invitedUser->role_id;
        $autoshopId = $invitedUser->autoshop_id;

        //Check if the user already has an account before creating
         $user = User::firstOrCreate(
                ['email' => $request->email],
                ['password' => Hash::make($request->password)]
        ); 

        $user->roles()->attach($roleId, ['autoshop_id' => $autoshopId]);


        $userRole = $user->roles->where('id',$roleId)->first()->name;
        $userRole = implode(' ', preg_split('/(?=[A-Z])/', ucwords($userRole)));

        //Delete the Invite
        $invitedUser->delete();

        return response()->json([
          "data" => $user,
          "message" => "User Created and Assigned as".$userRole
      ], 201);
    }


    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ]);

        //Check if the user was invited
        if(Invite::where('email', $request->email)->doesntExist()){
           return response()->json(["error" => 'Unauthorised Access! You must be Invited to Sign Up'],401);
        }

        $invitedUser = Invite::where('uuid', $request->invite)->first();
        $roleId = $invitedUser->role_id;


        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->roles()->attach($roleId);

        //Delete the Invite
        $invitedUser->delete();
        
        return response()->json([
          "data" => $user,
          "message" => "User Created and Assigned as Admin"
        ], 201);

    }

    public function updateUserRole($request,$user)
    {
      $formerRoleId = $user->roles()->wherePivot('autoshop_id', $request->autoshop_id)->first()->id;
      $user->roles()->detach($formerRoleId);

      $roleId = Role::where('name',Str::camel($request->role))->first()->id;
      $user->roles()->attach($roleId, ['autoshop_id' => $request->autoshop_id]);
    }

    public function storeUserProfile($request,$user)
    {   

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'supervisor' => 'nullable|string',
            'phone' => 'required|digits:11',
            'image' => 'image|nullable|max:1999',
            'location' => 'required|array',
            'location.*' => 'string'
        ]);
      
        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        
        $userProfile = new UserProfile([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'supervisor' => $request->supervisor,
            'image' => $this->storeImage($request),
            'location' => $request->location
        ]);
        
        $user->userprofile()->save($userProfile);  //bind the profile to it's user
        
        return response()->json([
          "data" => $userProfile,
          "message" => "Profile Created"
      ], 201);
    }


        /**
     * Update the User's profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function updateUserProfile(User $user, UserProfile $userProfile,Request $request)
    {
      
         $validator = Validator::make($request->all(), [
            'firstname' => 'string',
            'lastname' => 'string',
            'supervisor' => 'nullable|string',
            'phone' => 'nullable|digits:11',
            'image' => 'image|nullable|max:1999',
            'location' => 'nullable|array',
            'location.*' => 'string'
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        $location = $userProfile->location;

        $newlocation = ([
          "country" => isset($request->location['country']) ? $request->location['country'] : $location['country'],
          "state" => isset($request->location['state']) ? $request->location['state'] : $location['state'],
          "city" => isset($request->location['city']) ? $request->location['city'] : $location['city']
        ]);
        
        $userProfile->update([
            'firstname' => $request->firstname ?: $userProfile->firstname,
            'lastname' => $request->lastname ?: $userProfile->lastname,
            'phone' => $request->phone ?: $userProfile->phone,
            'supervisor' => $request->supervisor ?: $userProfile->supervisor,
            'image' => $this->updateImage($userProfile),
            'location' => $newlocation
        ]);
        
        return response()->json([
          "data" => $userProfile,
          "message" => "Profile Updated"
      ], 200);
    }



    /**
     * Return the User's Profile Image.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function getUserProfileImage(User $user,UserProfile $userProfile)
    {
         $pathToFile = public_path().'/storage/images/'.$userProfile->image;
  
         return response()->file($pathToFile);
    }


    /**
     * Remove the User's Profile from storage.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function deleteUserProfile(User $user,UserProfile $userProfile)
    {
       $this->deleteImage($userProfile); 
       $userProfile->delete(); 

       return response()->json(["message" => "Profile Deleted!"], 200);
    }

    public function showInvitePage(Invite $invite)
    {
        //Check if invite URL has been tampered with

        if (! request()->hasValidSignature()) {
           abort(401,'Unauthorised Access!');
        }
       
        return response()->json(['data' => [
             "email" => $invite->email,
             "invite" => $invite->uuid
        ]], 200);
    }

    public function getAdminUsers()
    {
      $role = Role::where('name','superAdmin')->first();
      $adminUsers = $role->users->all();

      return response()->json(['data'=> $adminUsers], 200);
    }

    public function getShopOwners()
    {
      $role = Role::where('name','shopOwner')->first();
      $shopOwners = $role->users->all();

      return response()->json(['data'=> $shopOwners], 200);
    }

}
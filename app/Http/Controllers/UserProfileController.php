<?php

namespace App\Http\Controllers;

use App\UserProfile;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{

    /**
     * Store a newly created Profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Request $request->location array(country,state & city)
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user)
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

        if ($request->hasFile('image')){
        $image = $request->file('image');
        $filenameWithExt = $image->getClientOriginalName();
        $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);

        $extension = $image->getClientOriginalExtension();
        $imageToStore = $filename.'_'.time().'.'.$extension;
            
        $path = $image->storeAs('public/images',$imageToStore);
        
      } else {
        $imageToStore = 'no_image.jpg';
      }
      
        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        
        $userProfile = new UserProfile([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone' => $request->phone,
            'supervisor' => $request->supervisor,
            'image' => $imageToStore,
            'location' => json_encode($request->location)
        ]);
        
        $user->userprofile()->save($userProfile);  //bind the profile to it's user
        
        return response()->json([
          "data" => $userProfile,
          "message" => "Profile Created"
      ], 201);
    }

    /**
     * Return the user's Profile
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function show(User $user,UserProfile $userProfile)
    {
         return response()->json(["data" => $userProfile], 200);
    }

    /**
     * Update the User's profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserProfile $userProfile,Request $request)
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
        
        if ($request->hasFile('image')){
         
            $image = $request->file('image');
            $filenameWithExt = $image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);

            $extension = $image->getClientOriginalExtension();
            $imageToStore = $filename.'_'.time().'.'.$extension;

            $path = $image->storeAs('public/images/',$imageToStore);

                    //Prevent the default image from being deleted
                    if($userProfile->image !== 'no_image.jpg'){
                      Storage::delete('public/images/'.$userProfile->image); 
                    }  

      } else {

             $imageToStore = 'no_image.jpg';

      }

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        $location = json_decode($userProfile->location,true);

        $newlocation = json_encode([
          "country" => isset($request->location['country']) ? $request->location['country'] : $location['country'],
          "state" => isset($request->location['state']) ? $request->location['state'] : $location['state'],
          "city" => isset($request->location['city']) ? $request->location['city'] : $location['city']
        ]);
        
        $userProfile->update([
            'firstname' => $request->firstname ?: $userProfile->firstname,
            'lastname' => $request->lastname ?: $userProfile->lastname,
            'phone' => $request->phone ?: $userProfile->phone,
            'supervisor' => $request->supervisor ?: $userProfile->supervisor,
            'image' => $imageToStore,
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
    public function image(User $user,UserProfile $userProfile)
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
    public function destroy(User $user,UserProfile $userProfile)
    {
        //Prevent the default image from being deleted
        if($userProfile->image !== 'no_image.jpg'){
          Storage::delete('public/images/'.$userProfile->image);
        } 
       $userProfile->delete(); 

       return response()->json(["message" => "Profile Deleted!"], 200);
    }
}

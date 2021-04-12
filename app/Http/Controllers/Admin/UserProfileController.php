<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UserProfile;
use App\User;
use Illuminate\Http\Request;
use App\Repository\UserRepository;

class UserProfileController extends Controller
{

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request $request->location array(country,state,city)
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $user)
    {
        return $this->userRepo->storeUserProfile($request,$user);
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


    public function image(User $user,UserProfile $userProfile)
    {
        return $this->userRepo->getUserProfileImage($user,$userProfile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request, UserProfile $userProfile)
    {
        return $this->userRepo->updateUserProfile($user,$userProfile,$request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user,UserProfile $userProfile)
    {
         return $this->userRepo->deleteUserProfile($user,$userProfile);
    }
}

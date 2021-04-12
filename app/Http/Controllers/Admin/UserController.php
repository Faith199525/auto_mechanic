<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\AutoShop;
use App\Repository\UserRepository;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data'=>User::all()], 200);
    }

    /**
     * Fetch all Super Admins
     * 
     * @return \Illuminate\Http\Response
     */

    public function getAdminUsers()
    {
        return $this->userRepo->getAdminUsers();
    }


    /**
     * Fetch all Shop Owners
     * 
     * @return \Illuminate\Http\Response
     */

    public function getShopOwners()
    {
        return $this->userRepo->getShopOwners();
    }

    /**
     * Fetch all Users in an Autoshop
     * 
     * @return \Illuminate\Http\Response
     */

    public function getUsersByAutoshop(Autoshop $autoshop)
    {
        return response()->json(['data' => $autoshop->user], 200);
    }

    /**
     * Store an Admin User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->userRepo->storeAdmin($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response()->json(['data' => $user,'message' => 'User updated!'], 200);
    }


    public function updateUserRole(Request $request,User $user)
    {
        $this->userRepo->updateUserRole($request,$user);

        return response()->json(['message' => 'Role updated!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['data' => $user,'message' => 'User deleted'], 200);
    }
}

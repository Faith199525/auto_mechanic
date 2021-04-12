<?php

namespace App\Repository;

use Illuminate\Http\Request;
use App\Role;
use Validator;
use Illuminate\Support\Str;

class RoleRepository {

    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'permissions' => 'required|array'
        ]);

        $role = Role::create([
            'name' => Str::camel($request->name),
            'permissions' => $request->permissions
        ]);
        
        return response()->json([
          "data" => $role,
          "message" => $request->name." role created"
        ], 201);
    }

    public function updateRole($request,$role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => Str::camel($request->name)?:$role->name,
            'permissions' => $request->permissions?:$role->permissions
        ]);
        
        return response()->json([
          "data" => $role,
          "message" => "Role updated!"
        ], 200);
    }

}
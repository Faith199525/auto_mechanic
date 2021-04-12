<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Vehicle;
use Illuminate\Http\Request;
use Validator;

class InventoryController extends Controller
{
    /**
     * Display all inventories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Inventory::all(), 200);
    }

    /**
     * Store a new inventory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id'  => 'required|exists:App\Vehicle,id',
            'inventory' => 'required|array',
            'comment' => 'nullable',
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }
        
        $inventory = Inventory::create($request->all());

        return response()->json([
            "data" => $inventory,
            "message" => "Inventory created"
        ], 201);
    }

    /**
     * Show all Inventories for a vehicle
    */
    public function showAll(Vehicle $vehicle)
    {
        
        return response()->json(['data' => $vehicle->inventories], 200);
    }

    /**
     * Show a specific Inventory
    */
    public function show(Inventory $inventory)
    {
        return response()->json(['data' => $inventory], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Inventory $inventory)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id'  => 'required',
            'inventory' => 'required|array',
            'comment' => 'nullable',
        ]);

        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        $inventory->update($request->all());

        return response()->json([
            "data" => $inventory,
            "message" => "Inventory Updated"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(["message" => "Inventory Deleted"], 200);
    }
}

<?php

namespace App\Repository;

use Validator;

class AutoshopRepository {

    public function updateAutoshop($request,$autoShop)
    {
        $validator = Validator::make($request->all(), [
            'auto_shop_name' => 'string',
            'auto_shop_address' => 'string',
            'auto_shop_email' => 'nullable|email',
            'staff_size' => 'string'
        ]);
      
        if($validator->fails()){
             return response()->json(["error" => $validator->messages()], 422);
        }

        $autoShop->update($request->validated());
    }

    public function disableAutoshop($autoShop)
    {
        $autoShop->update([
            'active' => 0
        ]);
    }

    public function enableAutoshop($autoShop)
    {
        $autoShop->update([
            'active' => 1
        ]);
    }
}
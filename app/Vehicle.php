<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    protected $table="vehicles";

    protected $fillable = [
        'maker','production_year','vin','vehicle_owner_id','model','license_plate_number','engine_number',
    ];

    use SoftDeletes;

    public function vehicleOwner()
    {
        return $this->belongsTo('App\VehicleOwner');
    }

    public function services()
    {
       return $this->hasMany('App\Service');
    }

    public function inventories()
    {
       return $this->hasMany('App\Inventory');
    }
}

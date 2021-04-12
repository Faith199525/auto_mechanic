<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends Model
{
    protected $table="billings";

    protected $fillable = [
        'service_id','vehicle_owner_id','amount','status'
    ];

    use SoftDeletes;

    public function workOrder()
    {
        return $this->belongsTo('App\WorkOrder');
    }

    public function vehicleOwner()
    {
        return $this->belongsTo('App\VehicleOwner');
    }
}

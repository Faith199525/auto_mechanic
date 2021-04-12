<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleOwner extends Model
{
    protected $table="vehicle_owners";

    protected $fillable = [
        'firstname','lastname', 'email', 'phone_number','address','auto_shop_id'
    ];

    use SoftDeletes;

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle');
    }

    public function autoshop()
    {
        return $this->belongsTo('App\AutoShop');
    }

    public function billings()
    {
        return $this->hasMany('App\Billing');
    }
}

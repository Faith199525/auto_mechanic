<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoShop extends Model
{
    protected $table="autoshops";

    protected $fillable = [
        'auto_shop_name','auto_shop_address', 'auto_shop_email', 'staff_size','active'
    ];

    protected $hidden = [
        'active'
    ];
    
    use SoftDeletes;

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public function vehicles()
    {
        return $this->hasManyThrough('App\Vehicle','App\VehicleOwner');
    }

    public function vehicleOwners()
    {
        return $this->hasMany('App\VehicleOwner');
    }
}

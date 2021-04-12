<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
	use SoftDeletes;

	protected $table='services';
    protected $guarded = ['deleted_at'];
 	protected $hidden=['deleted_at'];

 	public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function inventory()
    {
        return $this->belongsTo('App\Inventory');
    }
    
    public function estimate()
    {
        return $this->hasOne('App\Estimate');
    }

    public function workOrders()
    {
       return $this->hasOne('App\WorkOrder');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $casts = [
        'inventory' => 'array'
    ];

    protected $fillable = [
        'vehicle_id','inventory', 'comment'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle');
    }

    public function workOrder()
    {
       return $this->hasOne('App\Service');
    }
}

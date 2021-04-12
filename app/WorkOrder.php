<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{

	protected $table="work_orders";

    protected $guarded = ['deleted_at'];
    protected $hidden=['deleted_at'];

    use SoftDeletes;

    public function service()
    {
        return $this->belongsTo('App\Service');
    }

     public function billing()
    {
        return $this->hasOne('App\Billing');
    }

    public function images()
    {
        return $this->morphMany('App\ImageFile', 'imageable');
    }
}

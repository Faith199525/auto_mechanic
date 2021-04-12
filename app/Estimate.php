<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
	use SoftDeletes;

	protected $table='estimates';
    protected $casts = [
        'items' => 'array'
    ];
 	
 	protected $guarded = ['deleted_at'];
 	protected $hidden=['deleted_at'];

 	public function service()
    {
        return $this->belongsTo('App\Service');
    }
}

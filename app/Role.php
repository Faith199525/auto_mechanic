<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    /**
     * The attributes that should be cast to array
     * 
     */
    protected $casts = [
        'permissions' => 'array'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','permissions'
    ];

    use SoftDeletes;


    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('autoshop_id');
    }
}

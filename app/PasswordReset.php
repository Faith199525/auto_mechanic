<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
     protected $table="password_resets";

     public $timestamps = false;
     
     protected $fillable = [
        'email',
    ];

    use SoftDeletes;

}

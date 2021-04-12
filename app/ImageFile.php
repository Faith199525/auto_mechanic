<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageFile extends Model
{
    protected $table="image_files";

    protected $fillable = ['name','path','imageable_id','imageable_type'];

    protected $hidden = ['created_at','updated_at','deleted_at','imageable_id','imageable_type'];

    use SoftDeletes;

    public function imageable()
    {
        return $this->morphTo();
    }
}

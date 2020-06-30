<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use SoftDeletes;
	
    protected $fillable=[ 'name', 'permissions', 'created_at', 'updated_at','deleted_at'];
}

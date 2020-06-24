<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

    protected $fillable = ['name', 'user_id', 'updated_at', 'created_at', 'is_active', 'deleted_at'];

    
}

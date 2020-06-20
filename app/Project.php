<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['project_id', 'name', 'details', 'created_at', 'updated_at', 'deleted_at'];
}

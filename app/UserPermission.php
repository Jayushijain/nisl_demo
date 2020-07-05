<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
	protected $fillable = ['user_id', 'features', 'capabilities'];

	/**
	 * Determines if user has permissions.
	 *
	 * @param  str  $feature     The feature/module
	 * @param  str  $capability  The capability/action
	 *
	 * @return bool True if has permissions, False otherwise.
	 */
	public function has_permissions($arr)
	{
		if($arr[1] == 'index')
		{
			$arr[1] == 'view';
		}
		else if($arr[1] == 'update' || strpos($arr[1],'update') == true)
		{
			$arr[1] == 'edit';
		}
		else if($arr[1] == 'destroy' || strpos($arr[1],'delete') == true)
		{
			$arr[1] == 'delete';
		}
		else if($arr[1] == 'store' || $arr[1] == 'add')
		{
			$arr[1] == 'create';
		}


		$permission = $this->where([
			['user_id', get_loggedin_user_id()],
			['features', $arr[0]],
			['capabilities', $arr[1]]
		])->get();

		if ($permission)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

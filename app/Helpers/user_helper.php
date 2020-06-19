<?php

use Illuminate\Support\Facades\DB;

/**
 * Gets the loggedin user identifier.
 *
 * @return int  The loggedin user identifier.
 */
function get_loggedin_user_id()
{
	return session('user_id');
}

/**
 * Gets the requested info of logged in user.
 *
 * @param  str  $info  The key of the information required.
 *
 * @return mixed The information required.
 */
function get_loggedin_info($info)
{
	return session('username');
}

/**
 * Gets the requested info of user.
 *
 * @param  int  $id    The id of the user.
 * @param  str  $info  The key of the information required.
 *
 * @return mixed The information required.
 */
function get_user_info($id, $info = '')
{
	$user = DB::table('users')->where('id',$id)->get();

	if ($info != '')
	{
		return $user->$info;
	}
	else
	{
		return $user;
	}
}

/**
 * Determines if user has permissions.
 *
 * @param  str  $feature     The feature/module
 * @param  str  $capability  The capability/action
 *
 * @return bool True if has permissions, False otherwise.
 */
function has_permissions($feature, $capability)
{
	$permissions = DB::table('user_permissions')->where([
		['user_id','=', get_loggedin_user_id()],
		['features','=', $feature],
		['capabilities','=', $capability]
	])->get();

	if ($permissions)
	{
		return true;
	}
	else
	{
		return false;
	}
}



?>
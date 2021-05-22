<?php

use Illuminate\Support\Facades\DB;
//use App\ActivityLog;

/**
 * Sets the notification alert on different evets performed.
 *
 * @param str  $type     The type
 * @param str  $message  The message
 */
function set_alert($type, $message)
{
	Session::flash($type, $message);
	
}

/**
 * Logs an activity into the database if enabled.
 *
 * @param str  $description  The description
 * @param str  $user_id      The id of the user doing the activity
 */
function log_activity($description, $user_id = '')
{
	if (get_settings('log_activity') == 1)
	{
		if ($user_id == '')
		{
			$user_id = get_loggedin_user_id();
		}

		$data = array(
			'description' => $description,
			'date'        => date('Y-m-d H:i:s'),
			'user_id'     => $user_id,
			'ip_address'  => $CI->input->ip_address()
		);

		DB::table('activity_logs')->insert($data);
	}
}

/**
 * Gets the settings value for the passed key.
 * Returns all the settings values of no key is passed.
 *
 * @param  str  $name  The key of the settings
 *
 * @return str  The settings value.
 */
function get_settings($name = '')
{
	if ($name == '')
	{
		$settings = DB::table('settings')->get();

		return $settings;
	}
	else
	{
		$result =  DB::table('settings')->where('name', $name)->value('value');

		if ($result)
		{
			return $result;
		}
		else
		{
			return null;
		}
	}
}

/**
 * Checks if user is logged in or not
 * @return boolean
 */
function is_user_logged_in()
{
	if (session('user_logged_in'))
	{
		return get_user_info(session('user_id'), 'is_active');
	}

	return false;
}

/**
 * Determines if active controller.
 *
 * @param  str  $controller  The controller
 *
 * @return bool True if active controller, False otherwise.
 */
function is_active_controller($controller)
{
	if (request()->is('nisl_demo/admin/'.$controller))
	{
		return TRUE;
	}

	return FALSE;
}

/**
 * Generates a random hash key for Forgot Password functionality
 *
 * @return str  Hash key
 */
function app_generate_hash()
{
	return md5(rand().microtime().time().uniqid());
}

/**
 * Gets the role by identifier.
 *
 * @param  int  $id  The identifier
 *
 * @return str  The role by identifier.
 */
function get_role_by_id($id)
{
	$role = App\Role::find($id);

	return $role->name;
}

/**
 * Gets the email template for the passed slug.
 *
 * @param  str  $slug  The slug name of the template
 *
 * @return str  The email template.
 */
function get_email_template($slug)
{
	$result = DB::table('email_templates')->where('slug', $slug)->first();

	if ($result)
	{
		return $result;
	}
	else
	{
		return null;
	}
}

/**
	 * Set the page title.
	 * @param str $page_title The title to be set.
	 *
	 * @return str  The page title.
	 */
	function set_page_title($page_title)
	{
		if (strpos(url()->current(), '/admin') == true)
		{
			return $page_title = get_settings('company_name').' | Admin Panel | '.$page_title;
		}
		else
		{
			return $page_title = get_settings('company_name').' | '.$page_title;
		}
	}

?>
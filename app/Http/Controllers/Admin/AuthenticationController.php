<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Facades\App\Authentication;
use Illuminate\Http\Request;
use App\User;

class AuthenticationController extends Controller
{
	public function index(Request $request)
	{
		return (new AuthenticationController())->login($request);		
	}

	public function login(Request $request)
	{
		if ($request->email)
		{
			$email    = $request->email;
			$password = $request->password;
			$remember = $request->remember;			
			$user = Authentication::login($email, $password, $remember);
			
			if (is_array($user) && isset($user['user_inactive']))
			{
				set_alert('error', __('messages.your_account_is_not_active'));
				return redirect('/admin/authentication');
			}
			elseif (is_array($user) && isset($user['invalid_email']))
			{
				set_alert('error', __('messages.incorrect_email'));
				return redirect('/admin/authentication');
			}
			elseif (is_array($user) && isset($user['invalid_password']))
			{
				set_alert('error', __('messages.incorrect_password'));
				return redirect('/admin/authentication');
			}
			elseif ($user == false)
			{
				set_alert('error', __('messages.incorrect_email_or_password'));
				return redirect('/admin/authentication');
			}

			//If previous redirect URL is set in session, redirect to that URL
			//maybe_redirect_to_previous_url();

			//Else rediret to admin home page.
			//return 'true';
			return redirect('/admin/dashboard');
		}
		else
		{
			echo view('admin.authentication.index');
		}
	}

	/**
	 * Loads forgot password form & performs forgot password
	 */
	public function forgot_password(Request $request)
	{
		//$this->set_page_title(_l('forgot_password'));

		if ($request->email)
		{
			$success = Authentication::forgot_password($request->email, true);

			if (is_array($success) && isset($success['user_inactive']))
			{
				set_alert('error', __('messages.your_account_is_not_active'));
			}
			elseif (is_array($success) && isset($success['invalid_user']))
			{
				set_alert('error', __('messages.incorrect_email'));
			}
			elseif ($success == true)
			{
				set_alert('success', __('messages.check_email_for_resetting_password'));
			}
			else
			{
				set_alert('error', __('messages.error_setting_new_password_key'));
			}

			return redirect('/admin/forgot_password');
		}
		else
		{
			return view('admin.authentication.forgot_password');
		}		
	}


	/**
	 * Loads reset password form & resets the password
	 *
	 * @param int  $user_id       The user identifier
	 * @param str  $new_pass_key  The new pass key
	 */
	public function reset_password(Request $request,$user_id = 0, $new_pass_key = '')
	{
		if (($user_id == 0) || ($new_pass_key == ''))
		{
			return redirect('/admin/authentication');
		}

		//$this->set_page_title(_l('reset_password'));

		if (!Authentication::can_reset_password($user_id, $new_pass_key))
		{
			set_alert('error', __('messages.password_reset_key_expired'));
			return redirect('/admin/authentication');
		}

		if ($request->password)
		{
			$success = Authentication::reset_password($user_id, $new_pass_key, $request->password);

			if (is_array($success) && $success['expired'] == true)
			{
				set_alert('error', __('messages.password_reset_key_expired'));
			}
			elseif ($success == true)
			{
				set_alert('success', __('messages.password_reset_message'));
			}
			else
			{
				set_alert('error', __('messages.new_password_is_same_as_old_password'));
				return redirect('/admin/forgot_password');
			}

			return redirect('/admin/authentication');
		}

		return view('admin.authentication.reset_password');
	}

	/**
	 * Does logout
	 */
	public function logout()
	{
		Authentication::logout();
		return redirect('/admin/authentication');
	}
}

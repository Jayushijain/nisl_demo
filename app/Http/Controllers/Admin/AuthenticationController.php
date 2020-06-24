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
				log_activity("Inactive User Tried to Login [Email: $email]", $user['id']);
				return redirect('/admin/authentication');
			}
			elseif (is_array($user) && isset($user['invalid_email']))
			{
				set_alert('error', __('messages.incorrect_email'));
				log_activity("Non Existing User Tried to Login [Email: $email]");
				return redirect('/admin/authentication');
			}
			elseif (is_array($user) && isset($user['invalid_password']))
			{
				set_alert('error', __('messages.incorrect_password'));
				log_activity("Failed Login Attempt With Incorrect Password [Email: $email]", $user['id']);
				return redirect('/admin/authentication');
			}
			elseif ($user == false)
			{
				set_alert('error', __('messages.incorrect_email_or_password'));
				log_activity("Failed Login Attempt [Email: $email]");
				return redirect('/admin/authentication');
			}

			log_activity("User Logged In [Email: $email]");

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

		if (is_user_logged_in())
		{
			return redirect('/admin/authentication');
		}

		if ($request->email)
		{
			$success = $this->Authentication_model->forgot_password($this->input->post('email'), true);

			if (is_array($success) && isset($success['user_inactive']))
			{
				set_alert('error', _l('your_account_is_not_active'));
			}
			elseif (is_array($success) && isset($success['invalid_user']))
			{
				set_alert('error', _l('incorrect_email'));
			}
			elseif ($success == true)
			{
				set_alert('success', _l('check_email_for_resetting_password'));
			}
			else
			{
				set_alert('error', _l('error_setting_new_password_key'));
			}

			redirect(admin_url('authentication/forgot_password'));
		}

		return view('admin.authentication.forgot_password');
	}

	/**
	 * Does logout
	 */
	public function logout()
	{
		log_activity('User Logged Out [Email: '.get_loggedin_info('email').']', get_loggedin_user_id());
		Authentication::logout();
		return redirect('/admin/authentication');
	}
}

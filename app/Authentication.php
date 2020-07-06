<?php

namespace App;

use App\User;
use Facades\Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Authentication extends Model
{
	public function login($email, $password, $remember)
	{
		if ((!empty($email)) && (!empty($password)))
		{
			$user = User::where('email', $email)->first();

			if ($user)
			{
				if ($user->password != md5($password))
				{
					return ['invalid_password' => true, 'id' => $user->id];
				}
			}
			else
			{
				return ['invalid_email' => true];
			}

			if ($user->is_active == 0)
			{
				return ['user_inactive' => true, 'id' => $user->id];
			}

			if ($user->is_admin != 1 && $user->is_email_verified == 0)
			{
				return ['email_unverified' => true, 'id' => $user->id];
			}

			$user_data = [
				'user_id'        => $user->id,
				'email'          => $user->email,
				'username'       => ucwords($user->firstname.' '.$user->lastname),
				'is_admin'       => $user->is_admin,
				'user_logged_in' => true
			];

			session($user_data);

// 	if ($remember)

// 	{

// 		$this->create_autologin($user->id);
			// 	}

			$this->update_login_info($user->id);

			return true;
		}

		return false;
	}

	/**
	 * Update login info on autologin
	 *
	 * @param int  $user_id  The user identifier
	 */
	private function update_login_info($user_id)
	{
		User::where('id', $user_id)->update(array('last_ip' => Request::ip(), 'last_login' => date('Y-m-d H:i:s')));
	}

	/**
	 *
	 * Generates new password key for the user to reset the password
	 *
	 * @param  str   $email  The email from the user
	 *
	 * @return bool  True if user exists & link is sent to user email, False otherwise
	 */
	public function forgot_password($email)
	{
		$user = DB::table('users')->where('email', $email)->first();

		if ($user)
		{
			if ($user->is_active == 0)
			{
				return ['user_inactive' => true];
			}

			if ($user->is_admin != 1 && $user->is_email_verified == 0)
			{
				return ['email_unverified' => true];
			}

			$new_pass_key = app_generate_hash();

			$update_user = DB::table('users')->where('id', $user->id)->update([
				'new_pass_key'           => $new_pass_key,
				'new_pass_key_requested' => date('Y-m-d H:i:s')
			]);

			if ($update_user)
			{
				$template = get_email_template('forgot-password');
				$subject  = $template->subject;

				$message = get_settings('email_header');

				$user = DB::table('users')->where('email', $email)->first();

				if ($user->is_admin)
				{
					$reset_password_link = url('/authentication/reset_password/').$user->id.'/'.$user->new_pass_key;
				}
				else
				{
					$reset_password_link = url('/authentication/reset_password/').$user->id.'/'.$user->new_pass_key;
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{email}',
					'{reset_password_link}',
					'{email_signature}',
					'{company_name}'
				];

				$replace = [
					$user->firstname,
					$user->lastname,
					$email,
					$reset_password_link,
					get_settings('email_signature'),
					get_settings('company_name')
				];

				$message .= str_replace($find, $replace, $template->message);

				$message .= str_replace('{company_name}', get_settings('company_name'), get_settings('email_footer'));

				$sent = send_email($email, $subject, $message);

				if ($sent)
				{
					return true;
				}

				return false;
			}

			return false;
		}

		return ['invalid_user' => true];
	}

	public function verify_email($signup_key)
	{
		$user = User::where('signup_key', $signup_key)->count();

		if ($user == 1)
		{
			$input['is_email_verified'] = 1;
			$input['is_active'] = 1;
			User::where('signup_key', $signup_key)->update($input);

			return true;
		}

		return null;
	}

	/**
	 * Resets user password after successful validation of the key
	 *
	 * @param  int   $user_id       The user identifier
	 * @param  str   $new_pass_key  The new pass key
	 * @param  str   $password      The password
	 *
	 * @return bool  True if the password is reset, Null otherwise
	 */
	public function reset_password($user_id, $new_pass_key, $password)
	{
		if (!$this->can_reset_password($user_id, $new_pass_key))
		{
			return ['expired' => true];
		}

		$update = User::where([
			['id', $user_id],
			['new_pass_key', $new_pass_key]
		])->update(array('password' => md5($password)));

		if ($update)
		{
			$input['new_pass_key']           = null;
			$input['new_pass_key_requested'] = null;
			$input['last_password_change']   = date('Y-m-d H:i:s');
			User::where([
				['id', $user_id],
				['new_pass_key', $new_pass_key]
			])->update($input);

			return true;
		}

		return null;
	}

	/**
	 * Determines if the key is not expired or doesn't exists in database
	 *
	 * @param  int  $user_id       The user identifier
	 * @param  str  $new_pass_key  The new pass key
	 *
	 * @return bool True if key is active, False otherwise
	 */
	public function can_reset_password($user_id, $new_pass_key)
	{
		$user = User::where([
			['id', $user_id],
			['new_pass_key', $new_pass_key]])->get();

		if ($user)
		{
			$timestamp_now_minus_1_hour = time() - (60 * 60);
			$new_pass_key_requested     = strtotime($user->new_pass_key_requested);

			if ($timestamp_now_minus_1_hour > $new_pass_key_requested)
			{
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * Deletes an autologin when user logs out
	 */
	private function delete_autologin()
	{
		$this->load->helper('cookie');

		if ($cookie = get_cookie('autologin', true))
		{
			$data = unserialize($cookie);
			$this->user_autologin->delete($data['user_id'], md5($data['key']));
			delete_cookie('autologin', 'aal');
		}
	}

	/**
	 * Clears the autologin & session
	 */
	public function logout()
	{
		//$this->delete_autologin();

		session()->flush();
	}
}

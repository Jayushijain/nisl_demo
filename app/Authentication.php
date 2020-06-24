<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User;

class Authentication extends Model
{
    public function login($email, $password, $remember)
    {
    	if ((!empty($email)) && (!empty($password)))
		{
			$user = User::where('email',$email)->first();

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

		// 	$this->update_login_info($user->id);

		 	return true;
		 }

		 return false;
    }

    // public function checkLogin()
    // {
    // 	if($this->login)
    // }

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
		$user = DB::table('users')->where('email',$email)->first();

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

				$user = DB::table('users')->where('email',$email)->first();

				if ($user->is_admin)
				{
					$reset_password_link = url('authentication/reset_password/').$user->id.'/'.$user->new_pass_key;
				}
				else
				{
					$reset_password_link = url('authentication/reset_password/').$user->id.'/'.$user->new_pass_key;
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
					$user['firstname'],
					$user['lastname'],
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

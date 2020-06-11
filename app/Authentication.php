<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Authentication extends Model
{
    public function login($email, $password, $remember)
    {
    	if ((!empty($email)) && (!empty($password)))
		{
			//return $email;
			$user = DB::table('users')->get();
			if(count($user)>0)
			{
				dd($user);	
			}
			else
			{
				echo 'none';
			}

		// 	if ($user)
		// 	{
		// 		if ($user->password != md5($password))
		// 		{
		// 			return ['invalid_password' => true, 'id' => $user->id];
		// 		}
		// 	}
		// 	else
		// 	{
		// 		return ['invalid_email' => true];
		// 	}

		// 	if ($user->is_active == 0)
		// 	{
		// 		return ['user_inactive' => true, 'id' => $user->id];
		// 	}

		// 	if ($user->is_admin != 1 && $user->is_email_verified == 0)
		// 	{
		// 		return ['email_unverified' => true, 'id' => $user->id];
		// 	}

		// 	$user_data = [
		// 		'user_id'        => $user->id,
		// 		'email'          => $user->email,
		// 		'username'       => ucwords($user->firstname.' '.$user->lastname),
		// 		'is_admin'       => $user->is_admin,
		// 		'user_logged_in' => true
		// 	];

		// 	$this->session->set_userdata($user_data);

		// 	if ($remember)
		// 	{
		// 		$this->create_autologin($user->id);
		// 	}

		// 	$this->update_login_info($user->id);

		// 	return true;
		 }

		// return false;
    }
}

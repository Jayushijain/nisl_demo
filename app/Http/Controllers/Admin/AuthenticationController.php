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
		$this->login($request);
		//(new AuthenticationController())->login($request);
	}

	public function login(Request $request)
	{
		$user = DB::table('users')->get();
			if(count($user)>0)
			{
				dd($user);	
			}
			else
			{
				echo 'none';
			}
		if ($request->all())
		{
			$email    = $request->email;
			$password = $request->password;
			$remember = $request->remember;

			
			// $eg = Authentication::login($email, $password, $remember);
			// dd($eg);
		}
		else
		{
			echo view('admin.authentication.index');
		}
	}
}

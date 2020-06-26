<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$users = User::get();

		return view('admin.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$roles = Role::get();

		return view('admin.users.create', compact('roles'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$input = $request->except(['_token']);

		$input['password']   = md5($request->password);
		$input['is_active']  = 1;
		$input['signup_key'] = '';
		$input['last_ip']    = $request->ip();

		if ($request->role == 1)
		{
			$input['is_admin'] = 1;
		}
		else
		{
			$input['is_admin'] = 0;
		}

		$insert = User::save($input);
        log_activity("New User Created [ID: $insert->id]");

        if($insert)
        {
            set_alert('success', __('messages._added_successfully', ['Name' => __('messages.user')]));

            return redirect('/admin/users');            
        }
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$roles = Role::get();
		$user  = User::find($id);

		return view('admin.users.edit', compact('roles', 'user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$input = $request->except(['_token']);
		$user  = User::find($id);

		if ($request->newpassword == NULL)
		{
			$input['is_active'] = $request->is_active ? 1 : 0;
		}
		else
		{
			$input['password']  = md5($request->password);
			$input['is_active'] = $request->is_active ? 1 : 0;
		}

		$input['is_admin'] = ($request->role == 1) ? 1 : 0;
		$update            = $user->update($input);

		if ($update)
		{
			set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.user')]));
			log_activity("User Updated [ID: $user->id]");

			return redirect('/admin/users');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);

		if ($user->delete())
		{
            log_activity("User Deleted [ID: $user->id]");
			echo 'true';
		}
		else
		{
			echo 'false';
		}


	}
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Auth;
use Log;
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
		try{
			$users = User::where('id','!=',Auth::user()->id)->get();
			$data['page_title'] = 'Users';
			return view('admin.users.index', compact('users'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('UserController index: '.$e->getMessage());
            return redirect('/admin/dashboard')->with('error_message','Something went wrong! Please Try again');
        }		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		try{
			$roles = Role::get();
			$data['page_title'] = 'Users';
			return view('admin.users.create', compact('roles'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('UserController create: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		try{
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
		catch (\RuntimeException $e){
            Log::info('UserController store: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		try{
			$roles = Role::get();
			$user  = User::find($id);
			$data['page_title'] = 'Users';

			if($user)
			{
				return view('admin.users.edit', compact('roles', 'user'),$data);
			}
			else
			{
				set_alert('success', __('messages.no_data_found'));
				return redirect('/admin/users');
			}
		}
		catch (\RuntimeException $e){
            Log::info('UserController edit: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }
		
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
		try{
			$input = $request->except(['_method', '_token']);
			$user  = User::find($id);
			$data['page_title'] = 'Users';
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
			$data['page_title'] = 'Users';
			if ($update)
			{
				set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.user')]));
				return redirect('/admin/users');
			}
		}
		catch (\RuntimeException $e){
            Log::info('UserController update: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }		
	}


	/**
	 * Toggles the user status to Active or Inactive
	 */
	public function update_status(Request $request)
	{
		try{
			$user_id        = $request->user_id;
			$user           = User::find($user_id);
			$input['is_active'] = $request->is_active;

			$update = $user->update($input);

			if ($update)
			{
				if ($request->is_active == 1)
				{
					echo 'true';
				}
				else
				{
					echo 'false';
				}
			}
		}
		catch (\RuntimeException $e){
            Log::info('UserController update_status: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
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
		try{
			$user = User::find($id);

			if ($user->delete())
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
		catch (\RuntimeException $e){
            Log::info('UserController destroy: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }
		
	}

	/**
	 * Deletes multiple category records
	 */
	public function delete_selected(Request $request)
	{
		try{
			$ids     = $request->ids;
			$deleted = User::destroy($ids);

			if ($deleted)
			{
				echo 'true';
			}
			else
			{
				echo 'false';
			}
		}
		catch (\RuntimeException $e){
            Log::info('UserController delete_selected: '.$e->getMessage());
            return redirect('/admin/users')->with('error_message','Something went wrong! Please Try again');
        }				
	}
}

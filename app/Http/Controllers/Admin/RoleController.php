<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\UserPermission;
use Illuminate\Http\Request;
use Log;

class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try{
			$roles = Role::get();
			$data['page_title'] = 'Roles';
			return view('admin.roles.index', compact('roles'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('RoleController index: '.$e->getMessage());
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
			$permissions_data = $this->default_permissions();
			$data['page_title'] = 'Roles';
			return view('admin.roles.create', compact('permissions_data'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('RoleController index: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
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
			$input                = $request->except(['_token']);
			$input['permissions'] = serialize($request->permissions);
			$insert = Role::insert($input);

			if ($insert)
			{
				set_alert('success', __('messages._added_successfully', ['Name' => __('messages.role')]));
				return redirect('/admin/roles');
			}
		}
		catch (\RuntimeException $e){
            Log::info('RoleController store: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
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
			$permissions_data = $this->default_permissions();
			$users            = User::where('role', $id)->get();
			$role             = Role::find($id);
			$data['page_title'] = 'Roles';
			return view('admin.roles.edit', compact('permissions_data', 'role', 'users'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('RoleController edit: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
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
			$role                 = Role::find($id);
			$input                = $request->except(['_method', '_token']);
			$input['permissions'] = serialize($request->permissions);
			$update = $role->update($input);
			$users = User::where('role', $id)->get();

			if ($users != null)
			{
				$user_id_array = array();

				foreach ($users as $key => $value)
				{
					$user_id = $value['id'];
					array_push($user_id_array, $user_id);
				}

				$delete_permissions = UserPermission::whereIn('user_id', $user_id_array)->delete();
				$roles              = Role::find($id);
				$permissions        = unserialize($roles->permissions);

				foreach ($permissions as $key => $permission)
				{
					foreach ($permission as $key_permission => $value)
					{
						foreach ($user_id_array as $key_user_id => $user)
						{
							$input = array
								(
								'user_id'      => $user,
								'features'     => $key,
								'capabilities' => $value
							);
							$user_permissions_insert = UserPermission::insert($input);
						}
					}
				}

				if ($update)
				{
					set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.role')]));
					return redirect('/admin/roles');
				}
			}
		}
		catch (\RuntimeException $e){
            Log::info('RoleController update: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
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
			$role = Role::find($id);
			$users = User::where('role', $id)->get();

			if (empty($users))
			{
				$result =$role->delete();
				echo 'true';
			}
			else
				echo 'false';
		}
		catch (\RuntimeException $e){
            Log::info('RoleController destroy: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
        }		
	}

	/**
	 * Deletes multiple category records
	 */
	public function delete_selected(Request $request)
	{
		try{
			$roles                  = $request->ids;
			$deleted_role_ids       = array();
			$deleted_role_names     = array();
			$not_deleted_role_names = array();
			$output                 = '';

			foreach ($roles as $role)
			{
				$users = User::where('role', $role)->get();

				if (empty($users))
				{
					array_push($deleted_role_ids, $role);
					array_push($deleted_role_names, get_role_by_id($role));
					$result = $this->roles->delete($role);
				}
				else
				{
					array_push($not_deleted_role_names, get_role_by_id($role));
				}
			}

			$deleted_roles     = implode(', ', $deleted_role_names);
			$not_deleted_roles = implode(', ', $not_deleted_role_names);

			$data['type'] = 'success';

			if (empty($deleted_role_ids) && !empty($not_deleted_role_names))
			{
				$output .= (count($not_deleted_role_names) == 1) ? __('messages.single_role_not_deleted_msg', ['Name' => $not_deleted_roles]) : __('messages.multiple_roles_not_deleted_msg',['Name' => $not_deleted_roles]);

				$data['type'] = 'error';
			}
			else

			if (!empty($deleted_role_ids) && !empty($not_deleted_role_names))
			{
				$output .= (count($deleted_role_ids) == 1) ? __('messages.single_role_deleted_msg', ['Name'=>$deleted_roles]) : __('messages.multiple_roles_deleted_msg', ['Name'=>$deleted_roles]);

				$output .= (count($not_deleted_role_names) == 1) ? __('messages.single_role_not_deleted_msg', ['Name' => $not_deleted_roles]) : __('messages.multiple_roles_not_deleted_msg', ['Name' => $not_deleted_roles]);
			}
			else
			{
				$output .= (count($deleted_role_ids) == 1) ? __('messages.single_role_deleted_msg', ['Name'=>$deleted_roles]) : __('messages.multiple_roles_deleted_msg', ['Name'=>$deleted_roles]);
			}

			$data['deleted_role_ids'] = $deleted_role_ids;
			$data['output']           = $output;

			if (!empty($deleted_role_ids))
			{
				$deleted_role_ids = implode(',', $deleted_role_ids);
			}

			echo json_encode($data);
		}
		catch (\RuntimeException $e){
            Log::info('RoleController delete_selected: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
        }
	}

	/**
	 * Contains the Default Permissions to be used in the system.
	 * You can add or remove permissions in this function & it will reflect * on the Roles Module
	 *
	 * @return [array]      The default permissions with features & capabilities
	 */
	public function default_permissions()
	{
		try{
			$common_permissions = [
				'view'   => __('messages.view'),
				'create' => __('messages.create'),
				'edit'   => __('messages.edit'),
				'delete' => __('messages.delete')
			];

			$permissions = [
				'users'           => [
					'name'         => __('messages.users'),
					'capabilities' => $common_permissions
				],
				'projects'        => [
					'name'         => __('messages.projects'),
					'capabilities' => $common_permissions
				],
				'categories'      => [
					'name'         => __('messages.categories'),
					'capabilities' => $common_permissions
				],
				'roles'           => [
					'name'         => __('messages.roles'),
					'capabilities' => $common_permissions
				],
				'email_templates' => [
					'name'         => 'Email Templates',
					'capabilities' => [
						'view' => __('messages.view'),
						'edit' => __('messages.edit')
					]
				],
				'settings'        => [
					'name'         => __('messages.settings'),
					'capabilities' => [
						'view'   => __('messages.view'),
						'create' => __('messages.create')
					]
				]
			];
			return $permissions;
		}
		catch (\RuntimeException $e){
            Log::info('RoleController default_permissions: '.$e->getMessage());
            return redirect('/admin/roles')->with('error_message','Something went wrong! Please Try again');
        }		
	}
}
		
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\UserPermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$roles = Role::get();

		return view('admin.roles.index', compact('roles'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$permissions_data = $this->default_permissions();

		return view('admin.roles.create', compact('permissions_data'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$input                = $request->except(['_token']);
		$input['permissions'] = serialize($request->permissions);

		$insert = Role::insert($input);

		if ($insert)
		{
			set_alert('success', __('messages._added_successfully', ['Name' => __('messages.role')]));

			return redirect('/admin/roles');
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
		$permissions_data = $this->default_permissions();
		$users            = User::where('role', $id)->get();
		$role             = Role::find($id);

		return view('admin.roles.edit', compact('permissions_data', 'role', 'users'));
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$role = Role::find($id);
		$users = User::where('role', $id)->get();

		if (empty($users))
		{
			$result =$role->delete();
			echo 'true';
		}
		else
		{
			echo 'false';
		}
	}

	/**
	 * Deletes multiple category records
	 */
	public function delete_selected(Request $request)
	{
		$ids     = $request->ids;
		$deleted = Role::destroy($ids);

		if ($deleted)
		{
			echo 'true';
		}
		else
		{
			echo 'false';
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
}

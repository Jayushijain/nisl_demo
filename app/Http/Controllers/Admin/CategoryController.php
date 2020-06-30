<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$categories = Category::get();

		return view('admin.categories.index', compact('categories'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
// if (!has_permissions('categories', 'create'))

// {

//     $this->access_denied('categories', 'create');

// }

		if ($request)
		{
			$input = $request->except(['_token']);

			$input['user_id']   = get_loggedin_user_id();
			$input['is_active'] = 1;

			$insert = Category::insert($input);

			log_activity("New Category Created [ID: $insert]");

			if ($insert)
			{
				set_alert('success', __('messages._added_successfully', ['Name' => __('messages.category')]));

				return redirect('admin/categories');
			}
		}
	}

	/**
	 * Toggles the category status to Active or Inactive
	 */
	public function update_status(Request $request)
	{
		$category_id        = $request->category_id;
		$category           = Category::find($category_id);
		$input['is_active'] = $request->is_active;

		$update = $category->update($input);

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
		//$this->set_page_title(_l('categories').' | '._l('edit'));

		$category = Category::find($id);

		return view('admin.categories.edit', compact('category'));
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
		$category           = Category::where('id', $id);
		$input              = $request->except(['_method', '_token']);
		$input['is_active'] = $request->is_active ? 1 : 0;

		if ($category->update($input))
		{
			log_activity("Category Updated [ID: $id]");

			set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.category')]));

			return redirect('admin/categories');
		}
		else
		{
			echo 'hii';
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
		$category = Category::find($id);

		if ($category->delete())
		{
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
		$deleted = Category::destroy($ids);

		if ($deleted)
		{
			echo 'true';
		}
		else
		{
			echo 'false';
		}
		
	}
}

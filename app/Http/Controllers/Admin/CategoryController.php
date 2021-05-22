<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Log;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try{
			$page_title = set_page_title(__('messages.categories'));
			$categories = Category::get();
			return view('admin.categories.index', compact('categories','page_title'));
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController index: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
        } 
		
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get_categories (Request $request)
	{
		try{
			$start  = isset($request['start']) ? $request['start'] : 0;
			$length = isset($request['length']) ? $request['length'] : 10;
			$searchchar = isset($request->search['value']) ? $request->search['value'] : '';
			$orderdir = isset($request->order[0]['dir']) ? $request->order[0]['dir'] : '';
	        $ordercol = isset($request->order[0]['column']) ? $request->order[0]['column'] : '';
	        $query = Category::select('id','name');

	        if ($searchchar != '') {
	            $query->Where('name', 'like', '%' . $searchchar . '%');
	        }

	        if($orderdir != 'aesc'){
	            if ($ordercol == 1) {
	            	$query->orderBy('name', $orderdir);
	            }
	        }

			$final['data']= $query->offset($request->start)->limit($request->length)->get();
			$final['recordsTotal'] = Category::count();
			$final['recordsFiltered'] = $final['recordsTotal'];
			echo json_encode($final);
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController get_categories: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
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
			set_page_title(__('messages.categories').' | '.__('messages.add'));

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
		catch (\RuntimeException $e){
            Log::info('CategoryController store: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
        } 		
	}

	/**
	 * Toggles the category status to Active or Inactive
	 */
	public function update_status(Request $request)
	{
		try{
			$category_id        = $request->category_id;
			$category           = Category::find($category_id);
			$input['is_active'] = $request->is_active;
			$update = $category->update($input);

			if ($update)
			{
				if ($request->is_active == 1)
					echo 'true';
				else
					echo 'false';
			}
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController update_status: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
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
			set_page_title(__('messages.categories').' | '.__('messages.edit'));
			$page_title = 'Users';
			$category = Category::find($id);

			if($category)
				return view('admin.categories.edit', compact('category','page_title'));
			else
				set_alert('error', __('messages.no_data_found'));
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController edit: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
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
				return redirect('admin/categories');
			}
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController update: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
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
			$category = Category::find($id);

			if ($category->delete())
				echo 'true';
			else
				echo 'false';
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController destroy: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
        }		
	}

	/**
	 * Deletes multiple category records
	 */
	public function delete_selected(Request $request)
	{
		try{
			$ids     = $request->ids;
			$deleted = Category::destroy($ids);

			if ($deleted)
				echo 'true';
			else
				echo 'false';
		}
		catch (\RuntimeException $e){
            Log::info('CategoryController delete_selected: '.$e->getMessage());
            return redirect('/admin/categories')->with('error_message','Something went wrong! Please Try again');
        }
		
		
	}
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Project;
use Log;

class ProjectController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try{
			$projects = Project::get();
			$data['page_title'] = __('messages.dashboard');
			return view('admin.projects.index', compact('projects'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController index: '.$e->getMessage());
            return redirect('/admin/dashboard')->with('error_message','Something went wrong! Please Try again');
        }		
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get_projects (Request $request)
	{
		try{
			$final['data']= Project::offset($request->start)->limit($request->length)->get();
			$final['recordsTotal'] = Project::count();
			$final['recordsFiltered'] = $final['recordsTotal'];
			echo json_encode($final);
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController get_projects: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
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
			$data['page_title'] = "project";
			return view('admin.projects.create',$data);
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController create: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
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
			$input['project_id'] = 'PROJECT_'.rand(10, 100);
			$input['created_at'] = date('Y-m-d H:i:s');
			$insert = Project::insert($input);
			log_activity("New Project Created [ID: $insert]");

			if ($insert)
			{
				set_alert('success', __('messages._added_successfully', ['Name' => __('messages.project')]));
				return redirect('admin/projects');
			}
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController store: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
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
			$project = Project::find($id);
			$data['page_title'] = 'Project';
			return view('admin.projects.edit', compact('project'),$data);
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController edit: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
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
			$project = Project::find($id);
			$input   = $request->except(['_method', '_token']);

			$input['project_id'] = 'PROJECT_'.rand(10, 100);
			$input['updated_at'] = date('Y-m-d H:i:s');

			if ($project->update($input))
			{
				set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.project')]));
				log_activity("Project Updated [ID:$project->id]");

				return redirect('admin/projects');
			}$project = Project::find($id);
			$input   = $request->except(['_method', '_token']);

			$input['project_id'] = 'PROJECT_'.rand(10, 100);
			$input['updated_at'] = date('Y-m-d H:i:s');

			if ($project->update($input))
			{
				set_alert('success', __('messages._updated_successfully', ['Name' => __('messages.project')]));
				log_activity("Project Updated [ID:$project->id]");
				return redirect('admin/projects');
			}
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController update: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
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
			$project = Project::find($id);

	        if ($project->delete()){
	            echo 'true';
	            log_activity("Project Deleted [ID:$project->id]");
	        }
	        else
	            echo 'false';
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController destroy: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
        }		
	}

	/**
	 * Deletes multiple project records
	 */
	public function delete_selected(Request $request)
	{
		try{
			$ids     = $request->ids;
			$deleted = Project::destroy($ids);

			if ($deleted)
				echo 'true';
			else
				echo 'false';
		}
		catch (\RuntimeException $e){
            Log::info('ProjectController update: '.$e->getMessage());
            return redirect('/admin/projects')->with('error_message','Something went wrong! Please Try again');
        }
	}
}

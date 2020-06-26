<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Project;

class ProjectController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$projects = Project::get();

		return view('admin.projects.index', compact('projects'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.projects.create');
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
		$project = Project::find($id);

		return view('admin.projects.edit', compact('project'));
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
		$project = Project::where('id', $id);
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$project = Project::find($id);

        if ($project->delete())
        {
            echo 'true';
            log_activity("Project Deleted [ID:$project->id]");
        }
        else
        {
            echo 'false';
        }
	}
}

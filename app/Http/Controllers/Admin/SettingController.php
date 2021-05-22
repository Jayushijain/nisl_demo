<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Log;

class SettingController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try{
			$data['page_title'] = 'Settings';
			return view('admin.settings.index',$data);
		}
		catch (\RuntimeException $e){
            Log::info('SettingController index: '.$e->getMessage());
            return redirect('/admin/dashboard')->with('error_message','Something went wrong! Please Try again');
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
			foreach ($request->except('_token') as $key => $value)
			{
				$settig_exists = Setting::where('name', $key)->count();
	            
				if ($settig_exists == 0 && $value != '')
				{
					$input = [
						'name'  => $key,
						'value' => $value
					];

					Setting::insert($input);
				}

				if ($settig_exists == 1)
				{
					$settings = Setting::where('name', $key)->first();

					if ($settings->value != $value && $value != '')
					{
						Setting::where('id', $settings->id)->update(array('value' => $value));
					}
					else

					if ($value == '' || $value == null)
					{
						$delete = Setting::where('name', $key)->delete();
					}
				}
			}

			echo 'true';
		}
		catch (\RuntimeException $e){
            Log::info('SettingController store: '.$e->getMessage());
            return redirect('/admin/settings')->with('error_message','Something went wrong! Please Try again');
        }		
	}
}

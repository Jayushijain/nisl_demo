<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Project;
use App\Role;
use Log;

class DashboardController extends Controller
{
    public function index()
    {
    	try{
    		$data['page_title'] = __('messages.dashboard');
	    	$data['user_count'] = User::where('is_active',1)->count();
	    	$data['category_count'] = Category::count();
	    	$data['product_count'] = Project::count();
	    	$data['role_count'] = Role::count();
	    	return view('admin.dashboard.index',$data);
    	}
    	catch (\RuntimeException $e){
            Log::info('DashboardController index: '.$e->getMessage());
            return redirect('/admin/dashboard')->with('error_message','Something went wrong! Please Try again');
        } 
    	
    }
}

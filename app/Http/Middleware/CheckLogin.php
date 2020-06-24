<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
//use Facades\App\Authentication;
use Facades\App\Http\Controllers\Admin\AuthenticationController;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('user_logged_in'))
        {
            return $next($request);            
        }
        else
        {
            //return $request->all();
            return redirect('/admin/authentication');
        }
    }
}

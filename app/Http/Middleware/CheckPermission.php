<?php

namespace App\Http\Middleware;

use Closure;
use App\UserPermission;

class CheckPermission
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
		$curi = \Route::current()->getName();
		$arr  = explode('.', $curi);
        $permission = new UserPermission;

        if($permission->has_permissions($arr))
        {
            return $next($request);
        }
        else
        {
            return redirect()->route('dashboard');
        }

		
	}
}

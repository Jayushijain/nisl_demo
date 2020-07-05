<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "http://nisl_demo.test/admin/categories/update_status",
        "http://nisl_demo.test/admin/categories/delete_selected",
        "http://nisl_demo.test/admin/projects/delete_selected",
        "http://nisl_demo.test/admin/users/update_status",
        "http://nisl_demo.test/admin/users/delete_selected",
        "http://nisl_demo.test/admin/settings/add",
        "http://nisl_demo.test/admin/roles/delete_selected",
    ];

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        // If request is an ajax request, then check to see if token matches token provider in
        // the header. This way, we can use CSRF protection in ajax requests also.
        $token = $request->ajax() ? $request->header('X-CSRF-Token') : $request->input('_token');

        return $request->session()->token() == $token;
    }
}

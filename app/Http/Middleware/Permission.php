<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$permission)
    {
        if(auth()->guard('admin')->user()->hasPermissionTo($permission)) {
            return $next($request);
        } else {
            return Redirect()->route('employee.dashboard')->with('error', 'No Permission To Access');
        }
    }
}

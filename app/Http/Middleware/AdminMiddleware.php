<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Auth::guard('admin')->user()) {
            return $next($request);    
        }        
        return redirect()->route('admin.login');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Auth;

class AdminMiddleware
{

    public function handle($request, Closure $next)
    {
        if(Auth::User() && Auth::User()->role == 'admin' || Auth::User() && Auth::User()->role == 'super-admin'){
            if(Auth::User()->status == 'Inactive'){
                 Auth::logout();
                return redirect('/');
            }
            return $next($request);
        }
        return redirect('/');
    }
}

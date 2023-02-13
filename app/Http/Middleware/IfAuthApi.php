<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use  Illuminate\Support\Facades\Route;

class IfAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::get('logged_in')==1) {
            if (Route::getFacadeRoot()->current()->uri() == 'login') {
                return redirect('/');
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
         return $next($request)
             ->header('Access-Control-Allow-Origin', \Request::server('HTTP_ORIGIN'))
             ->header('Access-Control-Allow-Headers', 'origin, x-requested-with, content-Type, accept, authorization')
             ->header('Access-Control-Allow-Credentials', 'true')
             ->header('Access-Control-Max-Age', '86400')
             ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
     }
}

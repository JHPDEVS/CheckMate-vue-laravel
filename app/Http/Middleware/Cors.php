<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
         header('Access-Control-Allow-Origin: *'); 
         header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
         header('Access-Control-Allow-Credentials: false'); 
            
         return $next($request);
    }
}

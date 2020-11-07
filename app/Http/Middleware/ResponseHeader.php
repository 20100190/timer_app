<?php

namespace App\Http\Middleware;

use Closure;

class ResponseHeader
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
        $response = $next($request);
        $response->header('Cache-Control', 'no-store');
 
        return $response;
        //return $next($request);
    }
}

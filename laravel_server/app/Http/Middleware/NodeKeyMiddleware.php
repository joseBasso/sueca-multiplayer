<?php

namespace App\Http\Middleware;

use Closure;

class NodeKeyMiddleware
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
        $key = $request->header('key');
        if($key != null){
            if($key == 'secret'){
                return $next($request);
            }
        }
        return response('Correct key needed',401);
    }
}

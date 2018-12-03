<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ShowNewDeviceIfNoNewDevice
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
    	if (Auth::user()->devices->count() === 0) {
    		return redirect()->route('create.device');
	    }

        return $next($request);
    }
}

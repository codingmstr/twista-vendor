<?php

namespace App\Http\Middleware;

use App\Models\BusinessSetting;
use Closure;
use Illuminate\Support\Facades\App;


class Ahmed
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       
       info($request->url());
       info($request->all());
        return $next($request);
    }
}

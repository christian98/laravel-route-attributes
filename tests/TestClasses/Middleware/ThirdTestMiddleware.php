<?php

namespace Spatie\RouteAttributes\Tests\TestClasses\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThirdTestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}

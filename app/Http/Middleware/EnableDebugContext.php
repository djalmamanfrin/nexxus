<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnableDebugContext
{
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->id();

        $enabled = $userId
            ? Cache::get("debug:user:{$userId}", false)
            : false;

        app()->instance('debug_mode', $enabled);

        return $next($request);
    }
}

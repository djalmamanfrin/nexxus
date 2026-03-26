<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttachRequestId
{
    public function handle(Request $request, Closure $next)
    {
        $requestId = (string) Str::uuid();

        app()->instance('request_id', $requestId);
        $request->headers->set('X-Request-Id', $requestId);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next) {
    if (\App\Models\BlockedIp::where('ip_address', $request->ip())->exists()) {
        return response()->json([
            'message' => 'Your IP has been blocked due to abusive behavior. Please contact support if you believe this is a mistake.'
        ], 403);
    }
    return $next($request);
}
}

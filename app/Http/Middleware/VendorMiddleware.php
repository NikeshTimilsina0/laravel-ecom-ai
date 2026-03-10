<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role->role_name !== 'vendor') {
            abort(403, 'Unauthorized. Vendor access required.');
        }

        return $next($request);
    }
}

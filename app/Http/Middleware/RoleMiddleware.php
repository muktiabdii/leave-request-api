<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\ApiResponse;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (auth()->user()->role !== $role) {
            return ApiResponse::error(
                'Unauthorized',
                403
            );
        }

        return $next($request);
    }
}

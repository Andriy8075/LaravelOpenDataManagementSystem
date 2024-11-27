<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $rolesArray = explode('|', $roles); // Split roles by `|` (pipe) delimiter

        // Check if the user has one of the allowed roles
        if ($request->user() && in_array($request->user()->role, $rolesArray)) {
            return $next($request); // Allow access
        }

        abort(403, 'Unauthorized action.'); // Deny access
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ifAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated under the 'admin' guard
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // If not an admin, return appropriate response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized access. Admins only.'], 403);
        }

        return redirect()->route('admin.login')->withErrors([
            'error' => 'Access denied. Admins only.',
        ]);
    }
}

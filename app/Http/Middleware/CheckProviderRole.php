<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProviderRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();
        
        if (!$user) {
            return redirect()->route('provider.login')->with('error', 'Please login to access this page.');
        }
        
        if (!$user->isProvider() && !$user->isAdmin()) {
            abort(403, 'Unauthorized. Provider access required.');
        }
        
        return $next($request);
    }
}

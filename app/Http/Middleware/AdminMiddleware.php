<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->isAdmin()) {
            // Redirect to appropriate dashboard based on user role
            $user = Auth::user();
            if ($user->isManager()) {
                return redirect()->route('manager.dashboard')->with('error', 'You do not have admin access.');
            } else {
                return redirect()->route('employee.dashboard')->with('error', 'You do not have admin access.');
            }
        }

        return $next($request);
    }
}

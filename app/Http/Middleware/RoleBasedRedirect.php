<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleBasedRedirect
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect('/admin');
            } elseif ($user->hasRole('staff')) {
                return redirect('/staff');
            } elseif ($user->hasRole('buyer')) {
                return redirect('/buyer');
            } elseif ($user->hasRole('seller')) {
                return redirect('/seller');
            } elseif ($user->hasRole('tenant')) {
                return redirect('/tenant');
            } elseif ($user->hasRole('landlord')) {
                return redirect('/landlord');
            } elseif ($user->hasRole('contractor')) {
                return redirect('/contractor');
            }
            // If user has 'free' role or no specific role, allow them to access /app
            return $next($request);
        }

        return $next($request);
    }
}
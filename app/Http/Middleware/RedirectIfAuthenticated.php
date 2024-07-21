<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;
    
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
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
                // If user has 'free' role or no specific role, redirect to default home
                return redirect(RouteServiceProvider::HOME);
            }
        }
    
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleBasedRedirect
{
    protected $roleRedirects = [
        'admin' => '/admin',
        'staff' => '/staff',
        'buyer' => '/buyer',
        'seller' => '/seller',
        'tenant' => '/tenant',
        'landlord' => '/landlord',
        'contractor' => '/contractor',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            foreach ($this->roleRedirects as $role => $redirect) {
                if ($user->hasRole($role)) {
                    return redirect($redirect);
                }
            }
            // If user has 'free' role or no specific role, allow them to access /app
            if ($user->hasRole('free') || $user->roles->isEmpty()) {
                return $next($request);
            }
            // If user doesn't have any recognized role, redirect to a default page
            return redirect('/dashboard');
        }
    
        // If not authenticated, redirect to login
        return redirect()->route('login');
    }
}

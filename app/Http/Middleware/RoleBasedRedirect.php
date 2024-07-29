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
                    $teamId = $user->currentTeam ? $user->currentTeam->id : 1;
                    $redirect = str_replace('{team}', $teamId, $redirect);
                    if ($request->is($redirect) || $request->is($redirect . '/*')) {
                        return $next($request);
                    }
                    return redirect($redirect);
                }
            }
            // If user has a role not in $roleRedirects, redirect to /{role}
            $userRoles = $user->getRoleNames();
            if ($userRoles->isNotEmpty()) {
                $firstRole = $userRoles->first();
                $roleRedirect = '/' . $firstRole;
                if ($request->is($roleRedirect) || $request->is($roleRedirect . '/*')) {
                    return $next($request);
                }
                return redirect($roleRedirect);
            }
            // If user has no roles, allow them to access the requested page
            return $next($request);
        }
            return $next($request);
        // If not authenticated, redirect to login
//        return redirect()->route('login');
    }
}

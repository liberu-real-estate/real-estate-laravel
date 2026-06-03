<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleBasedRedirect
{
    protected array $roleRedirects = [
        'super_admin' => 'admin',
        'admin'       => 'admin',
        'staff'       => 'staff',
        'agent'       => 'agent',
        'buyer'       => 'buyer',
        'seller'      => 'seller',
        'landlord'    => 'landlord',
        'tenant'      => 'tenant',
        'contractor'  => 'contractor',
    ];

    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $next($request);
        }

        if ($this->isInTenantContext($request) || $request->is('app') || $request->is('app/*')) {
            return $next($request);
        }

        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        foreach ($this->roleRedirects as $role => $redirect) {
            if ($user->hasRole($role)) {
                if ($request->is($redirect) || $request->is($redirect.'/*')) {
                    return $next($request);
                }
                return redirect($redirect);
            }
        }

        // Fallback: redirect to /{firstRole} for roles not in the map
        $firstRole = $user->getRoleNames()->first();
        if ($firstRole) {
            if ($request->is($firstRole) || $request->is($firstRole.'/*')) {
                return $next($request);
            }
            return redirect($firstRole);
        }

        return $next($request);
    }

    protected function isInTenantContext(Request $request): bool
    {
        return $request->segment(1) === 'tenant' || $request->is('tenant/*');
    }
}

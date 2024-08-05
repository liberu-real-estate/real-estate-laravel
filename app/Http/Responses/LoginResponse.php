<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
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

    protected function shouldRedirect(Request $request, $redirect)
    {
        // Check if the current request path matches the redirect path
        return !$request->is($redirect) && !$request->is($redirect . '/*');
    }

    public function toResponse($request)
    {
        $user = Auth::user();

        foreach ($this->roleRedirects as $role => $redirect) {
            if ($user->hasRole($role)) {
                return $request->wantsJson()
                    ? new JsonResponse(['two_factor' => false], 200)
                    : ($this->shouldRedirect($request, $redirect)
                        ? redirect()->to($redirect)
                        : redirect()->intended($redirect));
            }
        }

        // If user has a role not in $roleRedirects, redirect to /{role}
        $userRoles = $user->getRoleNames();
        if ($userRoles->isNotEmpty()) {
            $firstRole = $userRoles->first();
            $roleRedirect = '/' . $firstRole;
            return $request->wantsJson()
                ? new JsonResponse(['two_factor' => false], 200)
                : ($this->shouldRedirect($request, $roleRedirect)
                    ? redirect()->to($roleRedirect)
                    : redirect()->intended($roleRedirect));
        }

        // Default redirection
        return $request->wantsJson()
            ? new JsonResponse(['two_factor' => false], 200)
            : redirect()->intended('/app');
    }
}

<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\JsonResponse;
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

    public function toResponse($request)
    {
        $user = Auth::user();
        foreach ($this->roleRedirects as $role => $redirect) {
            if ($user->hasRole($role)) {
                return $request->wantsJson()
                            ? new JsonResponse(['two_factor' => false], 200)
                            : redirect()->intended($redirect);
            }
        }

        // If user has a role not in $roleRedirects, redirect to /{role}
        $userRoles = $user->getRoleNames();
        if ($userRoles->isNotEmpty()) {
            $firstRole = $userRoles->first();
            $roleRedirect = '/' . $firstRole;
            return $request->wantsJson()
                        ? new JsonResponse(['two_factor' => false], 200)
                        : redirect()->intended($roleRedirect);
        }

        // Default redirection
        return $request->wantsJson()
                    ? new JsonResponse(['two_factor' => false], 200)
                    : redirect()->intended('/app');
    }
}

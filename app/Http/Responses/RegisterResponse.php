<?php 

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class RegisterResponse implements RegisterResponseContract
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

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // Check if the user has a role and redirect accordingly
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

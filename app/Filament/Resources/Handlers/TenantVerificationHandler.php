<?php

namespace App\Filament\Resources\Handlers;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class TenantVerificationHandler
{
    public static function handle(array $data): void
    {
        $response = Password::broker('tenants')->reset(
            $data,
            function ($user, $token) {
                $user->email_verified_at = now();
                $user->save();
            }
        );

        if ($response !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }
    }
}

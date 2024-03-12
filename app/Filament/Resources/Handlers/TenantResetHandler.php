<?php

namespace App\Filament\Resources\Handlers;

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class TenantResetHandler
{
    public static function handle(array $data): void
    {
        $status = Password::sendResetLink($data);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [trans($status)],
            ]);
        }
    }
}

<?php

namespace App\Filament\Resources\Handlers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TenantLoginHandler
{
    public static function handle(array $data): void
    {
        if (!Auth::attempt($data)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }
    }
}

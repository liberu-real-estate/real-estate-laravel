<?php

namespace App\Filament\Resources\Handlers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TenantRegisterHandler
{
    public static function handle(array $data): void
    {
        $existingTenant = Tenant::where('email', $data['email'])->first();
        if ($existingTenant) {
            throw ValidationException::withMessages([
                'email' => ['The email address is already registered.'],
            ]);
        }

        $tenant = new Tenant([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (!$tenant->save()) {
            throw new \Exception('Failed to register the tenant.');
        }
    }
}

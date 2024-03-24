<?php

namespace App\Http\Controllers\Landlord;

use App\Filament\Resources\Handlers\TenantLoginHandler;
use App\Filament\Resources\Handlers\TenantRegisterHandler;
use App\Filament\Resources\Handlers\TenantResetHandler;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        TenantLoginHandler::handle($request->only('email', 'password'));
        // Redirect or return response after successful login
    }

    public function register(Request $request)
    {
        TenantRegisterHandler::handle($request->all());
        // Redirect or return response after successful registration
    }

    public function resetPassword(Request $request)
    {
        TenantResetHandler::handle($request->only('email'));
        // Redirect or return response after successful password reset request
    }
}

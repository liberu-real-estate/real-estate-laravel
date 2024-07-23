<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Staff\Resources\TenantResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class TenantResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleLoginSuccess()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        TenantResource::handleLogin(['email' => 'test@example.com', 'password' => 'password']);

        $this->assertAuthenticatedAs($user);
    }

    public function testHandleLoginFailure()
    {
        $this->expectException(ValidationException::class);

        TenantResource::handleLogin(['email' => 'nonexistent@example.com', 'password' => 'wrongpassword']);
    }

    public function testHandleRegister()
    {
        // Assuming handleRegister logic is implemented
        $userData = [
            'email' => 'newuser@example.com',
            'password' => bcrypt('password'),
        ];

        TenantResource::handleRegister($userData);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function testHandleVerification()
    {
        // Assuming handleVerification logic is implemented
        $user = User::factory()->create([
            'email' => 'verify@example.com',
            'email_verified_at' => null,
        ]);

        TenantResource::handleVerification(['email' => 'verify@example.com']);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function testHandleReset()
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        $status = TenantResource::handleReset(['email' => 'reset@example.com']);

        $this->assertEquals(Password::RESET_LINK_SENT, $status);
    }
}

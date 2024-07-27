<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\TenantResource\Pages\Login;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginSuccess()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/app/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/app');
        $this->assertAuthenticatedAs($user);
    }

    public function testLoginFailure()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/app/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302); // Filament typically redirects back with errors
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
        $this->assertGuest();
    }
}
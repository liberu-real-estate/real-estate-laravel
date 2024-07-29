<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Actions\Fortify\CreateNewUserWithTeams;
use App\Models\User;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $action = new CreateNewUserWithTeams();
        
        $user = $action->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNotNull($user->password);
        
        // Check team association
        $this->assertInstanceOf(Team::class, $user->currentTeam);
        $this->assertCount(1, $user->ownedTeams);
    }
}
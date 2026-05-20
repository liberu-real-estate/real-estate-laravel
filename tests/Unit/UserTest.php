<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_user_relationships()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->properties);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->teams);
    }

    public function test_user_has_teams()
    {
        $user = User::factory()->create();
        $team = $user->ownedTeams()->create(['name' => 'Test Team']);

        $this->assertTrue($user->hasTeamRole($team, 'owner'));
        $this->assertTrue($user->ownsTeam($team));
    }
}
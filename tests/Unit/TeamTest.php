<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_team()
    {
        $teamData = [
            'name' => 'Test Team',
            'personal_team' => false,
        ];

        $team = Team::create($teamData);

        $this->assertInstanceOf(Team::class, $team);
        $this->assertDatabaseHas('teams', ['name' => 'Test Team']);
    }

    public function test_team_relationships()
    {
        $team = Team::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $team->users);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $team->properties);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $team->branches);
    }

    public function test_team_owner()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $team->owner->id);
    }
}
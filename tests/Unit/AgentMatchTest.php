<?php

namespace Tests\Unit;

use App\Models\AgentMatch;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentMatchTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $agent;
    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->team = Team::create([
            'name' => 'Test Team',
            'user_id' => 1,
            'personal_team' => false,
        ]);
        
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        
        $this->agent = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $this->agent->assignRole('agent');
    }

    /** @test */
    public function it_can_create_an_agent_match()
    {
        $match = AgentMatch::create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'match_score' => 85.5,
            'expertise_score' => 80.0,
            'performance_score' => 90.0,
            'availability_score' => 85.0,
            'location_score' => 88.0,
            'specialization_score' => 82.0,
            'match_reasons' => ['Highly experienced', 'Great reviews'],
            'auto_generated' => true,
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(AgentMatch::class, $match);
        $this->assertEquals(85.5, $match->match_score);
        $this->assertEquals('pending', $match->status);
        $this->assertTrue($match->auto_generated);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $match = AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
        ]);

        $this->assertInstanceOf(User::class, $match->user);
        $this->assertEquals($this->user->id, $match->user->id);
    }

    /** @test */
    public function it_belongs_to_an_agent()
    {
        $match = AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
        ]);

        $this->assertInstanceOf(User::class, $match->agent);
        $this->assertEquals($this->agent->id, $match->agent->id);
    }

    /** @test */
    public function it_belongs_to_a_team()
    {
        $match = AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
        ]);

        $this->assertInstanceOf(Team::class, $match->team);
        $this->assertEquals($this->team->id, $match->team->id);
    }

    /** @test */
    public function it_casts_match_reasons_to_array()
    {
        $reasons = ['Great location knowledge', 'Excellent reviews'];
        
        $match = AgentMatch::create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'match_score' => 75.0,
            'match_reasons' => $reasons,
        ]);

        $this->assertIsArray($match->match_reasons);
        $this->assertEquals($reasons, $match->match_reasons);
    }

    /** @test */
    public function it_can_be_accepted()
    {
        $match = AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        $result = $match->accept();

        $this->assertTrue($result);
        $this->assertEquals('accepted', $match->fresh()->status);
    }

    /** @test */
    public function it_can_be_rejected()
    {
        $match = AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        $result = $match->reject();

        $this->assertTrue($result);
        $this->assertEquals('rejected', $match->fresh()->status);
    }

    /** @test */
    public function it_can_scope_pending_matches()
    {
        AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);
        
        AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'accepted',
        ]);

        $pendingMatches = AgentMatch::pending()->get();

        $this->assertCount(1, $pendingMatches);
        $this->assertEquals('pending', $pendingMatches->first()->status);
    }

    /** @test */
    public function it_can_scope_accepted_matches()
    {
        AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);
        
        AgentMatch::factory()->create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'status' => 'accepted',
        ]);

        $acceptedMatches = AgentMatch::accepted()->get();

        $this->assertCount(1, $acceptedMatches);
        $this->assertEquals('accepted', $acceptedMatches->first()->status);
    }

    /** @test */
    public function it_enforces_unique_user_agent_combination()
    {
        AgentMatch::create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'match_score' => 75.0,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        AgentMatch::create([
            'user_id' => $this->user->id,
            'agent_id' => $this->agent->id,
            'team_id' => $this->team->id,
            'match_score' => 80.0,
        ]);
    }
}

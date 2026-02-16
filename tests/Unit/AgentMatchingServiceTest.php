<?php

namespace Tests\Unit;

use App\Models\AgentMatch;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\Review;
use App\Models\Team;
use App\Models\User;
use App\Services\AgentMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentMatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    private AgentMatchingService $service;
    private User $user;
    private User $agent1;
    private User $agent2;
    private Team $team;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = new AgentMatchingService();
        
        // Create a team
        $this->team = Team::create([
            'name' => 'Test Real Estate Team',
            'user_id' => 1,
            'personal_team' => false,
        ]);
        
        // Create a regular user
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
            'agent_preferences' => [
                'location' => 'New York',
                'property_type' => 'apartment',
                'postal_code' => '10001',
            ],
        ]);
        
        // Create agent users
        $this->agent1 = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $this->agent1->assignRole('agent');
        
        $this->agent2 = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $this->agent2->assignRole('agent');
    }

    /** @test */
    public function it_can_calculate_expertise_score_for_experienced_agent()
    {
        // Create properties for agent1
        Property::factory()->count(5)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'sold',
        ]);
        
        Property::factory()->count(3)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'available',
        ]);
        
        $scores = $this->service->calculateMatchScore($this->user, $this->agent1);
        
        $this->assertGreaterThan(50, $scores['expertise_score']);
        $this->assertArrayHasKey('match_score', $scores);
    }

    /** @test */
    public function it_can_calculate_performance_score_based_on_reviews()
    {
        // Create reviews for agent1
        Review::factory()->count(5)->create([
            'reviewable_id' => $this->agent1->id,
            'reviewable_type' => User::class,
            'rating' => 5,
        ]);
        
        $scores = $this->service->calculateMatchScore($this->user, $this->agent1);
        
        $this->assertGreaterThan(70, $scores['performance_score']);
    }

    /** @test */
    public function it_can_calculate_availability_score_based_on_workload()
    {
        // Agent with few active listings should have higher availability
        Property::factory()->count(2)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'available',
        ]);
        
        // Agent with many active listings should have lower availability
        Property::factory()->count(15)->create([
            'user_id' => $this->agent2->id,
            'team_id' => $this->team->id,
            'status' => 'available',
        ]);
        
        $scores1 = $this->service->calculateMatchScore($this->user, $this->agent1);
        $scores2 = $this->service->calculateMatchScore($this->user, $this->agent2);
        
        $this->assertGreaterThan($scores2['availability_score'], $scores1['availability_score']);
    }

    /** @test */
    public function it_can_calculate_location_score_based_on_agent_properties()
    {
        // Create properties in user's preferred location
        Property::factory()->count(5)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'location' => 'New York, NY',
            'postal_code' => '10001',
        ]);
        
        // Create properties in different location
        Property::factory()->count(5)->create([
            'user_id' => $this->agent2->id,
            'team_id' => $this->team->id,
            'location' => 'Los Angeles, CA',
            'postal_code' => '90001',
        ]);
        
        $scores1 = $this->service->calculateMatchScore($this->user, $this->agent1, $this->user->agent_preferences);
        $scores2 = $this->service->calculateMatchScore($this->user, $this->agent2, $this->user->agent_preferences);
        
        $this->assertGreaterThan($scores2['location_score'], $scores1['location_score']);
    }

    /** @test */
    public function it_can_calculate_specialization_score_based_on_property_type()
    {
        // Agent specialized in apartments
        Property::factory()->count(8)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'property_type' => 'apartment',
        ]);
        
        // Agent specialized in houses
        Property::factory()->count(8)->create([
            'user_id' => $this->agent2->id,
            'team_id' => $this->team->id,
            'property_type' => 'house',
        ]);
        
        $scores1 = $this->service->calculateMatchScore($this->user, $this->agent1, $this->user->agent_preferences);
        $scores2 = $this->service->calculateMatchScore($this->user, $this->agent2, $this->user->agent_preferences);
        
        $this->assertGreaterThan($scores2['specialization_score'], $scores1['specialization_score']);
    }

    /** @test */
    public function it_can_find_best_matching_agents()
    {
        // Setup agent1 as highly qualified
        Property::factory()->count(5)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'sold',
            'location' => 'New York, NY',
            'property_type' => 'apartment',
        ]);
        
        Review::factory()->count(5)->create([
            'reviewable_id' => $this->agent1->id,
            'reviewable_type' => User::class,
            'rating' => 5,
        ]);
        
        $matches = $this->service->findMatches($this->user, 5);
        
        $this->assertNotEmpty($matches);
        $this->assertTrue($matches->first()->match_score > 0);
    }

    /** @test */
    public function it_can_create_agent_match_record()
    {
        $scores = $this->service->calculateMatchScore($this->user, $this->agent1);
        $match = $this->service->createMatch($this->user, $this->agent1, $scores);
        
        $this->assertInstanceOf(AgentMatch::class, $match);
        $this->assertEquals($this->user->id, $match->user_id);
        $this->assertEquals($this->agent1->id, $match->agent_id);
        $this->assertEquals($scores['match_score'], $match->match_score);
        $this->assertTrue($match->auto_generated);
    }

    /** @test */
    public function it_can_update_existing_match_record()
    {
        $scores1 = $this->service->calculateMatchScore($this->user, $this->agent1);
        $match1 = $this->service->createMatch($this->user, $this->agent1, $scores1);
        
        // Create another match with same user and agent
        $scores2 = $this->service->calculateMatchScore($this->user, $this->agent1);
        $match2 = $this->service->createMatch($this->user, $this->agent1, $scores2);
        
        $this->assertEquals($match1->id, $match2->id);
        $this->assertEquals(1, AgentMatch::where('user_id', $this->user->id)
            ->where('agent_id', $this->agent1->id)
            ->count());
    }

    /** @test */
    public function it_can_generate_matches_for_user()
    {
        // Setup multiple agents
        Property::factory()->count(5)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'sold',
        ]);
        
        Review::factory()->count(3)->create([
            'reviewable_id' => $this->agent1->id,
            'reviewable_type' => User::class,
            'rating' => 4,
        ]);
        
        $matches = $this->service->generateMatchesForUser($this->user, 0);
        
        $this->assertGreaterThan(0, $matches->count());
        $this->assertInstanceOf(AgentMatch::class, $matches->first());
    }

    /** @test */
    public function it_only_creates_matches_above_minimum_score()
    {
        $minScore = 80;
        
        // Create a moderately qualified agent (should have score < 80)
        Property::factory()->count(2)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
        ]);
        
        $matches = $this->service->generateMatchesForUser($this->user, $minScore);
        
        // Should not create match if score is below threshold
        $this->assertCount(0, $matches->filter(function ($match) use ($minScore) {
            return $match->match_score < $minScore;
        }));
    }

    /** @test */
    public function it_can_get_recommended_agents_for_property_search()
    {
        Property::factory()->count(5)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'location' => 'Boston, MA',
            'property_type' => 'house',
        ]);
        
        $searchContext = [
            'location' => 'Boston',
            'property_type' => 'house',
        ];
        
        $agents = $this->service->getRecommendedAgentsForPropertySearch($this->user, $searchContext);
        
        $this->assertNotEmpty($agents);
        $this->assertLessThanOrEqual(3, $agents->count());
    }

    /** @test */
    public function it_generates_appropriate_match_reasons()
    {
        // Create a highly qualified agent
        Property::factory()->count(10)->create([
            'user_id' => $this->agent1->id,
            'team_id' => $this->team->id,
            'status' => 'sold',
        ]);
        
        Review::factory()->count(10)->create([
            'reviewable_id' => $this->agent1->id,
            'reviewable_type' => User::class,
            'rating' => 5,
        ]);
        
        $scores = $this->service->calculateMatchScore($this->user, $this->agent1);
        
        $this->assertArrayHasKey('match_reasons', $scores);
        $this->assertIsArray($scores['match_reasons']);
        $this->assertNotEmpty($scores['match_reasons']);
    }

    /** @test */
    public function it_returns_base_scores_for_new_agents()
    {
        // Agent with no properties or reviews
        $newAgent = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
        $newAgent->assignRole('agent');
        
        $scores = $this->service->calculateMatchScore($this->user, $newAgent);
        
        // Base scores should be around 50
        $this->assertEquals(50, $scores['expertise_score']);
        $this->assertEquals(50, $scores['performance_score']);
    }

    /** @test */
    public function it_filters_agents_by_team()
    {
        $otherTeam = Team::create([
            'name' => 'Other Team',
            'user_id' => 1,
            'personal_team' => false,
        ]);
        
        $otherAgent = User::factory()->create([
            'current_team_id' => $otherTeam->id,
        ]);
        $otherAgent->assignRole('agent');
        
        $matches = $this->service->findMatches($this->user, 10);
        
        // Should not include agents from other teams
        $this->assertFalse($matches->contains('id', $otherAgent->id));
    }
}

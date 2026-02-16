<?php

namespace App\Services;

use App\Models\AgentMatch;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AgentMatchingService
{
    /**
     * Find the best matching agents for a user based on their needs
     *
     * @param User $user The user looking for an agent
     * @param int $limit Maximum number of agents to return
     * @return Collection Collection of agents with match scores
     */
    public function findMatches(User $user, int $limit = 5): Collection
    {
        $preferences = $user->agent_preferences ?? [];
        
        // Get agents from the user's team or all teams if no team
        $query = User::role('agent');
        
        if ($user->currentTeam) {
            $query->where('current_team_id', $user->currentTeam->id);
        }
        
        $agents = $query->with(['properties', 'reviews', 'appointments'])->get();
        
        // Calculate match scores for each agent
        $scoredAgents = $agents->map(function ($agent) use ($user, $preferences) {
            $scores = $this->calculateMatchScore($user, $agent, $preferences);
            $agent->match_score = $scores['match_score'];
            $agent->match_details = $scores;
            return $agent;
        })->sortByDesc('match_score')->take($limit);
        
        return $scoredAgents;
    }

    /**
     * Calculate match score between a user and an agent
     *
     * @param User $user The user
     * @param User $agent The agent
     * @param array $preferences User's agent preferences
     * @return array Array of score components and overall match score
     */
    public function calculateMatchScore(User $user, User $agent, array $preferences = []): array
    {
        $scores = [];
        
        // Expertise score (25% weight) - based on properties sold and active listings
        $scores['expertise_score'] = $this->calculateExpertiseScore($agent);
        
        // Performance score (25% weight) - based on ratings and reviews
        $scores['performance_score'] = $this->calculatePerformanceScore($agent);
        
        // Availability score (20% weight) - based on current workload
        $scores['availability_score'] = $this->calculateAvailabilityScore($agent);
        
        // Location score (15% weight) - based on property locations agent handles
        $scores['location_score'] = $this->calculateLocationScore($agent, $preferences);
        
        // Specialization score (15% weight) - based on property types
        $scores['specialization_score'] = $this->calculateSpecializationScore($agent, $preferences);
        
        // Calculate overall score
        $overallScore = (
            $scores['expertise_score'] * 0.25 +
            $scores['performance_score'] * 0.25 +
            $scores['availability_score'] * 0.20 +
            $scores['location_score'] * 0.15 +
            $scores['specialization_score'] * 0.15
        );
        
        // Generate match reasons
        $matchReasons = $this->generateMatchReasons($scores, $agent);
        
        return [
            'match_score' => round($overallScore, 2),
            'expertise_score' => round($scores['expertise_score'], 2),
            'performance_score' => round($scores['performance_score'], 2),
            'availability_score' => round($scores['availability_score'], 2),
            'location_score' => round($scores['location_score'], 2),
            'specialization_score' => round($scores['specialization_score'], 2),
            'match_reasons' => $matchReasons,
        ];
    }

    /**
     * Create or update an agent match record
     *
     * @param User $user The user
     * @param User $agent The agent
     * @param array $scores The calculated scores
     * @return AgentMatch
     */
    public function createMatch(User $user, User $agent, array $scores): AgentMatch
    {
        // Check if match already exists
        $existingMatch = AgentMatch::where('user_id', $user->id)
            ->where('agent_id', $agent->id)
            ->first();
        
        if ($existingMatch) {
            $existingMatch->update($scores);
            return $existingMatch;
        }
        
        return AgentMatch::create(array_merge([
            'user_id' => $user->id,
            'agent_id' => $agent->id,
            'team_id' => $user->current_team_id ?? 1,
            'auto_generated' => true,
        ], $scores));
    }

    /**
     * Generate matches for a user and store them in the database
     *
     * @param User $user
     * @param int $minScore Minimum match score to create a record (0-100)
     * @return Collection
     */
    public function generateMatchesForUser(User $user, int $minScore = 60): Collection
    {
        $agents = $this->findMatches($user, 10);
        $matches = collect();
        
        foreach ($agents as $agent) {
            $scores = $this->calculateMatchScore($user, $agent, $user->agent_preferences ?? []);
            
            // Only create matches with score >= minScore
            if ($scores['match_score'] >= $minScore) {
                $match = $this->createMatch($user, $agent, $scores);
                $matches->push($match);
            }
        }
        
        return $matches;
    }

    /**
     * Get recommended agents for a user based on property search context
     *
     * @param User $user
     * @param array $searchContext Property search criteria
     * @return Collection
     */
    public function getRecommendedAgentsForPropertySearch(User $user, array $searchContext = []): Collection
    {
        // Temporarily update user preferences with search context
        $preferences = array_merge($user->agent_preferences ?? [], $searchContext);
        
        $agents = User::role('agent');
        
        if ($user->currentTeam) {
            $agents->where('current_team_id', $user->currentTeam->id);
        }
        
        $agents = $agents->with(['properties', 'reviews'])->get();
        
        // Score agents based on the search context
        return $agents->map(function ($agent) use ($user, $preferences) {
            $scores = $this->calculateMatchScore($user, $agent, $preferences);
            $agent->match_score = $scores['match_score'];
            $agent->match_details = $scores;
            return $agent;
        })->sortByDesc('match_score')->take(3);
    }

    /**
     * Calculate expertise score based on agent's track record
     */
    private function calculateExpertiseScore(User $agent): float
    {
        $totalProperties = $agent->properties()->count();
        $soldProperties = $agent->properties()->where('status', 'sold')->count();
        
        if ($totalProperties === 0) {
            return 50; // Base score for new agents
        }
        
        // Score based on experience and success rate
        $experienceScore = min(50, $totalProperties * 2); // Cap at 50
        $successRate = $soldProperties / $totalProperties;
        $successScore = $successRate * 50;
        
        return min(100, $experienceScore + $successScore);
    }

    /**
     * Calculate performance score based on reviews and ratings
     */
    private function calculatePerformanceScore(User $agent): float
    {
        $averageRating = $agent->reviews()->avg('rating');
        $reviewCount = $agent->reviews()->count();
        
        if (!$averageRating || $reviewCount === 0) {
            return 50; // Base score for agents without reviews
        }
        
        // Convert 5-star rating to 100-point scale
        $ratingScore = ($averageRating / 5) * 80;
        
        // Bonus for having more reviews (credibility)
        $reviewBonus = min(20, $reviewCount * 2);
        
        return min(100, $ratingScore + $reviewBonus);
    }

    /**
     * Calculate availability score based on current workload
     */
    private function calculateAvailabilityScore(User $agent): float
    {
        $activeListings = $agent->properties()
            ->whereIn('status', ['available', 'pending'])
            ->count();
        
        $recentAppointments = $agent->appointments()
            ->where('start_time', '>=', now()->subDays(7))
            ->count();
        
        // Lower score for agents with high workload
        $workloadScore = max(0, 100 - ($activeListings * 3) - ($recentAppointments * 2));
        
        return max(30, $workloadScore); // Minimum 30% availability
    }

    /**
     * Calculate location score based on agent's property locations
     */
    private function calculateLocationScore(User $agent, array $preferences): float
    {
        if (!isset($preferences['location']) && !isset($preferences['postal_code'])) {
            return 50; // Neutral score if no location preference
        }
        
        $agentProperties = $agent->properties()->get();
        
        if ($agentProperties->isEmpty()) {
            return 50; // Neutral for agents without properties
        }
        
        $matchCount = 0;
        $totalProperties = $agentProperties->count();
        
        foreach ($agentProperties as $property) {
            $match = false;
            
            if (isset($preferences['location'])) {
                $searchLocation = strtolower($preferences['location']);
                $propertyLocation = strtolower($property->location);
                if (str_contains($propertyLocation, $searchLocation)) {
                    $match = true;
                }
            }
            
            if (isset($preferences['postal_code']) && 
                $property->postal_code === $preferences['postal_code']) {
                $match = true;
            }
            
            if ($match) {
                $matchCount++;
            }
        }
        
        $matchPercentage = ($matchCount / $totalProperties) * 100;
        
        return min(100, max(30, $matchPercentage + 20)); // Minimum 30%, bonus 20%
    }

    /**
     * Calculate specialization score based on property types
     */
    private function calculateSpecializationScore(User $agent, array $preferences): float
    {
        if (!isset($preferences['property_type'])) {
            return 50; // Neutral score if no type preference
        }
        
        $agentProperties = $agent->properties()->get();
        
        if ($agentProperties->isEmpty()) {
            return 50; // Neutral for agents without properties
        }
        
        $typeCount = $agentProperties->where('property_type', $preferences['property_type'])->count();
        $totalProperties = $agentProperties->count();
        
        $specializationPercentage = ($typeCount / $totalProperties) * 100;
        
        return min(100, max(30, $specializationPercentage + 20)); // Minimum 30%, bonus 20%
    }

    /**
     * Generate human-readable match reasons
     */
    private function generateMatchReasons(array $scores, User $agent): array
    {
        $reasons = [];
        
        if ($scores['expertise_score'] >= 75) {
            $reasons[] = 'Highly experienced with excellent track record';
        } elseif ($scores['expertise_score'] >= 60) {
            $reasons[] = 'Good experience in real estate';
        }
        
        if ($scores['performance_score'] >= 75) {
            $reasons[] = 'Highly rated by previous clients';
        } elseif ($scores['performance_score'] >= 60) {
            $reasons[] = 'Good client reviews';
        }
        
        if ($scores['availability_score'] >= 70) {
            $reasons[] = 'Currently available to take on new clients';
        }
        
        if ($scores['location_score'] >= 70) {
            $reasons[] = 'Specializes in your preferred area';
        }
        
        if ($scores['specialization_score'] >= 70) {
            $reasons[] = 'Experienced with your property type';
        }
        
        if (empty($reasons)) {
            $reasons[] = 'Available agent in your area';
        }
        
        return $reasons;
    }
}

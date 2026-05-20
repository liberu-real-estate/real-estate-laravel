# Agent Matching Service Documentation

## Overview

The Agent Matching Service is a sophisticated feature that intelligently matches users with real estate agents based on their specific needs, preferences, and property search context. The service uses a multi-factor scoring algorithm to recommend the most suitable agents.

## Features

### 1. Intelligent Matching Algorithm

The service evaluates agents across five key dimensions:

- **Expertise (25% weight)**: Based on the agent's track record
  - Total number of properties handled
  - Success rate (sold properties vs total listings)
  - Experience level

- **Performance (25% weight)**: Based on client satisfaction
  - Average rating from reviews
  - Number of reviews (credibility factor)
  - Client feedback

- **Availability (20% weight)**: Based on current workload
  - Number of active listings
  - Recent appointments
  - Current capacity

- **Location (15% weight)**: Based on area expertise
  - Properties handled in user's preferred location
  - Postal code matches
  - Geographic specialization

- **Specialization (15% weight)**: Based on property type expertise
  - Experience with specific property types
  - Portfolio composition
  - Niche expertise

### 2. Context-Aware Recommendations

The service provides different recommendation modes:

- **Profile-based**: Matches based on user's saved agent preferences
- **Search-based**: Recommends agents based on current property search criteria
- **Hybrid**: Combines both profile and search context for optimal results

### 3. Match Management

- **Status tracking**: pending, accepted, rejected
- **Match reasons**: Human-readable explanations for recommendations
- **Score breakdown**: Detailed scoring for transparency
- **Persistent storage**: Saves matches for future reference

## Installation

The service includes the following components:

### Database Migrations

Two migrations are included:

1. `add_agent_preferences_to_users_table.php` - Adds JSON column for user preferences
2. `create_agent_matches_table.php` - Creates table for storing match records

Run migrations:
```bash
php artisan migrate
```

### Models

- **AgentMatch**: Manages match records
- **User**: Extended with agent matching relationships

### Service Class

- **AgentMatchingService**: Core matching logic and algorithms

### UI Components

- **AgentRecommendations** (Livewire): Display component for agent recommendations
- **agent-recommendations.blade.php**: View template

## Usage

### Basic Usage

#### Get Recommended Agents for a User

```php
use App\Services\AgentMatchingService;

$service = new AgentMatchingService();
$user = Auth::user();

// Get top 5 matching agents
$matches = $service->findMatches($user, 5);

foreach ($matches as $agent) {
    echo "Agent: {$agent->name}";
    echo "Match Score: {$agent->match_score}%";
    echo "Reasons: " . implode(', ', $agent->match_details['match_reasons']);
}
```

#### Generate and Save Matches

```php
$service = new AgentMatchingService();
$user = Auth::user();

// Generate matches with minimum score of 60%
$matches = $service->generateMatchesForUser($user, 60);

// Access saved matches
$savedMatches = $user->agentMatches()->pending()->get();
```

#### Context-Aware Recommendations

```php
$searchContext = [
    'location' => 'New York',
    'property_type' => 'apartment',
    'postal_code' => '10001'
];

$agents = $service->getRecommendedAgentsForPropertySearch($user, $searchContext);
```

### User Model Helper Methods

The User model includes convenient helper methods:

```php
// Get recommended agents
$agents = $user->getRecommendedAgents(5);

// Generate matches
$matches = $user->generateAgentMatches(60);

// Get agents for property search
$agents = $user->getAgentsForPropertySearch($searchContext);

// Access relationships
$matchedAgents = $user->matchedAgents;
$agentMatches = $user->agentMatches()->accepted()->get();
```

### Livewire Component

Use in Blade views:

```blade
{{-- General recommendations --}}
<livewire:agent-recommendations />

{{-- With search context --}}
<livewire:agent-recommendations :searchContext="['location' => 'Boston', 'property_type' => 'house']" />
```

### Setting User Preferences

```php
$user->agent_preferences = [
    'location' => 'New York',
    'property_type' => 'apartment',
    'postal_code' => '10001',
    'min_price' => 500000,
    'max_price' => 1000000
];
$user->save();
```

## API Reference

### AgentMatchingService Methods

#### findMatches(User $user, int $limit = 5): Collection

Finds the best matching agents for a user.

**Parameters:**
- `$user`: The user looking for an agent
- `$limit`: Maximum number of agents to return (default: 5)

**Returns:** Collection of User models with match_score and match_details attributes

#### calculateMatchScore(User $user, User $agent, array $preferences = []): array

Calculates match score between a user and an agent.

**Returns:** Array with score components:
```php
[
    'match_score' => 85.5,
    'expertise_score' => 90.0,
    'performance_score' => 85.0,
    'availability_score' => 80.0,
    'location_score' => 88.0,
    'specialization_score' => 84.0,
    'match_reasons' => ['Highly experienced', 'Great reviews']
]
```

#### createMatch(User $user, User $agent, array $scores): AgentMatch

Creates or updates a match record.

#### generateMatchesForUser(User $user, int $minScore = 60): Collection

Generates and saves matches for a user above the minimum score threshold.

#### getRecommendedAgentsForPropertySearch(User $user, array $searchContext = []): Collection

Gets agents recommended for a specific property search context.

### AgentMatch Model Methods

#### accept(): bool

Marks the match as accepted.

#### reject(): bool

Marks the match as rejected.

#### Scopes

- `pending()`: Get pending matches
- `accepted()`: Get accepted matches

## Database Schema

### agent_matches Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| agent_id | bigint | Foreign key to users (agent) |
| team_id | bigint | Foreign key to teams |
| match_score | decimal(5,2) | Overall match score |
| expertise_score | decimal(5,2) | Expertise component score |
| performance_score | decimal(5,2) | Performance component score |
| availability_score | decimal(5,2) | Availability component score |
| location_score | decimal(5,2) | Location component score |
| specialization_score | decimal(5,2) | Specialization component score |
| match_reasons | json | Array of match reasons |
| auto_generated | boolean | Whether match was auto-generated |
| status | string | pending, accepted, or rejected |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### users.agent_preferences Column

JSON column storing user preferences:

```json
{
    "location": "New York",
    "property_type": "apartment",
    "postal_code": "10001",
    "min_price": 500000,
    "max_price": 1000000,
    "min_bedrooms": 2,
    "max_bedrooms": 4,
    "required_features": ["parking", "gym"]
}
```

## Testing

Comprehensive test coverage is provided:

### Running Tests

```bash
# Run all agent matching tests
php artisan test --filter=AgentMatching

# Run specific test classes
php artisan test tests/Unit/AgentMatchingServiceTest.php
php artisan test tests/Unit/AgentMatchTest.php
```

### Test Coverage

- Expertise score calculation
- Performance score calculation based on reviews
- Availability score based on workload
- Location score matching
- Specialization score for property types
- Match creation and updates
- User helper methods
- Context-aware recommendations
- Team filtering
- Base scores for new agents

## Configuration

### Minimum Score Threshold

Adjust the minimum match score when generating matches:

```php
// Only create matches with 70%+ score
$matches = $service->generateMatchesForUser($user, 70);
```

### Result Limits

Control how many agents are returned:

```php
// Get top 10 agents
$agents = $service->findMatches($user, 10);
```

### Score Weights

The scoring weights are defined in the `calculateMatchScore` method and can be adjusted if needed:

```php
$overallScore = (
    $scores['expertise_score'] * 0.25 +      // 25%
    $scores['performance_score'] * 0.25 +    // 25%
    $scores['availability_score'] * 0.20 +   // 20%
    $scores['location_score'] * 0.15 +       // 15%
    $scores['specialization_score'] * 0.15   // 15%
);
```

## Best Practices

1. **Update user preferences regularly**: Ensure user preferences are kept up-to-date for accurate matching

2. **Generate matches periodically**: Run match generation for active users on a schedule

3. **Monitor match quality**: Track acceptance rates and user feedback

4. **Optimize agent data**: Ensure agents keep their profiles, properties, and areas of expertise current

5. **Handle new agents**: The system provides base scores for agents without track records

6. **Team-based filtering**: Matches respect team boundaries for multi-tenant setups

## Troubleshooting

### No matches found

- Verify agents exist with the 'agent' role
- Check that agents belong to the same team as the user
- Lower the minimum score threshold
- Ensure user preferences are set

### Low match scores

- Agents need properties and reviews to score well
- New agents receive base scores of 50%
- Consider adding more agent data (properties, reviews)

### Performance issues

- Use pagination for large agent lists
- Consider caching match results
- Run match generation in background jobs for batch processing

## Future Enhancements

Potential improvements for future versions:

1. **Machine learning**: Use ML to refine scoring based on successful matches
2. **Real-time availability**: Integrate with agent calendars
3. **Geolocation**: Use precise distance calculations
4. **Language preferences**: Match based on agent languages
5. **Client testimonials**: Weight recent reviews more heavily
6. **Communication style**: Match based on preferred communication methods
7. **Specialization tags**: More granular expertise categorization

## Support

For questions or issues:
- Check the test files for usage examples
- Review the service class documentation
- Consult the Livewire component for UI integration patterns

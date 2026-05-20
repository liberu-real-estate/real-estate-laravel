<?php

namespace Database\Factories;

use App\Models\AgentMatch;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgentMatch>
 */
class AgentMatchFactory extends Factory
{
    protected $model = AgentMatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'agent_id' => User::factory(),
            'team_id' => Team::factory(),
            'match_score' => $this->faker->randomFloat(2, 50, 100),
            'expertise_score' => $this->faker->randomFloat(2, 40, 100),
            'performance_score' => $this->faker->randomFloat(2, 40, 100),
            'availability_score' => $this->faker->randomFloat(2, 30, 100),
            'location_score' => $this->faker->randomFloat(2, 30, 100),
            'specialization_score' => $this->faker->randomFloat(2, 30, 100),
            'match_reasons' => [
                $this->faker->randomElement([
                    'Highly experienced with excellent track record',
                    'Good experience in real estate',
                    'Highly rated by previous clients',
                    'Currently available to take on new clients',
                    'Specializes in your preferred area',
                ]),
            ],
            'auto_generated' => true,
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }

    /**
     * Indicate that the match is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the match is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the match is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}

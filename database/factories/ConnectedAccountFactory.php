<?php

namespace Database\Factories;

use App\Models\ConnectedAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JoelButcher\Socialstream\Providers;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConnectedAccount>
 */
class ConnectedAccountFactory extends Factory
{
    protected $model = ConnectedAccount::class;

    public function definition(): array
    {
        return [
            'provider' => $this->faker->randomElement(Providers::all()),
            'provider_id' => $this->faker->numerify('########'),
            'token' => Str::random(432),
            'refresh_token' => Str::random(432),
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'expires_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}

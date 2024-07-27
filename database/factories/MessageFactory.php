<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'content' => $this->faker->paragraph,
            'read_at' => $this->faker->optional()->dateTimeThisMonth(),
            'team_id' => Team::factory(),
        ];
    }
}
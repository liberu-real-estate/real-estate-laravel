<?php

namespace Database\Factories;

use App\Models\SiteSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteSettingsFactory extends Factory
{
    protected $model = SiteSettings::class;

    public function definition(): array
    {
        return [
            'site_name' => $this->faker->company(),
            'site_description' => $this->faker->sentence(),
            'contact_email' => $this->faker->email(),
            'contact_phone' => $this->faker->phoneNumber(),
        ];
    }
}
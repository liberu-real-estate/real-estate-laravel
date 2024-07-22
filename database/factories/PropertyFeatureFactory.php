<?php

namespace Database\Factories;

use App\Models\PropertyFeature;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFeatureFactory extends Factory
{
    protected $model = PropertyFeature::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'feature' => $this->faker->randomElement(['Swimming Pool', 'Garden', 'Garage', 'Balcony', 'Fireplace']),
        ];
    }
}
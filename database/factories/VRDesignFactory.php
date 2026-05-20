<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use App\Models\VRDesign;
use Illuminate\Database\Eloquent\Factories\Factory;

class VRDesignFactory extends Factory
{
    protected $model = VRDesign::class;

    public function definition()
    {
        return [
            'property_id' => Property::factory(),
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'vr_provider' => 'mock',
            'design_data' => [
                'version' => '1.0',
                'created_via' => 'factory',
            ],
            'room_layout' => [
                'width' => $this->faker->numberBetween(8, 15),
                'height' => $this->faker->numberBetween(6, 10),
                'depth' => $this->faker->numberBetween(8, 15),
            ],
            'furniture_items' => [],
            'materials' => [
                'walls' => [
                    'color' => $this->faker->hexColor,
                    'texture' => $this->faker->randomElement(['smooth', 'rough', 'painted']),
                ],
                'floor' => [
                    'material' => $this->faker->randomElement(['wood', 'tile', 'carpet']),
                    'color' => $this->faker->hexColor,
                ],
            ],
            'lighting' => [
                'ambient' => [
                    'intensity' => $this->faker->randomFloat(2, 0.3, 1.0),
                    'color' => '#FFFFFF',
                ],
            ],
            'thumbnail_path' => null,
            'vr_scene_url' => null,
            'is_public' => $this->faker->boolean(30),
            'is_template' => false,
            'style' => $this->faker->randomElement(['modern', 'traditional', 'minimalist', 'luxury', 'industrial', 'scandinavian', 'contemporary', 'rustic']),
            'view_count' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Indicate that the design is public.
     */
    public function public()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_public' => true,
            ];
        });
    }

    /**
     * Indicate that the design is a template.
     */
    public function template()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_template' => true,
                'is_public' => true,
            ];
        });
    }

    /**
     * Indicate that the design has furniture items.
     */
    public function withFurniture()
    {
        return $this->state(function (array $attributes) {
            return [
                'furniture_items' => [
                    [
                        'id' => uniqid('furniture_'),
                        'category' => 'seating',
                        'type' => 'Sofa',
                        'position' => [0, 0, 0],
                        'rotation' => [0, 90, 0],
                        'scale' => [1, 1, 1],
                        'material' => ['color' => '#8B4513'],
                        'created_at' => now()->toIso8601String(),
                    ],
                    [
                        'id' => uniqid('furniture_'),
                        'category' => 'tables',
                        'type' => 'Coffee Table',
                        'position' => [2, 0, 1],
                        'rotation' => [0, 0, 0],
                        'scale' => [1, 1, 1],
                        'material' => ['color' => '#000000'],
                        'created_at' => now()->toIso8601String(),
                    ],
                ],
            ];
        });
    }
}

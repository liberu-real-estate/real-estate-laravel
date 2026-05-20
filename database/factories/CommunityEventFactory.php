<?php

namespace Database\Factories;

use App\Models\CommunityEvent;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityEventFactory extends Factory
{
    protected $model = CommunityEvent::class;

    public function definition(): array
    {
        $eventDate = $this->faker->dateTimeBetween('now', '+3 months');
        $endDate = clone $eventDate;
        $endDate->modify('+' . $this->faker->numberBetween(1, 4) . ' hours');

        $categories = ['community', 'festival', 'market', 'sports', 'concert', 'workshop', 'fundraiser', 'parade'];

        return [
            'title' => $this->faker->randomElement([
                'Community Farmers Market',
                'Summer Music Festival',
                'Local Art Exhibition',
                'Neighborhood Cleanup Day',
                'Food Truck Rally',
                'Outdoor Movie Night',
                'Charity Run',
                'Street Fair',
                'Community Gardening Workshop',
                'Holiday Parade',
            ]),
            'description' => $this->faker->paragraph(3),
            'event_date' => $eventDate,
            'end_date' => $endDate,
            'location' => $this->faker->streetAddress,
            'latitude' => $this->faker->latitude(51.0, 52.0),
            'longitude' => $this->faker->longitude(-1.0, 1.0),
            'category' => $this->faker->randomElement($categories),
            'organizer' => $this->faker->randomElement([
                'Community Center',
                'Local Chamber of Commerce',
                'Parks & Recreation',
                'Cultural Association',
                'Neighborhood Watch',
            ]),
            'contact_email' => $this->faker->email,
            'contact_phone' => $this->faker->phoneNumber,
            'website_url' => $this->faker->optional(0.6)->url,
            'is_public' => true,
            'property_id' => null, // Not associated with specific property by default
        ];
    }

    /**
     * Associate event with a property.
     */
    public function forProperty(Property $property): Factory
    {
        return $this->state(function (array $attributes) use ($property) {
            return [
                'property_id' => $property->id,
                'latitude' => $property->latitude,
                'longitude' => $property->longitude,
            ];
        });
    }
}

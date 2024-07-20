<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use Illuminate\Support\Carbon;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $properties = [
            [
                'title' => 'Luxurious Beachfront Villa',
                'description' => 'A stunning 5-bedroom villa with direct beach access and panoramic ocean views.',
                'location' => 'Malibu, CA',
                'price' => 5000000,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'area_sqft' => 4500,
                'year_built' => 2015,
                'property_type' => 'Villa',
                'status' => 'For Sale',
                'is_featured' => true,
                'list_date' => now(),
		'user_id' => 1,
            ],
            [
                'title' => 'Modern Downtown Apartment',
                'description' => 'Sleek 2-bedroom apartment in the heart of the city with state-of-the-art amenities.',
                'location' => 'New York, NY',
                'price' => 1200000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'area_sqft' => 1200,
                'year_built' => 2020,
                'property_type' => 'Apartment',
                'status' => 'For Sale',
                'is_featured' => true,
                'list_date' => now(),
		'user_id' => 1,
            ],
            [
                'title' => 'Charming Suburban Home',
                'description' => 'Cozy 3-bedroom family home with a large backyard and modern interiors.',
                'location' => 'Austin, TX',
                'price' => 450000,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 2000,
                'year_built' => 2010,
                'property_type' => 'Single Family Home',
                'status' => 'For Sale',
                'is_featured' => false,
                'list_date' => now(),
		'user_id' => 1,
            ],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}

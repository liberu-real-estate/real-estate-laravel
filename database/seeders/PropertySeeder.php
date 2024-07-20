<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use Illuminate\Support\Carbon;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $placeholderImages = [
            'property1.jpg',
            'property2.jpg',
            'property3.jpg',
            'property4.jpg',
            'property5.jpg',
            'property6.jpg',
            'property7.jpg',
            'property8.jpg',
            'property9.jpg',
            'property10.jpg',
        ];

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
                'image' => $placeholderImages[0],
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
                'image' => $placeholderImages[1],
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
                'image' => $placeholderImages[2],
            ],
        ];

        foreach ($properties as $index => $property) {
            $property['image'] = $placeholderImages[$index % count($placeholderImages)];
            Property::create($property);
        }
    }
}

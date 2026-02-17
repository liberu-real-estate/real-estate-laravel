<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\Neighborhood;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{

public function run()
{
    $faker = Faker::create();
    $salesCategory = PropertyCategory::where('name', 'sales')->first();
    $lettingsCategory = PropertyCategory::where('name', 'lettings')->first();
    $hmoCategory = PropertyCategory::where('name', 'hmo')->first();
    $this->createProperties($salesCategory, 10);
    $this->createProperties($lettingsCategory, 10);
    $this->createHmoProperties($hmoCategory, 5);
}
private function createProperties($category, $count)
{
    $faker = Faker::create();
    $neighborhoods = Neighborhood::all();
    
    // Sample virtual tour URLs for demo purposes
    $virtualTourUrls = [
        'https://my.matterport.com/show/?m=SxQL3iGyoDo', // Matterport example
        'https://kuula.co/share/collection/7l2nJ?logo=1&info=1&fs=1&vr=0&sd=1&thumbs=1', // Kuula example
        null, // No virtual tour
        null,
    ];
    
    $virtualTourProviders = ['matterport', 'kuula', null, null];
    
    // Sample 3D model URLs for demonstration
    $sample3DModels = [
        'https://threejs.org/examples/models/gltf/LittlestTokyo.glb',
        'https://raw.githubusercontent.com/KhronosGroup/glTF-Sample-Models/master/2.0/DamagedHelmet/glTF-Binary/DamagedHelmet.glb',
        null, // Some properties won't have 3D models
        null,
    ];
    
    for ($i = 0; $i < $count; $i++) {
        // Add virtual tours to about 30% of properties
        $hasVirtualTour = $faker->boolean(30);
        $tourIndex = $faker->numberBetween(0, 1);
        
        Property::create([
            'title' => $faker->sentence(4),
            'description' => $faker->paragraph(3),
            'location' => $faker->city . ', ' . $faker->stateAbbr,
            'price' => $faker->numberBetween(100000, 5000000),
            'bedrooms' => $faker->numberBetween(1, 6),
            'bathrooms' => $faker->numberBetween(1, 4),
            'area_sqft' => $faker->numberBetween(500, 5000),
            'year_built' => $faker->numberBetween(1950, 2023),
            'property_type' => $faker->randomElement(['Apartment', 'House', 'Villa', 'Condo']),
            'status' => $category->name === 'sales' ? 'For Sale' : 'For Rent',
            'is_featured' => $faker->boolean(20),
            'list_date' => $faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => 1,
            'property_category_id' => $category->id,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'postal_code' => $faker->postcode,
            'neighborhood_id' => $neighborhoods->count() > 0 ? $neighborhoods->random()->id : null,
            'virtual_tour_url' => $hasVirtualTour ? $virtualTourUrls[$tourIndex] : null,
            'virtual_tour_provider' => $hasVirtualTour ? $virtualTourProviders[$tourIndex] : null,
            'live_tour_available' => $hasVirtualTour ? $faker->boolean(60) : false,
            'model_3d_url' => $faker->randomElement($sample3DModels),
        ]);
    }
}
private function createHmoProperties($category, $count)
{
    $faker = Faker::create();
    $neighborhoods = Neighborhood::all();
    
    // Sample virtual tour URLs for demo purposes
    $virtualTourUrls = [
        'https://my.matterport.com/show/?m=SxQL3iGyoDo',
        'https://kuula.co/share/collection/7l2nJ?logo=1&info=1&fs=1&vr=0&sd=1&thumbs=1',
    ];
    
    $virtualTourProviders = ['matterport', 'kuula'];
    
    // Sample 3D model URLs for demonstration
    $sample3DModels = [
        'https://threejs.org/examples/models/gltf/LittlestTokyo.glb',
        null,
    ];
    
    for ($i = 0; $i < $count; $i++) {
        // HMO properties are more likely to have virtual tours (50%)
        $hasVirtualTour = $faker->boolean(50);
        $tourIndex = $faker->numberBetween(0, 1);
        
        $property = Property::create([
            'title' => 'HMO ' . $faker->sentence(3),
            'description' => $faker->paragraph(3),
            'location' => $faker->city . ', ' . $faker->stateAbbr,
            'price' => $faker->numberBetween(200000, 1000000),
            'bedrooms' => $faker->numberBetween(4, 10),
            'bathrooms' => $faker->numberBetween(2, 5),
            'area_sqft' => $faker->numberBetween(1000, 3000),
            'year_built' => $faker->numberBetween(1950, 2023),
            'property_type' => 'HMO',
            'status' => 'For Rent',
            'is_featured' => $faker->boolean(20),
            'list_date' => $faker->dateTimeBetween('-1 year', 'now'),
            'user_id' => 1,
            'property_category_id' => $category->id,
            'latitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'postal_code' => $faker->postcode,
            'neighborhood_id' => $neighborhoods->count() > 0 ? $neighborhoods->random()->id : null,
            'virtual_tour_url' => $hasVirtualTour ? $virtualTourUrls[$tourIndex] : null,
            'virtual_tour_provider' => $hasVirtualTour ? $virtualTourProviders[$tourIndex] : null,
            'live_tour_available' => $hasVirtualTour ? $faker->boolean(70) : false,
            'model_3d_url' => $faker->randomElement($sample3DModels),
        ]);
        for ($j = 0; $j < $property->bedrooms; $j++) {
            $property->rooms()->create([
                'room_number' => 'Room ' . ($j + 1),
                'size' => $faker->numberBetween(100, 300),
                'rent' => $faker->numberBetween(300, 800),
                'is_available' => $faker->boolean(70),
            ]);
        }
    }
}
}

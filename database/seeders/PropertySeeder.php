<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\PropertyCategory;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $salesCategory = PropertyCategory::where('name', 'sales')->first();
        $lettingsCategory = PropertyCategory::where('name', 'lettings')->first();

        $this->createProperties($salesCategory, 10);
        $this->createProperties($lettingsCategory, 10);
    }

    private function createProperties($category, $count)
    {
        $faker = Faker::create();

        for ($i = 0; $i < $count; $i++) {
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
            ]);
        }
    }
}

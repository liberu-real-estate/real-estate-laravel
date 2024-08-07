<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['sales', 'lettings', 'hmo'];

        foreach ($categories as $category) {
            DB::table('property_categories')->insert([
                'name' => $category,
                'slug' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

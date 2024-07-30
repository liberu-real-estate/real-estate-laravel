<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyCategory;

class PropertyCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'sales', 'slug' => 'sales'],
            ['name' => 'rent', 'slug' => 'rent'],
            // Ajoutez d'autres catégories ici
        ];

        foreach ($categories as $category) {
            // Vérifier si la catégorie existe déjà
            PropertyCategory::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}

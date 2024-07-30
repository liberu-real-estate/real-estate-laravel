<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User; // Ajoutez ceci pour obtenir des utilisateurs

class PropertySeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // Obtenez un utilisateur existant

        if ($user) {
            Property::create([
                'title' => 'Aut omnis occaecati perferendis sed.',
                'description' => 'Sed aliquam ex quam alias. Dolores numquam voluptatem esse est numquam. Qui quod et et. Ut molestias modi ea mollitia eos deleniti vel expedita.',
                'location' => 'Kiarashire, CT',
                'price' => 1243817,
                'bedrooms' => 5,
                'bathrooms' => 3,
                'area_sqft' => 2691,
                'year_built' => 1974,
                'property_type' => 'Villa',
                'status' => 'For Sale',
                'is_featured' => false,
                'list_date' => '2023-12-30 20:18:42',
                'user_id' => $user->id, // Utilisez l'ID de l'utilisateur existant
                'property_category_id' => 1, // Utilisez un ID valide pour la catégorie
            ]);
        } else {
            throw new \Exception('No users found in the database.');
        }
    }
}

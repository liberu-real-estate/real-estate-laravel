<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class DefaultTeamSeeder extends Seeder
{
    public function run()
    {
        // Vérifier si l'équipe existe déjà
        if (!Team::where('id', 1)->exists()) {
            Team::create([
                'id' => 1,
                'name' => 'default',
                'personal_team' => false,
                'user_id' => 1,
            ]);
        }
    }
}

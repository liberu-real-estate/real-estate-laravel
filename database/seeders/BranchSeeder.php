<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            Branch::create([
                'name' => $team->name . ' Branch',
                'address' => '456 Oak St, City, Country',
                'phone_number' => '+1987654321',
                'team_id' => $team->id,
            ]);
        }
    }
}
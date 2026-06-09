<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $panels = ['admin', 'staff', 'agent', 'buyer', 'seller', 'landlord', 'tenant', 'contractor', 'app'];

        foreach ($panels as $panel) {
            Artisan::call('shield:generate', [
                '--all' => true,
                '--panel' => $panel,
                '--ignore-existing-policies' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointmentTypes = [
            ['name' => 'Valuation'],
            ['name' => 'Viewing'],
            ['name' => 'Property Inspection'],
            ['name' => 'Contract Signing'],
        ];

        foreach ($appointmentTypes as $type) {
            DB::table('appointment_types')->insert($type);
        }
    }
}
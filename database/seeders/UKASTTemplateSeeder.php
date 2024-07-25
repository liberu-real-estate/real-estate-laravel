<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class UKASTTemplateSeeder extends Seeder
{
    public function run()
    {
        DocumentTemplate::findOrCreateUKASTTemplate();
    }
}
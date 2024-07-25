<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplateSeeder extends Seeder
{
    public function run()
    {
        DocumentTemplate::findOrCreateUKASTTemplate();
        DocumentTemplate::findOrCreateSection8Template();
        DocumentTemplate::findOrCreateSection21Template();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class UKDocumentTemplateSeeder extends Seeder
{
    public function run()
    {
        $templateTypes = [
            'uk_ast_agreement',
            'section_8_notice',
            'section_21_notice',
        ];

        foreach ($templateTypes as $type) {
            DocumentTemplate::findOrCreateTemplate($type);
        }
    }
}
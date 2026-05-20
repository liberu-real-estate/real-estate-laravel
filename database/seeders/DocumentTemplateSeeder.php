<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'type' => 'uk_ast_agreement',
                'name' => 'UK Assured Shorthold Tenancy Agreement',
                'description' => 'UK-specific Assured Shorthold Tenancy agreement template compliant with Housing Act 1988',
                'view_path' => 'document_templates.uk_ast_agreement',
            ],
            [
                'type' => 'section_8_notice',
                'name' => 'Section 8 Notice',
                'description' => 'Notice seeking possession of a property let on an assured tenancy or an assured agricultural occupancy',
                'view_path' => 'document_templates.section_8_notice',
            ],
            [
                'type' => 'section_21_notice',
                'name' => 'Section 21 Notice',
                'description' => 'Notice requiring possession of a property let on an assured shorthold tenancy',
                'view_path' => 'document_templates.section_21_notice',
            ],
            // Add more templates here as needed
        ];

        foreach ($templates as $template) {
            DocumentTemplate::findOrCreateTemplate(
                $template['type'],
                $template['name'],
                $template['description'],
                $template['view_path']
            );
        }
    }
}

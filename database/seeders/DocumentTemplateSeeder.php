<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            'UKASTTemplate' => 'UK Assured Shorthold Tenancy Agreement',
            'Section8Template' => 'Section 8 Notice',
            'Section21Template' => 'Section 21 Notice',
            'LeaseAgreementTemplate' => 'Lease Agreement',
            'RentalApplicationTemplate' => 'Rental Application Form',
            'PropertyInspectionTemplate' => 'Property Inspection Report',
            'EvictionNoticeTemplate' => 'Eviction Notice',
            'RentReceiptTemplate' => 'Rent Receipt',
        ];

        foreach ($templates as $method => $name) {
            $findOrCreateMethod = 'findOrCreate' . $method;
            if (method_exists(DocumentTemplate::class, $findOrCreateMethod)) {
                DocumentTemplate::$findOrCreateMethod();
            } else {
                DocumentTemplate::create([
                    'name' => $name,
                    'file_path' => 'templates/' . strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $method)) . '.docx',
                    'description' => 'Template for ' . $name,
                    'team_id' => 1,
                    'type' => strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $method)),
                ]);
            }
        }
    }
}

<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\Tenant;
use App\Models\Property;

class LeaseAgreementService
{
    public function createLeaseAgreement(Tenant $tenant, Property $property, array $terms)
    {
        $template = DocumentTemplate::findOrCreateLeaseTemplate();
        $content = $this->populateTemplate($template->content, $tenant, $property, $terms);
        
        // Here you would save the populated agreement, possibly to a new 'lease_agreements' table
        // For now, we'll just return the content
        return $content;
    }

    private function populateTemplate($content, Tenant $tenant, Property $property, array $terms)
    {
        $placeholders = [
            '{{tenant_name}}' => $tenant->name,
            '{{property_address}}' => $property->address,
            '{{lease_start_date}}' => $terms['start_date'],
            '{{lease_end_date}}' => $terms['end_date'],
            '{{monthly_rent}}' => $terms['monthly_rent'],
            // Add more placeholders as needed
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $content);
    }
}
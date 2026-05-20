
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

    private function populateUKASTTemplate($content, Tenant $tenant, Property $property, array $terms)
    {
        $placeholders = [
            '{{agreement_date}}' => now()->format('d F Y'),
            '{{landlord_name}}' => $property->owner->name,
            '{{landlord_address}}' => $property->owner->address,
            '{{tenant_name}}' => $tenant->name,
            '{{tenant_address}}' => $tenant->address,
            '{{property_address}}' => $property->address,
            '{{tenancy_term}}' => $terms['term'],
            '{{start_date}}' => $terms['start_date'],
            '{{end_date}}' => $terms['end_date'],
            '{{monthly_rent}}' => $terms['monthly_rent'],
            '{{rent_due_day}}' => $terms['rent_due_day'],
            '{{landlord_signature_date}}' => '',
            '{{tenant_signature_date}}' => '',
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $content);
    }
}
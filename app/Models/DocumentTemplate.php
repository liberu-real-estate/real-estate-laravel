<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'team_id',
        'type',
        'content',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public static function findOrCreateLeaseTemplate()
    {
        return self::firstOrCreate(
            ['type' => 'lease_agreement'],
            [
                'name' => 'Default Lease Agreement',
                'description' => 'Standard tenancy lease agreement template',
                'content' => '{{lease_content}}', // Placeholder for actual template content
                'team_id' => 1, // Assuming a default team ID, adjust as needed
            ]
        );
    }

    public static function findOrCreateUKASTTemplate()
    {
        return self::firstOrCreate(
            ['type' => 'uk_ast_agreement'],
            [
                'name' => 'UK Assured Shorthold Tenancy Agreement',
                'description' => 'UK-specific Assured Shorthold Tenancy agreement template compliant with Housing Act 1988',
                'content' => self::getUKASTContent(),
                'team_id' => 1, // Assuming a default team ID, adjust as needed
            ]
        );
    }

    private static function getUKASTContent()
    {
        return <<<EOT
ASSURED SHORTHOLD TENANCY AGREEMENT

This agreement is made on {{agreement_date}} between:

LANDLORD: {{landlord_name}} of {{landlord_address}}

and

TENANT: {{tenant_name}} of {{tenant_address}}

PROPERTY: The dwelling known as {{property_address}}

1. The Landlord lets to the Tenant the Property for a term of {{tenancy_term}} commencing on {{start_date}} and ending on {{end_date}}.

2. The Tenant shall pay rent of £{{monthly_rent}} per calendar month, payable in advance on the {{rent_due_day}} day of each month.

3. This agreement is an assured shorthold tenancy as defined in section 19A of the Housing Act 1988.

4. The Tenant agrees:
   a) To pay the rent on time
   b) To use the Property as a private residence only
   c) To keep the Property in good condition
   d) To allow the Landlord access for inspections and repairs

5. The Landlord agrees:
   a) To keep the Property in good repair
   b) To ensure all gas and electrical appliances are safe
   c) To protect the Tenant's deposit in a government-approved scheme

6. This agreement may be terminated by either party giving notice in accordance with the Housing Act 1988.

Signed by the Landlord: ________________________ Date: {{landlord_signature_date}}

Signed by the Tenant: __________________________ Date: {{tenant_signature_date}}
EOT;
    }
}
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

    public static function findOrCreateTemplate(string $type, string $name, string $description, string $content)
    {
        return self::firstOrCreate(
            ['type' => $type],
            [
                'name' => $name,
                'description' => $description,
                'content' => $content,
                'team_id' => 1, // Assuming a default team ID, adjust as needed
            ]
        );
    }

    public static function findOrCreateLeaseTemplate()
    {
        return self::findOrCreateTemplate(
            'lease_agreement',
            'Default Lease Agreement',
            'Standard tenancy lease agreement template',
            '{{lease_content}}' // Placeholder for actual template content
        );
    }

    public static function findOrCreateUKASTTemplate()
    {
        return self::findOrCreateTemplate(
            'uk_ast_agreement',
            'UK Assured Shorthold Tenancy Agreement',
            'UK-specific Assured Shorthold Tenancy agreement template compliant with Housing Act 1988',
            self::getUKASTContent()
        );
    }

    public static function findOrCreateSection8Template()
    {
        return self::findOrCreateTemplate(
            'section_8_notice',
            'Section 8 Notice',
            'Notice seeking possession of a property let on an assured tenancy or an assured agricultural occupancy',
            self::getSection8Content()
        );
    }

    public static function findOrCreateSection21Template()
    {
        return self::findOrCreateTemplate(
            'section_21_notice',
            'Section 21 Notice',
            'Notice requiring possession of a property let on an assured shorthold tenancy',
            self::getSection21Content()
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

    private static function getSection8Content()
    {
        return <<<EOT
SECTION 8 NOTICE
(Notice seeking possession of a property let on an assured tenancy or an assured agricultural occupancy)

Housing Act 1988 section 8 as amended by section 151 of the Housing Act 1996

To: {{tenant_name}}

TAKE NOTICE that possession is sought of the premises known as:
{{property_address}}

on the following ground(s):

{{grounds_for_possession}}

The court proceedings will not begin until after: {{notice_expiry_date}}

Dated: {{notice_date}}

Signed: ________________________
(Landlord/Agent)

Name and address of landlord/agent:
{{landlord_name}}
{{landlord_address}}

NOTES:
1. If the tenant or licensee does not leave the dwelling, the landlord or licensor must get an order for possession from the court before the tenant or licensee can lawfully be evicted.
2. The tenant or licensee can apply to the court for a postponement of the date of possession.
EOT;
    }

    private static function getSection21Content()
    {
        return <<<EOT
SECTION 21 NOTICE
(Notice requiring possession of a property let on an assured shorthold tenancy)

Housing Act 1988 section 21(1) and (4) as amended by section 194 and paragraph 103 of Schedule 11 to the Local Government and Housing Act 1989

To: {{tenant_name}}

TAKE NOTICE that possession is required on or after {{possession_date}} of the premises known as:
{{property_address}}

which are occupied under an assured shorthold tenancy.

Dated: {{notice_date}}

Signed: ________________________
(Landlord/Agent)

Name and address of landlord/agent:
{{landlord_name}}
{{landlord_address}}

NOTES:
1. This notice is valid for six months from the date of service.
2. If the tenant does not leave the dwelling, the landlord must get an order for possession from the court before the tenant can lawfully be evicted.
3. The date specified in paragraph 1 above must not be earlier than two months from the date this notice is served and must not be earlier than the date on which the tenancy could be brought to an end under the terms of the tenancy agreement.
EOT;
    }
}
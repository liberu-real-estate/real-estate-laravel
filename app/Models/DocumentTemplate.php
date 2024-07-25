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

    public static function findOrCreateTemplate(string $type)
    {
        $templateData = self::getTemplateData($type);
        return self::firstOrCreate(
            ['type' => $type],
            $templateData
        );
    }

    private static function getTemplateData(string $type): array
    {
        $templates = [
            'lease_agreement' => [
                'name' => 'Default Lease Agreement',
                'description' => 'Standard tenancy lease agreement template',
                'content' => self::getLeaseContent(),
            ],
            'uk_ast_agreement' => [
                'name' => 'UK Assured Shorthold Tenancy Agreement',
                'description' => 'UK-specific Assured Shorthold Tenancy agreement template compliant with Housing Act 1988',
                'content' => self::getUKASTContent(),
            ],
            'section_8_notice' => [
                'name' => 'Section 8 Notice',
                'description' => 'Notice seeking possession of a property let on an assured tenancy or an assured agricultural occupancy',
                'content' => self::getSection8Content(),
            ],
            'section_21_notice' => [
                'name' => 'Section 21 Notice',
                'description' => 'Notice requiring possession of a property let on an assured shorthold tenancy',
                'content' => self::getSection21Content(),
            ],
        ];

        if (!isset($templates[$type])) {
            throw new \InvalidArgumentException("Unknown template type: $type");
        }

        return array_merge($templates[$type], ['team_id' => 1]); // Assuming a default team ID, adjust as needed
    }

    private static function getLeaseContent()
    {
        return '{{lease_content}}'; // Placeholder for actual template content
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

Housing Act 1988 section 8 as amended by section 151 of the Housing Act 1996 and section 97 of the Anti-social Behaviour, Crime and Policing Act 2014

To: {{tenant_name}}

Of: {{property_address}}

1. Your landlord {{landlord_name}} is serving this notice to inform you that they intend to seek possession of the property known as {{property_address}} on the following ground(s):

{{grounds_for_possession}}

2. The court proceedings will not begin until after {{notice_expiry_date}}.

3. If you need advice about this notice, you should take it immediately to a Citizens' Advice Bureau, a housing advice centre, a law centre or a solicitor.

Signed: ________________________ (Landlord/Agent)

Date: {{notice_date}}

Name and address of landlord/agent:
{{landlord_agent_name}}
{{landlord_agent_address}}
EOT;
    }

    private static function getSection21Content()
    {
        return <<<EOT
SECTION 21 NOTICE
(Notice requiring possession of a property let on an assured shorthold tenancy)

Housing Act 1988 section 21(1) and (4) as amended by section 194 and paragraph 103 of Schedule 11 to the Local Government and Housing Act 1989 and section 98(2) and (3) of the Housing Act 1996

To: {{tenant_name}}

Of: {{property_address}}

1. Your landlord {{landlord_name}} gives you notice that they require possession of the property known as:

{{property_address}}

2. The landlord requires possession after: {{notice_expiry_date}}

(Note: This date must be at least two months from the date this notice is served and, if the tenancy is a periodic tenancy, must also be the last day of a period of the tenancy)

3. This notice is valid for six months from the date of issue.

Signed: ________________________ (Landlord/Agent)

Date: {{notice_date}}

Name and address of landlord/agent:
{{landlord_agent_name}}
{{landlord_agent_address}}
EOT;
    }
}
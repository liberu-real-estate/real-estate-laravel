<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\View;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'team_id',
        'type',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public static function findOrCreateTemplate(string $type, string $name, string $description, string $view_path)
    {
        return self::firstOrCreate(
            ['type' => $type],
            [
                'name' => $name,
                'description' => $description,
                'file_path' => $view_path,
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
            'document_templates.lease_agreement'
        );
    }

    public static function findOrCreateUKASTTemplate()
    {
        return self::findOrCreateTemplate(
            'uk_ast_agreement',
            'UK Assured Shorthold Tenancy Agreement',
            'UK-specific Assured Shorthold Tenancy agreement template compliant with Housing Act 1988',
            'document_templates.uk_ast_agreement'
        );
    }

    public static function findOrCreateSection8Template()
    {
        return self::findOrCreateTemplate(
            'section_8_notice',
            'Section 8 Notice',
            'Notice seeking possession of a property let on an assured tenancy or an assured agricultural occupancy',
            'document_templates.section_8_notice'
        );
    }

    public static function findOrCreateSection21Template()
    {
        return self::findOrCreateTemplate(
            'section_21_notice',
            'Section 21 Notice',
            'Notice requiring possession of a property let on an assured shorthold tenancy',
            'document_templates.section_21_notice'
        );
    }

    public function renderContent(array $data = [])
    {
        return View::make($this->file_path, $data)->render();
    }
}
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
}
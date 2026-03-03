<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'team_id',
        'report_type',
        'surveyor_name',
        'surveyor_company',
        'survey_date',
        'expiry_date',
        'energy_band',
        'energy_current_score',
        'energy_potential_score',
        'property_condition',
        'condition_categories',
        'market_value',
        'reinstatement_cost',
        'file_path',
        'file_url',
        'notes',
    ];

    protected $casts = [
        'survey_date' => 'date',
        'expiry_date' => 'date',
        'market_value' => 'decimal:2',
        'reinstatement_cost' => 'decimal:2',
        'energy_current_score' => 'integer',
        'energy_potential_score' => 'integer',
        'condition_categories' => 'array',
    ];

    public const CONDITION_LABELS = [
        '1' => 'No action required',
        '2' => 'Routine maintenance required',
        '3' => 'Urgent attention required',
    ];

    public const CONDITION_SECTIONS = [
        'structure', 'roof_outside', 'roof_inside', 'chimney_stacks',
        'rainwater_fittings', 'main_walls', 'windows', 'outside_doors',
        'outside_decorative_finishes', 'conservatories', 'garages',
        'outside_access', 'gas_electricity', 'water', 'heating',
        'drainage', 'common_services', 'cellar',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isValid(): bool
    {
        return $this->survey_date && !$this->isExpired();
    }

    public function getConditionLabel(): string
    {
        return self::CONDITION_LABELS[$this->property_condition] ?? 'Unknown';
    }

    public function getEnergyImprovementPoints(): ?int
    {
        if ($this->energy_current_score !== null && $this->energy_potential_score !== null) {
            return $this->energy_potential_score - $this->energy_current_score;
        }

        return null;
    }

    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_date')->where('expiry_date', '<', now());
    }
}

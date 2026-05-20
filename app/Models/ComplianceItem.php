<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'compliance_type',
        'title',
        'description',
        'regulation_reference',
        'required_by_date',
        'completed_date',
        'status',
        'priority_level',
        'responsible_party',
        'cost_estimate',
        'actual_cost',
        'certificate_number',
        'certificate_expiry',
        'renewal_required',
        'team_id',
        'assigned_to',
        'notes',
        'risk_level'
    ];

    protected $casts = [
        'required_by_date' => 'date',
        'completed_date' => 'date',
        'certificate_expiry' => 'date',
        'cost_estimate' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'renewal_required' => 'boolean',
        'priority_level' => 'integer',
        'risk_level' => 'integer'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function complianceDocuments(): HasMany
    {
        return $this->hasMany(ComplianceDocument::class);
    }

    public function isOverdue(): bool
    {
        return $this->required_by_date < now() && $this->status !== 'completed';
    }

    public function isDueSoon(): bool
    {
        return $this->required_by_date <= now()->addDays(30) && $this->status !== 'completed';
    }

    public function getCertificateStatus(): string
    {
        if (!$this->certificate_expiry) {
            return 'No Certificate';
        }

        if ($this->certificate_expiry < now()) {
            return 'Expired';
        }

        if ($this->certificate_expiry <= now()->addDays(30)) {
            return 'Expiring Soon';
        }

        return 'Valid';
    }

    public function getRiskLevelText(): string
    {
        return match($this->risk_level) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Critical',
            default => 'Unknown'
        };
    }

    public function scopeOverdue($query)
    {
        return $query->where('required_by_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    public function scopeDueSoon($query)
    {
        return $query->where('required_by_date', '<=', now()->addDays(30))
                    ->where('status', '!=', 'completed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('compliance_type', $type);
    }

    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', '>=', 3);
    }
}
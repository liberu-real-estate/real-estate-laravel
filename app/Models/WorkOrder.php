<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'vendor_id',
        'title',
        'description',
        'work_type',
        'priority',
        'status',
        'scheduled_date',
        'started_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'estimated_hours',
        'actual_hours',
        'materials_cost',
        'labor_cost',
        'emergency_job',
        'requires_access',
        'access_instructions',
        'safety_requirements',
        'completion_notes',
        'customer_satisfaction',
        'team_id',
        'created_by',
        'assigned_to',
        'approved_by',
        'invoice_number',
        'payment_status'
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'started_date' => 'datetime',
        'completed_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'materials_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'emergency_job' => 'boolean',
        'requires_access' => 'boolean',
        'customer_satisfaction' => 'integer',
        'safety_requirements' => 'array'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function workOrderUpdates(): HasMany
    {
        return $this->hasMany(WorkOrderUpdate::class);
    }

    public function isOverdue(): bool
    {
        return $this->scheduled_date < now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDuration(): ?int
    {
        if ($this->started_date && $this->completed_date) {
            return $this->started_date->diffInHours($this->completed_date);
        }
        return null;
    }

    public function getCostVariance(): float
    {
        if ($this->estimated_cost && $this->actual_cost) {
            return $this->actual_cost - $this->estimated_cost;
        }
        return 0;
    }

    public function getTimeVariance(): float
    {
        if ($this->estimated_hours && $this->actual_hours) {
            return $this->actual_hours - $this->estimated_hours;
        }
        return 0;
    }

    public function getPriorityLevel(): string
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Critical',
            default => 'Unknown'
        };
    }

    public function canBeStarted(): bool
    {
        return in_array($this->status, ['pending', 'approved', 'scheduled']);
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress';
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeEmergency($query)
    {
        return $query->where('emergency_job', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeScheduledToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }

    public function scopeScheduledThisWeek($query)
    {
        return $query->whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }
}
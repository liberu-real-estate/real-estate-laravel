<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'update_type',
        'status_change',
        'description',
        'progress_percentage',
        'time_spent',
        'materials_used',
        'issues_encountered',
        'next_steps',
        'updated_by',
        'update_date',
        'is_customer_visible'
    ];

    protected $casts = [
        'update_date' => 'datetime',
        'progress_percentage' => 'integer',
        'time_spent' => 'decimal:2',
        'is_customer_visible' => 'boolean',
        'materials_used' => 'array',
        'issues_encountered' => 'array'
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeCustomerVisible($query)
    {
        return $query->where('is_customer_visible', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('update_type', $type);
    }
}
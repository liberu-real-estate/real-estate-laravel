<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'postal_code',
        'country',
        'website',
        'vendor_type',
        'specializations',
        'rating',
        'status',
        'preferred_vendor',
        'insurance_valid_until',
        'certifications',
        'payment_terms',
        'hourly_rate',
        'daily_rate',
        'emergency_contact',
        'emergency_phone',
        'availability_hours',
        'service_areas',
        'team_id',
        'added_by',
        'notes',
        'tax_number',
        'bank_details'
    ];

    protected $casts = [
        'insurance_valid_until' => 'date',
        'preferred_vendor' => 'boolean',
        'rating' => 'decimal:1',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'specializations' => 'array',
        'certifications' => 'array',
        'service_areas' => 'array',
        'availability_hours' => 'array',
        'bank_details' => 'array'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function vendorReviews(): HasMany
    {
        return $this->hasMany(VendorReview::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(VendorQuote::class);
    }

    public function hasValidInsurance(): bool
    {
        return $this->insurance_valid_until && $this->insurance_valid_until >= now();
    }

    public function getAverageRating(): float
    {
        return $this->vendorReviews()->avg('rating') ?? 0;
    }

    public function getTotalJobsCompleted(): int
    {
        return $this->workOrders()->where('status', 'completed')->count();
    }

    public function isAvailableForEmergency(): bool
    {
        return !empty($this->emergency_contact) && !empty($this->emergency_phone);
    }

    public function servicesArea($area): bool
    {
        return in_array($area, $this->service_areas ?? []);
    }

    public function hasSpecialization($specialization): bool
    {
        return in_array($specialization, $this->specializations ?? []);
    }

    public function getInsuranceStatus(): string
    {
        if (!$this->insurance_valid_until) {
            return 'No Insurance';
        }

        if ($this->insurance_valid_until < now()) {
            return 'Expired';
        }

        if ($this->insurance_valid_until <= now()->addDays(30)) {
            return 'Expiring Soon';
        }

        return 'Valid';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePreferred($query)
    {
        return $query->where('preferred_vendor', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('vendor_type', $type);
    }

    public function scopeWithValidInsurance($query)
    {
        return $query->where('insurance_valid_until', '>=', now());
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->whereJsonContains('specializations', $specialization);
    }

    public function scopeByServiceArea($query, $area)
    {
        return $query->whereJsonContains('service_areas', $area);
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
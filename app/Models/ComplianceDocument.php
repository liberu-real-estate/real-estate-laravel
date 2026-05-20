<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComplianceDocument extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'compliance_item_id',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'uploaded_by',
        'upload_date',
        'expiry_date',
        'is_verified',
        'verified_by',
        'verified_date',
        'notes'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'expiry_date' => 'date',
        'verified_date' => 'datetime',
        'is_verified' => 'boolean',
        'file_size' => 'integer'
    ];

    public function complianceItem(): BelongsTo
    {
        return $this->belongsTo(ComplianceItem::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && $this->expiry_date <= now()->addDays(30);
    }

    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_documents')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query)
    {
        return $query->where('expiry_date', '<=', now()->addDays(30));
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
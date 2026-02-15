<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    use HasFactory;

    protected $primaryKey = 'image_id';

    protected $fillable = [
        'team_id',
        'property_id',
        'is_staged',
        'original_image_id',
        'staging_style',
        'staging_metadata',
        'staging_provider',
        'file_path',
        'file_name',
        'mime_type',
    ];

    protected $casts = [
        'is_staged' => 'boolean',
        'staging_metadata' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function originalImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'original_image_id', 'image_id');
    }

    public function stagedVersions(): HasMany
    {
        return $this->hasMany(Image::class, 'original_image_id', 'image_id');
    }

    public function isStaged(): bool
    {
        return $this->is_staged;
    }

    public function hasStagedVersions(): bool
    {
        return $this->stagedVersions()->exists();
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VRDesign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'user_id',
        'team_id',
        'name',
        'description',
        'vr_provider',
        'design_data',
        'room_layout',
        'furniture_items',
        'materials',
        'lighting',
        'thumbnail_path',
        'vr_scene_url',
        'is_public',
        'is_template',
        'style',
        'view_count',
    ];

    protected $casts = [
        'design_data' => 'array',
        'room_layout' => 'array',
        'furniture_items' => 'array',
        'materials' => 'array',
        'lighting' => 'array',
        'is_public' => 'boolean',
        'is_template' => 'boolean',
        'view_count' => 'integer',
    ];

    /**
     * Get the property that owns the VR design.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user that created the VR design.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the team that owns the VR design.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Scope a query to only include public designs.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include template designs.
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope a query to filter by style.
     */
    public function scopeByStyle($query, string $style)
    {
        return $query->where('style', $style);
    }
}

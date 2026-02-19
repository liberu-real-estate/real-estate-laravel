<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Represents the Zoopla API settings.
 *
 * @property int $id
 * @property string $api_key
 * @property string $base_uri
 * @property string $sync_frequency
 * @property string|null $feed_id
 * @property bool $is_active
 */
class ZooplaSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_uri',
        'sync_frequency',
        'api_key',
        'feed_id',
        'is_active',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
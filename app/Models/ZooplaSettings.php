<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents the Zoopla API settings.
 *
 * @property int $id
 * @property string $api_key
 * @property string $base_uri
 * @property int $sync_frequency
 */
class ZooplaSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'base_uri',
        'sync_frequency',
    ];

    protected $hidden = [
        'api_key',
    ];
}
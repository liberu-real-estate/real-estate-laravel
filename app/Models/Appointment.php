<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents an appointment in the real estate application.
 *
 * @property int $appointment_id
 * @property int $user_id
 * @property int $agent_id
 * @property int $property_id
 * @property \DateTime $appointment_date
 * @property string $status
 * @property-read \App\Models\User $user
 * @property-read \App\Models\User $agent
 * @property-read \App\Models\Property $property
 */
class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'user_id',
        'agent_id',
        'property_id',
        'appointment_date',
        'status',
        'team_id',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope a query to only include upcoming appointments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('appointment_date', '>', now());
    }

    /**
     * Scope a query to only include appointments with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}


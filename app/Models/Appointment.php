<?php

namespace App\Models;

use DateTime;
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
 * @property DateTime $appointment_date
 * @property string $status
 * @property-read User $user
 * @property-read User $agent
 * @property-read Property $property
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
        'appointment_type_id',
        'name',
        'contact',
        'notes',
        'staff_id',
        'property_address',
        'property_type',
        'area_sqft',
        'bedrooms',
        'bathrooms',
        'calendar_event_id',
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

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    /**
     * Scope a query to only include upcoming appointments.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('appointment_date', '>', now());
    }

    /**
     * Scope a query to only include appointments with a specific status.
     *
     * @param Builder $query
     * @param  string  $status
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}


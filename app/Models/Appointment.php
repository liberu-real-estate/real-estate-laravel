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
        'duration',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'duration' => 'integer',
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

    public static function getAvailableTimeSlots($date, $agentId)
    {
        $workingHours = ['09:00', '17:00'];
        $appointmentDuration = 60; // minutes

        $bookedSlots = self::where('agent_id', $agentId)
            ->whereDate('appointment_date', $date)
            ->pluck('appointment_date')
            ->map(function ($dateTime) {
                return $dateTime->format('H:i');
            })
            ->toArray();

        $availableSlots = [];
        $currentTime = Carbon::parse($date . ' ' . $workingHours[0]);
        $endTime = Carbon::parse($date . ' ' . $workingHours[1]);

        while ($currentTime->lt($endTime)) {
            $timeSlot = $currentTime->format('H:i');
            if (!in_array($timeSlot, $bookedSlots)) {
                $availableSlots[] = $timeSlot;
            }
            $currentTime->addMinutes($appointmentDuration);
        }

        return $availableSlots;
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


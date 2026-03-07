<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Property;
use App\Models\Team;
use App\Events\BookingCancelled;
use App\Events\BookingRescheduled;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'staff_id',
        'user_id',
        'notes',
        'property_id',
        'name',
        'contact',
        'team_id',
        'status',
        'visit_type',
        'feedback',
        'calendar_event_id',
        'booking_type',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function getTimeAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return Carbon::parse($value);
    }

    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = $value instanceof Carbon ? $value->format('H:i:s') : $value;
    }

    public function scopeVisits($query)
    {
        return $query->where('visit_type', 'property_visit');
    }

    public function hasProvidedFeedback()
    {
        return !empty($this->feedback);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function setStaffIdAttribute($value)
    {
        $this->attributes['staff_id'] = $value ?? $this->getDefaultStaffId();
    }

    private function getDefaultStaffId()
    {
        return User::where('role', 'staff')->first()->id ?? null;
    }

    public function cancel()
    {
        if ($this->canBeCancelled()) {
            $this->status = 'cancelled';
            $this->save();
            event(new BookingCancelled($this));
            return true;
        }
        return false;
    }

    public function reschedule($newDate, $newTime)
    {
        if ($this->canBeRescheduled()) {
            $this->date = $newDate;
            $this->time = $newTime;
            $this->save();
            event(new BookingRescheduled($this));
            return true;
        }
        return false;
    }

    public function canBeCancelled()
    {
        $time = $this->getRawOriginal('time') ?? ($this->time instanceof \Carbon\Carbon ? $this->time->format('H:i:s') : $this->time);
        $cancellationDeadline = Carbon::parse((is_string($this->date) ? $this->date : $this->date->format('Y-m-d')) . ' ' . $time)->subHours(24);
        return Carbon::now()->lt($cancellationDeadline);
    }

    public function canBeRescheduled()
    {
        $time = $this->getRawOriginal('time') ?? ($this->time instanceof \Carbon\Carbon ? $this->time->format('H:i:s') : $this->time);
        $reschedulingDeadline = Carbon::parse((is_string($this->date) ? $this->date : $this->date->format('Y-m-d')) . ' ' . $time)->subHours(48);
        return Carbon::now()->lt($reschedulingDeadline);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }
}

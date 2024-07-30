<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

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
        $cancellationDeadline = Carbon::parse($this->date . ' ' . $this->time)->subHours(24);
        return Carbon::now()->lt($cancellationDeadline);
    }

    public function canBeRescheduled()
    {
        $reschedulingDeadline = Carbon::parse($this->date . ' ' . $this->time)->subHours(48);
        return Carbon::now()->lt($reschedulingDeadline);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }
}

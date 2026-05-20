<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'user_id',
        'team_id',
        'status',
        'search_criteria',
    ];

    protected $casts = [
        'search_criteria' => 'array',
    ];

    /**
     * Get the buyer's full name from first and last name components.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim("{$this->first_name} {$this->last_name}");
        }

        return $this->name ?? '';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(PropertyMatch::class);
    }

    public function appointments(): HasMany
    {
        // Appointments are linked through the associated user account
        return $this->hasMany(Appointment::class, 'user_id', 'user_id');
    }
}
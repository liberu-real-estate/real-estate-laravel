<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'team_id',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
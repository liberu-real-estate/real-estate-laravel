<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
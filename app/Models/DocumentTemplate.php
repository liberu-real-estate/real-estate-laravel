<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'team_id',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

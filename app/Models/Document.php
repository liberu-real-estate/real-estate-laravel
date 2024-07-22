<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'team_id'];

    public function digitalSignatures()
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'team_id'];

    public function digitalSignatures()
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

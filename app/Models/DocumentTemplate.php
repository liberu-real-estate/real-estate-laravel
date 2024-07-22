<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'description',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

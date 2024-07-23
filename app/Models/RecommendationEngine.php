<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationEngine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferences',
        'search_history',
        'browsing_behavior',
    ];

    protected $casts = [
        'preferences' => 'array',
        'search_history' => 'array',
        'browsing_behavior' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
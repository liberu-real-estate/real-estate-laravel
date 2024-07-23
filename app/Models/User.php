<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // ... existing code ...

    public function recommendationEngine()
    {
        return $this->hasOne(RecommendationEngine::class);
    }
}
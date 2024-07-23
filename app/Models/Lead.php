<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CrmIntegrationService;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'interest',
        'message',
        'status',
        'score',
        'crm_id',
    ];

    protected static function booted()
    {
        static::created(function ($lead) {
            app(CrmIntegrationService::class)->syncLead($lead);
        });

        static::updated(function ($lead) {
            app(CrmIntegrationService::class)->syncLead($lead);
        });
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}
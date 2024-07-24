<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CrmIntegrationService;
use App\Jobs\SyncLeadToCrm;
use App\Jobs\SyncActivityToCrm;

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
            SyncLeadToCrm::dispatch($lead);
        });

        static::updated(function ($lead) {
            SyncLeadToCrm::dispatch($lead);
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

    public function addActivity($type, $description)
    {
        $activity = $this->activities()->create([
            'type' => $type,
            'description' => $description,
        ]);

        SyncActivityToCrm::dispatch($activity);

        return $activity;
    }
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}

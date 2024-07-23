<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasPortalSync
{
    public function scopeNeedsSyncing(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('last_synced_at')
                  ->orWhere('updated_at', '>', 'last_synced_at');
        });
    }

    public function markAsSynced()
    {
        $this->last_synced_at = now();
        $this->save();
    }

    public function needsSync(): bool
    {
        return $this->last_synced_at === null || $this->updated_at > $this->last_synced_at;
    }
}
    public function scopeNeedsSyncing(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('last_synced_at')
                  ->orWhere('updated_at', '>', 'last_synced_at')
                  ->orWhereNull('boomin_id');
        });
    }
}
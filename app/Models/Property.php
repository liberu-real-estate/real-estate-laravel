    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
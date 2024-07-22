    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function contractors(): HasMany
    {
        return $this->hasMany(Contractor::class);
    }

    public function digitalSignatures(): HasMany
    {
        return $this->hasMany(DigitalSignature::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function documentTemplates(): HasMany
    {
        return $this->hasMany(DocumentTemplate::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function keyLocations(): HasMany
    {
        return $this->hasMany(KeyLocation::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function propertyFeatures(): HasMany
    {
        return $this->hasMany(PropertyFeature::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function rightMoveSettings(): HasMany
    {
        return $this->hasMany(RightMoveSettings::class);
    }

    public function siteSettings(): HasMany
    {
        return $this->hasMany(SiteSettings::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function zooplaSettings(): HasMany
    {
        return $this->hasMany(ZooplaSettings::class);
    }
}
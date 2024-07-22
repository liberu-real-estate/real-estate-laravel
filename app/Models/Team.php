    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function contractors()
    {
        return $this->hasMany(Contractor::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function documentTemplates()
    {
        return $this->hasMany(DocumentTemplate::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
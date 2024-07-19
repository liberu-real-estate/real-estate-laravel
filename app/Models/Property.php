    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeBedrooms($query, $min, $max)
    {
        return $query->whereBetween('bedrooms', [$min, $max]);
    }

    public function scopeBathrooms($query, $min, $max)
    {
        return $query->whereBetween('bathrooms', [$min, $max]);
    }

    public function scopeAreaRange($query, $min, $max)
    {
        return $query->whereBetween('area_sqft', [$min, $max]);
    }

    public function scopePropertyType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeHasAmenities($query, array $amenities)
    {
        return $query->whereHas('features', function ($query) use ($amenities) {
            $query->whereIn('feature_name', $amenities);
        }, '=', count($amenities));
    }
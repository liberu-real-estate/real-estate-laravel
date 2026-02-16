<?php
namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Represents a property in the real estate application.
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $location
 * @property float $price
 * @property int $bedrooms
 * @property int $bathrooms
 * @property float $area_sqft
 * @property int $year_built
 * @property string $property_type
 * @property string $status
 * @property DateTime $list_date
 * @property DateTime|null $sold_date
 * @property int $user_id
 * @property int $agent_id
 * @property string|null $virtual_tour_url
 * @property bool $is_featured
 * @property string|null $rightmove_id
 * @property string|null $zoopla_id
 * @property string|null $onthemarket_id
 * @property DateTime|null $last_synced_at
 * @property DateTime|null $deleted_at
 * @property-read Collection|Appointment[] $appointments
 * @property-read Collection|Transaction[] $transactions
 * @property-read Collection|Review[] $reviews
 * @property-read Collection|PropertyFeature[] $features
 * @property-read Collection|Image[] $images
 * @property-read Collection|Booking[] $bookings
 */
use Illuminate\Support\Facades\Cache;

class Property extends Model implements HasMedia
{
use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'property_template_id',
        'location',
        'latitude',
        'longitude',
        'walkability_score',
        'walkability_description',
        'transit_score',
        'transit_description',
        'bike_score',
        'bike_description',
        'walkability_updated_at',
        'price',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'year_built',
        'property_type',
        'status',
        'list_date',
        'sold_date',
        'user_id',
        'team_id',
        'agent_id',
        'virtual_tour_url',
        'virtual_tour_provider',
        'virtual_tour_embed_code',
        'live_tour_available',
        'is_featured',
        'rightmove_id',
        'zoopla_id',
        'onthemarket_id',
        'last_synced_at',
        'neighborhood_id',
        'property_category_id',
        'postal_code',
        'country',
        'energy_rating',
        'energy_score',
        'energy_rating_date',
        'insurance_policy_id',
        'insurance_coverage_amount',
        'insurance_premium',
        'insurance_expiry_date',
        'floor_plan_data',
        'floor_plan_image',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'list_date' => 'date',
        'sold_date' => 'date',
        'is_featured' => 'boolean',
        'live_tour_available' => 'boolean',
        'insurance_expiry_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'walkability_updated_at' => 'datetime',
        'floor_plan_data' => 'array',
    ];

    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    public function currentAuction()
    {
        return $this->auctions()->where('status', 'active')->first();
    }

    public function isInAuction()
    {
        return $this->currentAuction() !== null;
    }

    public function insurancePolicy()
    {
        return $this->belongsTo(InsurancePolicy::class);
    }

    public function hasActiveInsurance()
    {
        return $this->insurance_policy_id && $this->insurance_expiry_date > now();
    }

    public function template()
    {
        return $this->belongsTo(PropertyTemplate::class, 'property_template_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function setYearBuiltAttribute($value)
    {
        $this->attributes['year_built'] = is_string($value) ? substr($value, 0, 4) : $value;
    }

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'property_id');
    }
    
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'property_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function features()
    {
        return $this->hasMany(PropertyFeature::class, 'property_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function viewCount()
    {
        return $this->activities()->where('type', 'property_view')->count();
    }

    public function similarProperties($limit = 3)
    {
        return Property::where('id', '!=', $this->id)
            ->where('property_type', $this->property_type)
            ->whereBetween('price', [$this->price * 0.8, $this->price * 1.2])
            ->whereBetween('bedrooms', [$this->bedrooms - 1, $this->bedrooms + 1])
            ->whereBetween('bathrooms', [$this->bathrooms - 1, $this->bathrooms + 1])
            ->limit($limit)
            ->get();
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function category()
    {
        return $this->belongsTo(PropertyCategory::class, 'property_category_id');
    }

    public function valuations()
    {
        return $this->hasMany(PropertyValuation::class);
    }

    public function chainLinks()
    {
        return $this->hasMany(ChainLink::class);
    }

    public function complianceItems()
    {
        return $this->hasMany(ComplianceItem::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function vendorQuotes()
    {
        return $this->hasMany(VendorQuote::class);
    }

    public function propertyMatches()
    {
        return $this->hasMany(PropertyMatch::class);
    }

    public function marketAppraisals()
    {
        return $this->hasMany(MarketAppraisal::class);
    }

    public function histories()
    {
        return $this->hasMany(PropertyHistory::class)->orderBy('event_date', 'desc');
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'property_id', 'user_id')
            ->withTimestamps();
    }

    public function communityEvents()
    {
        return $this->hasMany(CommunityEvent::class);
    }

    public function getNearbyCommunityEvents($radius = 10)
    {
        if (!$this->latitude || !$this->longitude) {
            return collect([]);
        }

        // Use the nearby scope which includes distance calculation
        return CommunityEvent::public()
            ->upcoming()
            ->nearby($this->latitude, $this->longitude, $radius)
            ->get()
            ->map(function ($event) {
                // Distance is already calculated by the nearby scope
                // but we need to make it accessible as a property
                $event->distance_from_property = $event->distance ?? 0;
                return $event;
            });
    }

    public function getLatestValuation($type = 'market')
    {
        return $this->valuations()
            ->where('valuation_type', $type)
            ->where('status', 'active')
            ->latest('valuation_date')
            ->first();
    }

    public function getComplianceStatus()
    {
        $total = $this->complianceItems()->count();
        $completed = $this->complianceItems()->where('status', 'completed')->count();
        $overdue = $this->complianceItems()->where('required_by_date', '<', now())
            ->where('status', '!=', 'completed')->count();

        return [
            'total' => $total,
            'completed' => $completed,
            'overdue' => $overdue,
            'completion_rate' => $total > 0 ? ($completed / $total) * 100 : 0
        ];
    }

    public function hasActiveWorkOrders()
    {
        return $this->workOrders()
            ->whereIn('status', ['pending', 'approved', 'scheduled', 'in_progress'])
            ->exists();
    }

    public function getLatestMarketAppraisal()
    {
        return $this->marketAppraisals()
            ->where('valid_until', '>=', now())
            ->latest('appraisal_date')
            ->first();
    }

    /**
     * Update walkability scores for this property
     *
     * @return void
     */
    public function updateWalkabilityScores()
    {
        if (!$this->latitude || !$this->longitude) {
            return;
        }

        $walkScoreService = app(\App\Services\WalkScoreService::class);
        $address = $this->location . ', ' . $this->postal_code;
        
        $scores = $walkScoreService->getWalkScore($address, $this->latitude, $this->longitude);

        if ($scores) {
            $this->update([
                'walkability_score' => $scores['walk_score'],
                'walkability_description' => $scores['walk_description'],
                'transit_score' => $scores['transit_score'],
                'transit_description' => $scores['transit_description'],
                'bike_score' => $scores['bike_score'],
                'bike_description' => $scores['bike_description'],
                'walkability_updated_at' => now(),
            ]);
        }
    }

    /**
     * Check if walkability scores need updating (older than 30 days)
     *
     * @return bool
     */
    public function needsWalkabilityUpdate()
    {
        if (!$this->walkability_updated_at) {
            return true;
        }

        return $this->walkability_updated_at->lt(now()->subDays(30));
    }

    // Scopes
    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('postal_code', 'like', '%' . $search . '%');
        });
    }

    public function scopePostalCode(Builder $query, $postalCode): Builder
    {
        return $query->where('postal_code', 'like', $postalCode . '%');
    }

    public function scopeNearby(Builder $query, $latitude, $longitude, $radius): Builder
    {
        return $query->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
    }

    public function scopeCategory(Builder $query, $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopePriceRange(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeBedrooms(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('bedrooms', [$min, $max]);
    }

    public function scopeBathrooms(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('bathrooms', [$min, $max]);
    }

    public function scopeAreaRange(Builder $query, $min, $max): Builder
    {
        return $query->whereBetween('area_sqft', [$min, $max]);
    }

    public function scopePropertyType(Builder $query, $type): Builder
    {
        return $query->where('property_type', $type);
    }

    public function scopeHasAmenities(Builder $query, array $amenities): Builder
    {
        return $query->whereHas('features', function ($query) use ($amenities) {
            $query->whereIn('feature_name', $amenities);
        }, '=', count($amenities));
    }

    public function scopeNeedsSyncing(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('last_synced_at')
                  ->orWhere('updated_at', '>', 'last_synced_at');
        });
    }

    public function scopeEnergyRating(Builder $query, $rating): Builder
    {
        return $query->where('energy_rating', $rating);
    }

    public function scopeMinEnergyScore(Builder $query, $minScore): Builder
    {
        return $query->where('energy_score', '>=', $minScore);
    }

    public function scopeWalkabilityScore(Builder $query, $minScore): Builder
    {
        return $query->where('walkability_score', '>=', $minScore);
    }

    public function scopeTransitScore(Builder $query, $minScore): Builder
    {
        return $query->where('transit_score', '>=', $minScore);
    }

    public function scopeBikeScore(Builder $query, $minScore): Builder
    {
        return $query->where('bike_score', '>=', $minScore);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeCountry(Builder $query, $country): Builder
    {
        return $query->where('country', $country);
    }

    public function getAvailableDatesForTeam()
    {
        $bookedDates = $this->bookings()
            ->where('team_id', $this->team_id)
            ->pluck('date')
            ->toArray();

        $teamBookings = Booking::where('team_id', $this->team_id)
            ->pluck('date')
            ->toArray();

        $availableDates = [];
        $startDate = now();
        $endDate = now()->addMonths(3);

        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $currentDate = $date->format('Y-m-d');
            if (!in_array($currentDate, $bookedDates) && !in_array($currentDate, $teamBookings)) {
                $availableDates[] = $currentDate;
            }
        }

        return $availableDates;
    }

    /**
     * Check if property has a virtual tour
     *
     * @return bool
     */
    public function hasVirtualTour()
    {
        return !empty($this->virtual_tour_url) || !empty($this->virtual_tour_embed_code);
    }

    /**
     * Get the embedded virtual tour HTML
     *
     * @return string|null
     */
    public function getVirtualTourEmbed()
    {
        if ($this->virtual_tour_embed_code) {
            return $this->virtual_tour_embed_code;
        }

        // Auto-generate embed code for known providers
        if ($this->virtual_tour_url) {
            return $this->generateEmbedCode($this->virtual_tour_url);
        }

        return null;
    }

    /**
     * Generate embed code from URL for common virtual tour providers
     *
     * @param string $url
     * @return string|null
     */
    protected function generateEmbedCode($url)
    {
        // Matterport
        if (str_contains($url, 'matterport.com')) {
            return '<iframe width="100%" height="480" src="' . htmlspecialchars($url) . '" frameborder="0" allowfullscreen allow="xr-spatial-tracking"></iframe>';
        }

        // Kuula
        if (str_contains($url, 'kuula.co')) {
            return '<iframe width="100%" height="480" src="' . htmlspecialchars($url) . '" frameborder="0" allowfullscreen></iframe>';
        }

        // Generic iframe embed
        return '<iframe width="100%" height="480" src="' . htmlspecialchars($url) . '" frameborder="0" allowfullscreen></iframe>';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->withResponsiveImages();

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes(['video/mp4', 'video/quicktime'])
            ->singleFile();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($property) {
            Cache::flush();
        });

        static::updated(function ($property) {
            Cache::flush();
        });

        static::deleted(function ($property) {
            Cache::flush();
        });
    }
}

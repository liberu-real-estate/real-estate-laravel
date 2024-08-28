<?php
namespace App\Models;

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
 * @property \DateTime $list_date
 * @property \DateTime|null $sold_date
 * @property int $user_id
 * @property int $agent_id
 * @property string|null $virtual_tour_url
 * @property bool $is_featured
 * @property string|null $rightmove_id
 * @property string|null $zoopla_id
 * @property string|null $onthemarket_id
 * @property \DateTime|null $last_synced_at
 * @property \DateTime|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Appointment[] $appointments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PropertyFeature[] $features
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Booking[] $bookings
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
        'is_featured',
        'rightmove_id',
        'zoopla_id',
        'onthemarket_id',
        'last_synced_at',
        'neighborhood_id',
        'property_category_id',
        'postal_code',
        'energy_rating',
        'energy_score',
        'energy_rating_date',
        'insurance_policy_id',
        'insurance_coverage_amount',
        'insurance_premium',
        'insurance_expiry_date',
    ];

    protected $casts = [
        'last_synced_at' => 'datetime',
        'list_date' => 'date',
        'sold_date' => 'date',
        'is_featured' => 'boolean',
        'insurance_expiry_date' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
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
        return $this->hasMany(Review::class, 'property_id');
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

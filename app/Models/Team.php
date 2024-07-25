<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

use App\Models\Activity;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Contractor;
use App\Models\DigitalSignature;
use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\KeyLocation;
use App\Models\Lead;
use App\Models\OnTheMarketSettings;
use App\Models\Message;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\Review;
use App\Models\RightMoveSettings;
use App\Models\SiteSettings;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\ZooplaSettings;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function onTheMarketSettings(): HasMany
    {
        return $this->hasMany(OnTheMarketSettings::class);
    }

    public function rightMoveSettings(): HasMany
    {
        return $this->hasMany(RightMoveSettings::class);
    }

    public function zooplaSettings(): HasMany
    {
        return $this->hasMany(ZooplaSettings::class);
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

    public function keyLocations(): HasMany
    {
        return $this->hasMany(KeyLocation::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function propertyFeatures(): HasMany
    {
        return $this->hasMany(PropertyFeature::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}

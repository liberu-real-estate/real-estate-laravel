<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasDefaultTenant, HasTenants, FilamentUser
{
    use HasApiTokens;
    use HasConnectedAccounts;
    use HasRoles;
    use HasFactory;
    use HasProfilePhoto {
        HasProfilePhoto::profilePhotoUrl as getPhotoUrl;
    }
    use Notifiable;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;
    use HasTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function profilePhotoUrl(): Attribute
    {
        return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
            ? Attribute::get(fn () => $this->profile_photo_path)
            : $this->getPhotoUrl();
    }

    /**
     * @return array<Model> | Collection
     */
    public function getTenants(Panel $panel): array|Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();
        return $this->canAccessPanelById($panelId);
    }

    private function canAccessPanelById(string $panelId): bool
    {
        if ($panelId === "admin") {
            return $this->hasRole(['admin', 'super_admin']);
        }
        if ($panelId === "app") {
            return $this->hasRole(["staff", 'admin', 'super_admin']);
        }
        $allowedRoles = config("filament-shield.panels.$panelId", []);
	    return $this->hasAnyRole($allowedRoles);
    }
    public function canAccessFilament(): bool
    {
        $currentPanel = $this->getCurrentPanel();
       return $this->canAccessPanelById($currentPanel);
    }
    private function getCurrentPanel(): string
    {
        // This is a placeholder. You need to implement a way to determine the current panel.
        // It could be based on the current URL, a request parameter, or any other method
        // that fits your application's structure.
        return 'default';
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestTeam;
    }

    public function latestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    public function savedSearches()
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProperties()
    {
        return $this->belongsToMany(Property::class, 'favorites', 'user_id', 'property_id')
            ->withTimestamps();
    }

    public function team()
    {
        return $this->currentTeam();
    }
}

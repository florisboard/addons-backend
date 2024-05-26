<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AuthProviderEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'provider' => AuthProviderEnum::class,
        'is_admin' => 'boolean',
        'password' => 'hashed',
        'username_changed_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function isAdministrator(): bool
    {
        return $this->is_admin;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return Auth::user()->isAdministrator();
    }

    /**
     * @return HasMany<Project>
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * @return BelongsToMany<Project>
     */
    public function maintaining(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'maintainers');
    }

    /**
     * @return HasMany<Collection>
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * @return HasMany<Release>
     */
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    /**
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany<Domain>
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function getFilamentName(): string
    {
        return Auth::user()->username;
    }
}

<?php

namespace App\Models;

use App\Enums\ProjectTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 *
 * @property int|null $category_id
 * @property string $package_name
 * @property string $type
 * @property string|null $description
 * @property string|null $home_page
 * @property string|null $support_email
 * @property string|null $support_site
 * @property string|null $donate_site
 * @property int $is_recommended
 * @property string|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDonateSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereHomePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsRecommended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePackageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSupportEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSupportSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereType($value)
 *
 * @property string $name
 * @property string $slug
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 *
 * @property bool $is_active
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereIsActive($value)
 *
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \App\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Maintainer> $maintainers
 * @property-read int|null $maintainers_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Release> $releases
 * @property-read int|null $releases_count
 *
 * @mixin \Eloquent
 */
class Project extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , SoftDeletes;

    protected $casts = [
        'is_recommended' => 'boolean',
        'type' => ProjectTypeEnum::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();

        $this->addMediaCollection('screenshots');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function maintainers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'maintainers');
    }

    /**
     * @return BelongsToMany<Collection>
     */
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class);
    }

    /**
     * @return BelongsTo<User,Project>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Category,Project>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<Release>
     */
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }
}

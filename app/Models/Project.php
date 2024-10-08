<?php

namespace App\Models;

use App\Enums\ProjectTypeEnum;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperProject
 */
class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $casts = [
        'is_recommended' => 'boolean',
        'status' => StatusEnum::class,
        'type' => ProjectTypeEnum::class,
        'links' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();

        $this->addMediaCollection('screenshots')
            ->onlyKeepLatest(5);
    }

    /**
     * @return MorphOne<Media>
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'image');
    }

    /**
     * @return MorphMany<Media>
     */
    public function screenshots(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', 'screenshots');
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

    /**
     * @return HasOne<Release>
     */
    public function latestRelease(): HasOne
    {
        return $this->releases()
            ->one()
            ->latestOfMany();
    }

    /**
     * @return HasOne<Release>
     */
    public function latestApprovedRelease(): HasOne
    {
        return $this->releases()
            ->one()
            ->ofMany(['id' => 'MAX'], function (Builder $query) {
                $query->where('status', StatusEnum::Approved);
            });
    }

    /**
     * @return HasMany<Review>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasOne<Review>
     */
    public function userReview(): HasOne
    {
        return $this->reviews()->one()->where('user_id', Auth::id());
    }

    /**
     * @return MorphMany<Report>
     */
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * @return MorphMany<ChangeProposal>
     */
    public function changeProposals(): MorphMany
    {
        return $this->morphMany(ChangeProposal::class, 'model');
    }

    /**
     * @return MorphOne<ChangeProposal>
     */
    public function latestChangeProposal(): MorphOne
    {
        return $this->changeProposals()->one()->latestOfMany();
    }
}

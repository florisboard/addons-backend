<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin IdeHelperRelease
 */
class Release extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')
            ->singleFile();
    }

    /**
     * @return MorphOne<Media>
     */
    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'file');
    }

    /**
     * @return BelongsTo<Project,Release>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<User,Release>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

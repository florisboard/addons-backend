<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Release
 *
 * @property int $id
 * @property int $project_id
 * @property string $version
 * @property string|null $description
 * @property int $downloads_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 *
 * @method static \Database\Factories\ReleaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Release newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Release query()
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereDownloadsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereVersion($value)
 *
 * @property int $user_id
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Release whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Release extends Model
{
    use HasFactory;

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

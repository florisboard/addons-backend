<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Maintainer
 *
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\MaintainerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Maintainer whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Maintainer extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo<Project,Maintainer>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<User,Maintainer>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

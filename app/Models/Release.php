<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperRelease
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

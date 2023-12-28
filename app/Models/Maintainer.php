<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperMaintainer
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

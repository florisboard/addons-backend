<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperReview
 */
class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'score' => 'int',
    ];

    /**
     * @return BelongsTo<Project,Review>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<User,Review>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphToMany<Report>
     */
    public function reports(): MorphToMany
    {
        return $this->morphedByMany(Report::class, 'reportable');
    }
}

<?php

namespace App\Models;

use App\Enums\ReportTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @mixin IdeHelperReport
 */
class Report extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => ReportTypeEnum::class,
    ];

    /**
     * @return MorphToMany<Project>
     */
    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, 'reportable');
    }

    /**
     * @return MorphToMany<Review>
     */
    public function reviews(): MorphToMany
    {
        return $this->morphedByMany(Review::class, 'reportable');
    }
}

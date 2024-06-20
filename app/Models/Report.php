<?php

namespace App\Models;

use App\Enums\ReportTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
     * @return Attribute<bool,bool>
     */
    protected function isReviewed(): Attribute
    {
        return Attribute::make(
            get: fn(null $value, array $attributes) => (bool)$attributes['reviewed_at'],
        );
    }

    /**
     * @return BelongsTo<User,Report>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo<Model,Report>
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}

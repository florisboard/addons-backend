<?php

namespace App\Models;

use App\Enums\ReportTypeEnum;
use App\Enums\StatusEnum;
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
        'status' => StatusEnum::class,
    ];

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

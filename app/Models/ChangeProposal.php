<?php

namespace App\Models;

use App\Enums\ChangeProposalStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperChangeProposal
 */
class ChangeProposal extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => ChangeProposalStatusEnum::class,
        'data' => 'array',
    ];

    /**
     * @return BelongsTo<User,ChangeProposal>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphTo<Model,ChangeProposal>
     */
    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }
}

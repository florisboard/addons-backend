<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * @mixin IdeHelperMedia
 */
class Media extends BaseMedia
{
    use HasFactory;
}

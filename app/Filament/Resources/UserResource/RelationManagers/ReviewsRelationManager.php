<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ReviewResource;

class ReviewsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $resource = ReviewResource::class;
}

<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\CollectionResource;

class CollectionsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'collections';

    protected static ?string $resource = CollectionResource::class;
}

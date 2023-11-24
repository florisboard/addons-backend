<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ReleaseResource;

class ReleasesRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'releases';

    protected static ?string $resource = ReleaseResource::class;
}

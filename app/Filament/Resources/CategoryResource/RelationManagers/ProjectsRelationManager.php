<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ProjectResource;

class ProjectsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $resource = ProjectResource::class;
}

<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ReportResource;

class ReportsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $resource = ReportResource::class;
}

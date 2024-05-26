<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\DomainResource;

class DomainsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'domains';

    protected static ?string $resource = DomainResource::class;
}

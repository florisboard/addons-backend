<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ChangeProposalResource;

class ChangeProposalsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'changeProposals';

    protected static ?string $resource = ChangeProposalResource::class;
}

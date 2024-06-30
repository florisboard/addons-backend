<?php

namespace App\Filament\Resources\ChangeProposalResource\Pages;

use App\Filament\Resources\ChangeProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChangeProposals extends ListRecords
{
    protected static string $resource = ChangeProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

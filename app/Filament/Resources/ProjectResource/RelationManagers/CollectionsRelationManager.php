<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\CollectionResource;
use Filament\Actions;
use Filament\Tables\Table;

class CollectionsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'collections';

    protected static ?string $resource = CollectionResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DetachAction::make(),
            ])
            ->toolbarActions([
                Actions\DetachBulkAction::make(),
            ]);
    }
}

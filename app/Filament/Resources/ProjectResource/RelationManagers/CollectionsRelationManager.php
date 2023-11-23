<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\CollectionResource;
use Filament\Tables;
use Filament\Tables\Table;

class CollectionsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'collections';

    protected static ?string $resource = CollectionResource::class;

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}

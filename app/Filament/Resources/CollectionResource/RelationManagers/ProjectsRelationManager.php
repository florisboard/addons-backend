<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Tables\Table;

class ProjectsRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'projects';

    protected static ?string $resource = ProjectResource::class;

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

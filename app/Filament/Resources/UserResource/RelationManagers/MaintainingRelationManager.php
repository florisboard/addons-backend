<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Tables\Table;

class MaintainingRelationManager extends AutoRelationManager
{
    protected static string $relationship = 'maintaining';

    protected static ?string $resource = ProjectResource::class;

    protected static function getPluralRecordLabel(): ?string
    {
        return 'Maintainings';
    }

    protected static function getRecordLabel(): ?string
    {
        return 'Maintaining';
    }

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

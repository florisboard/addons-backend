<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Custom\AutoRelationManager;
use App\Filament\Resources\ProjectResource;
use Filament\Tables;
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
        return static::$resource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}

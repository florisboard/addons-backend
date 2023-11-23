<?php

namespace App\Filament\Custom;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AutoRelationManager extends RelationManager
{
    protected static ?string $resource = null;

    public function getTableModelLabel(): string
    {
        return static::getRecordLabel();
    }

    protected static function getRecordLabel(): ?string
    {
        return Str::title(static::$resource::getModelLabel());
    }

    protected static function getPluralRecordLabel(): ?string
    {
        return Str::title(static::$resource::getPluralModelLabel());
    }

    protected function getTableHeading(): string
    {
        return static::getTablePluralModelLabel();
    }

    public function getTablePluralModelLabel(): string
    {
        return static::getPluralRecordLabel();
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return static::getPluralRecordLabel();
    }

    public function form(Form $form): Form
    {
        return static::$resource::form($form);
    }

    public function table(Table $table): Table
    {
        return static::$resource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}

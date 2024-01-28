<?php

namespace App\Filament\Resources;

use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Components\FileInput;
use App\Filament\Forms\Layouts\BasicForm;
use App\Filament\Resources\ReleaseResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Release;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ReleaseResource extends CustomResource
{
    protected static ?string $model = Release::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    public static function form(Form $form): Form
    {
        return BasicForm::make($form, [
            Forms\Components\TextInput::make('version')
                ->maxLength(255)
                ->regex('/^\d+(?:\.\d+){2}$/')
                ->required(),
            Forms\Components\TextInput::make('downloads_count')
                ->integer()
                ->required(),
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->relationship('user', 'username')
                ->hiddenOn([UserResource\RelationManagers\ReleasesRelationManager::class])
                ->required(),
            Forms\Components\Select::make('project_id')
                ->searchable()
                ->preload()
                ->hiddenOn([ProjectResource\RelationManagers\ReleasesRelationManager::class])
                ->relationship('project', 'name')
                ->required(),
            Forms\Components\MarkdownEditor::make('description')
                ->columnSpanFull()
                ->required(),
            FileInput::make('file')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->hiddenOn([UserResource\RelationManagers\ReleasesRelationManager::class]),
                Tables\Columns\TextColumn::make('project.name')
                    ->hiddenOn([ProjectResource\RelationManagers\ReleasesRelationManager::class]),
                Tables\Columns\TextColumn::make('version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('downloads_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->toggleable(),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReleases::route('/'),
            'create' => Pages\CreateRelease::route('/create'),
            'edit' => Pages\EditRelease::route('/{record}/edit'),
        ];
    }
}

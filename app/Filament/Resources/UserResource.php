<?php

namespace App\Filament\Resources;

use App\Enums\AuthProviderEnum;
use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicForm;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\User;
use App\Rules\Username;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends CustomResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return BasicForm::make($form, [
            Forms\Components\TextInput::make('username')
                ->minLength(3)
                ->maxLength(33)->unique(ignoreRecord: true)
                ->required()
                ->rules([new Username]),
            Forms\Components\Select::make('provider')
                ->options(AuthProviderEnum::class)
                ->searchable()
                ->required(),
            Forms\Components\DateTimePicker::make('username_changed_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_admin')->boolean(),
                Tables\Columns\TextColumn::make('provider')->badge(),
                Tables\Columns\TextColumn::make('username')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('username_changed_at')->toggleable()->dateTime(),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin'),
                Tables\Filters\TernaryFilter::make('email_verified_at')->nullable(),
                Tables\Filters\TernaryFilter::make('username_changed_at')->nullable(),
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
            RelationManagers\ProjectsRelationManager::class,
            RelationManagers\MaintainingRelationManager::class,
            RelationManagers\CollectionsRelationManager::class,
            RelationManagers\ReleasesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicForm;
use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Domain;
use App\Services\DomainService;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class DomainResource extends CustomResource
{
    protected static ?string $model = Domain::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|UnitEnum|null $navigationGroup = 'Users';

    public static function form(Schema $schema): Schema
    {
        return BasicForm::make($schema, [
            Forms\Components\TextInput::make('name')
                ->maxLength(255)
                ->regex(DomainService::REGEX)
                ->unique(ignoreRecord: true)
                ->required(),
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->relationship('user', 'username')
                ->required(),
            Forms\Components\TextInput::make('verification_code')
                ->maxLength(255)
                ->integer()
                ->minValue(DomainService::MIN_VERIFICATION_CODE)
                ->maxValue(DomainService::MAX_VERIFICATION_CODE)
                ->required(),
            Forms\Components\DateTimePicker::make('verified_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username'),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable()->toggleable()->limit(30),
                Tables\Columns\TextColumn::make('verification_code')->sortable(),
                Tables\Columns\TextColumn::make('verified_at')->searchable()->toggleable(),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('verified_at')
                    ->nullable(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}

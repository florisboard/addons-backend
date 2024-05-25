<?php

namespace App\Filament\Resources;

use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicForm;
use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Domain;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class DomainResource extends CustomResource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Users';

    public static function form(Form $form): Form
    {
        return BasicForm::make($form, [
            Forms\Components\TextInput::make('name')
                ->maxLength(255)
                ->required(),
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->relationship('user', 'username')
                ->required(),
            Forms\Components\TextInput::make('verification_code')
                ->maxLength(255)
                ->integer()
                ->minValue(100000)
                ->maxValue(999999)
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}

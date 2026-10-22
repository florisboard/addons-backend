<?php

namespace App\Filament\Resources;

use App\Enums\StatusEnum;
use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicSection;
use App\Filament\Forms\Layouts\ComplexForm;
use App\Filament\Forms\Layouts\StatusSection;
use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Review;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ReviewResource extends CustomResource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';

    protected static string|UnitEnum|null $navigationGroup = 'Projects';

    public static function getNavigationBadge(): ?string
    {
        return (string) Review::where('status', StatusEnum::UnderReview)->count();
    }

    public static function form(Schema $schema): Schema
    {
        $basicSection = BasicSection::make([
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->hiddenOn([UserResource\RelationManagers\ReviewsRelationManager::class])
                ->relationship('user', 'username')
                ->required(),
            Forms\Components\Select::make('project_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->hiddenOn([ProjectResource\RelationManagers\ReviewsRelationManager::class])
                ->relationship('project', 'package_name')
                ->required(),
            Forms\Components\TextInput::make('title')
                ->maxLength(255)
                ->required(),
            Forms\Components\TextInput::make('score')
                ->minValue(1)
                ->maxValue(5)
                ->integer()
                ->required(),
            Forms\Components\MarkdownEditor::make('description')
                ->columnSpanFull()
                ->required(),
        ]);

        $statusSection = StatusSection::make(includeStatusSelect: true);

        return ComplexForm::make($schema, [$basicSection], [$statusSection]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('user.username')
                    ->hiddenOn([UserResource\RelationManagers\ReviewsRelationManager::class]),
                Tables\Columns\TextColumn::make('project.title')
                    ->hiddenOn([ProjectResource\RelationManagers\ReviewsRelationManager::class]),
                Tables\Columns\TextColumn::make('score')
                    ->sortable()
                    ->badge()
                    ->color(function (int $state) {
                        return match ($state) {
                            1, 2 => 'danger',
                            3, 4 => 'orange',
                            default => 'primary',
                        };
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->toggleable(),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->searchable()
                    ->options(StatusEnum::class),
            ])
            ->recordActions([
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReportsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}

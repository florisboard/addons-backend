<?php

namespace App\Filament\Resources;

use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicSection;
use App\Filament\Forms\Layouts\ComplexForm;
use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends CustomResource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center';

    public static function form(Form $form): Form
    {
        $basicSection = BasicSection::make([
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->hiddenOn([UserResource\RelationManagers\ReviewsRelationManager::class])
                ->relationship('user', 'username')
                ->required(),
            Forms\Components\Select::make('project_id')
                ->searchable()
                ->preload()
                ->hiddenOn([ProjectResource\RelationManagers\ReviewsRelationManager::class])
                ->relationship('project', 'name')
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

        return ComplexForm::make($form, [$basicSection]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->hiddenOn([UserResource\RelationManagers\ReviewsRelationManager::class]),
                Tables\Columns\TextColumn::make('project.name')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}

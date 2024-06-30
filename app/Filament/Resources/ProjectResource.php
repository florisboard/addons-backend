<?php

namespace App\Filament\Resources;

use App\Enums\ProjectTypeEnum;
use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Components\ImageInput;
use App\Filament\Forms\Layouts\BasicSection;
use App\Filament\Forms\Layouts\ComplexForm;
use App\Filament\Forms\Layouts\ImagesSection;
use App\Filament\Forms\Layouts\StatusSection;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends CustomResource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';

    protected static ?string $navigationGroup = 'Projects';

    public static function form(Form $form): Form
    {
        $basicSection = BasicSection::make([
            Forms\Components\TextInput::make('title')
                ->maxLength(255)
                ->required(),
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->relationship('user', 'username')
                ->hiddenOn([UserResource\RelationManagers\ProjectsRelationManager::class])
                ->required(),
            Forms\Components\Select::make('category_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->relationship('category', 'title')
                ->hiddenOn([CategoryResource\RelationManagers\ProjectsRelationManager::class])
                ->required(),
            Forms\Components\TextInput::make('package_name')
                ->maxLength(255)
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('type')
                ->options(ProjectTypeEnum::class)
                ->required(),
            Forms\Components\TextInput::make('links.source_code')
                ->url()
                ->maxLength(255),
            Forms\Components\MarkdownEditor::make('description')
                ->columnSpanFull()
                ->required(),
            Forms\Components\Textarea::make('short_description')
                ->maxLength(255)
                ->columnSpanFull()
                ->required(),
            Forms\Components\Select::make('maintainers')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->multiple()
                ->relationship('maintainers', 'username'),
        ]);

        $imagesSection = ImagesSection::make([
            ImageInput::make('image'),
            ImageInput::make('screenshots', true),
        ]);

        $statusSection = StatusSection::make([
            Forms\Components\Toggle::make('is_recommended'),
            Forms\Components\Toggle::make('is_active'),
        ]);

        return ComplexForm::make($form, [$basicSection, $imagesSection], [$statusSection]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\IconColumn::make('is_recommended')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('package_name')->sortable()->searchable(isIndividual: true)->toggleable(),
                Tables\Columns\TextColumn::make('description')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                Tables\Columns\TextColumn::make('short_description')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                Tables\Columns\TextColumn::make('home_page')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                Tables\Columns\TextColumn::make('support_email')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                Tables\Columns\TextColumn::make('support_site')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                Tables\Columns\TextColumn::make('donate_site')->searchable(isIndividual: true)->toggleable()->toggledHiddenByDefault()->limit(30),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_recommended'),
                Tables\Filters\TernaryFilter::make('is_active'),
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
            RelationManagers\ChangeProposalsRelationManager::class,
            RelationManagers\ReleasesRelationManager::class,
            RelationManagers\CollectionsRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\ReportsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Enums\ChangeProposalStatusEnum;
use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicSection;
use App\Filament\Forms\Layouts\ComplexForm;
use App\Filament\Forms\Layouts\StatusSection;
use App\Filament\Resources\ChangeProposalResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\ChangeProposal;
use App\Models\Project;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Novadaemon\FilamentPrettyJson\PrettyJson;

class ChangeProposalResource extends CustomResource
{
    protected static ?string $model = ChangeProposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        $basicSection = BasicSection::make([
            Forms\Components\Textarea::make('reviewer_description')
                ->minLength(3)
                ->columnSpanFull()
                ->maxLength(1024),
            PrettyJson::make('data')
                ->columnSpanFull(),
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->columnSpanFull()
                ->relationship('user', 'username'),
            Forms\Components\MorphToSelect::make('model')
                ->columnSpanFull()
                ->searchable()
                ->preload()
                ->optionsLimit(50)
                ->types([
                    MorphToSelect\Type::make(Project::class)
                        ->titleAttribute('package_name'),
                    MorphToSelect\Type::make(Review::class)
                        ->titleAttribute('id'),
                ]),
        ]);

        $statusSection = StatusSection::make([
            Forms\Components\Select::make('status')
                ->searchable()
                ->options(ChangeProposalStatusEnum::class)
                ->required(),
        ]);

        return ComplexForm::make($form, [$basicSection], [$statusSection]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.username')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('model_type')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('model_id')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('reviewer_description')->toggleable()->limit(60),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->searchable()
                    ->options(ChangeProposalStatusEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_model')
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->url(function (ChangeProposal $changeProposal): string {
                        $resource = match ($changeProposal->model_type) {
                            Project::class => 'projects',
                            default => throw new \RuntimeException('This reportable type is not handled.')
                        };

                        return route("filament.admin.resources.$resource.edit", $changeProposal->model_id);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListChangeProposals::route('/'),
            'create' => Pages\CreateChangeProposal::route('/create'),
            'edit' => Pages\EditChangeProposal::route('/{record}/edit'),
        ];
    }
}

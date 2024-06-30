<?php

namespace App\Filament\Resources;

use App\Enums\ReportTypeEnum;
use App\Filament\Custom\CustomResource;
use App\Filament\Forms\Layouts\BasicForm;
use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Tables\Components\TimestampsColumn;
use App\Models\Project;
use App\Models\Report;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class ReportResource extends CustomResource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return BasicForm::make($form, [
            Forms\Components\Select::make('user_id')
                ->searchable()
                ->preload()
                ->relationship('user', 'username')
                ->required(),
            Forms\Components\Select::make('type')
                ->options(ReportTypeEnum::class)
                ->searchable()
                ->required(),
            Forms\Components\Textarea::make('description')
                ->minLength(3)
                ->columnSpanFull()
                ->maxLength(1024),
            Forms\Components\MorphToSelect::make('reportable')
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
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_reviewed')->boolean(),
                Tables\Columns\TextColumn::make('user.username')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('reportable_type')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('reportable_id')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('description')->toggleable()->limit(60),
                Tables\Columns\TextColumn::make('reviewed_at')->toggleable()->dateTime()->toggledHiddenByDefault(),
                ...TimestampsColumn::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->searchable()
                    ->options(ReportTypeEnum::class),
                Tables\Filters\TernaryFilter::make('reviewed_at')
                    ->label('Reviewed')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_model')
                    ->icon('heroicon-o-eye')
                    ->openUrlInNewTab()
                    ->url(function (Report $report): string {
                        $resource = match ($report->reportable_type) {
                            Project::class => 'projects',
                            Review::class => 'reviews',
                            default => throw new \RuntimeException('This reportable type is not handled.')
                        };

                        return route("filament.admin.resources.$resource.edit", $report->reportable_id);
                    }),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;

class TimestampsSection
{
    public static function make(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Timestamps')
            ->schema([
                Forms\Components\Placeholder::make('created_at')
                    ->content(fn ($record): ?string => $record->created_at?->diffForHumans()),

                Forms\Components\Placeholder::make('updated_at')
                    ->content(fn ($record): ?string => $record->updated_at?->diffForHumans()),
            ])
            ->hidden(fn ($record) => $record === null);
    }
}

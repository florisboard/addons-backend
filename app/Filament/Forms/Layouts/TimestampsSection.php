<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;
use Filament\Schemas\Components\Section;

class TimestampsSection
{
    public static function make(): Section
    {
        return Section::make('Timestamps')
            ->components([
                Forms\Components\Placeholder::make('created_at')
                    ->content(fn ($record): ?string => $record->created_at?->diffForHumans()),

                Forms\Components\Placeholder::make('updated_at')
                    ->content(fn ($record): ?string => $record->updated_at?->diffForHumans()),
            ])
            ->hidden(fn ($record) => $record === null);
    }
}

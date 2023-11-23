<?php

namespace App\Filament\Tables\Components;

use Filament\Tables;

class TimestampsColumn
{
    /**
     * @return Tables\Columns\TextColumn[]
     */
    public static function make(): array
    {
        return [
            Tables\Columns\TextColumn::make('created_at')->sortable()->dateTime()->toggleable(),
            Tables\Columns\TextColumn::make('updated_at')->sortable()->dateTime()->toggleable(),
        ];
    }
}

<?php

namespace App\Filament\Forms\Layouts;

use App\Enums\StatusEnum;
use Filament\Forms;

class StatusSection
{
    /**
     * @param  array<Forms\Components\Component>  $components
     */
    public static function make(array $components = [], bool $includeIsActive = false, bool $includeStatusSelect = false): Forms\Components\Section
    {
        if ($includeIsActive) {
            array_unshift(
                $components,
                Forms\Components\Toggle::make('is_active'),
            );
        }

        if ($includeStatusSelect) {
            array_unshift(
                $components,
                Forms\Components\Select::make('status')
                    ->searchable()
                    ->options(StatusEnum::class)
                    ->required(),
            );
        }

        return Forms\Components\Section::make('Status')
            ->schema($components);
    }
}

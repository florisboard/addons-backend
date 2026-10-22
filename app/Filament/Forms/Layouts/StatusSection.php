<?php

namespace App\Filament\Forms\Layouts;

use App\Enums\StatusEnum;
use Filament\Forms;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;

class StatusSection
{
    /**
     * @param  array<Component>  $components
     */
    public static function make(array $components = [], bool $includeIsActive = false, bool $includeStatusSelect = false): Section
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

        return Section::make('Status')
            ->components($components);
    }
}

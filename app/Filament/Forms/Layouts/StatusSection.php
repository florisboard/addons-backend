<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;

class StatusSection
{
    /**
     * @param  array<Forms\Components\Component>  $components
     */
    public static function make(array $components = [], bool $includeIsActive = false): Forms\Components\Section
    {
        if ($includeIsActive) {
            array_unshift(
                $components,
                Forms\Components\Toggle::make('is_active'),
            );
        }

        return Forms\Components\Section::make('Status')
            ->schema($components);
    }
}

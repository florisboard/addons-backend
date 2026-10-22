<?php

namespace App\Filament\Forms\Layouts;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;

class BasicSection
{
    /**
     * @param  array<Component>  $components
     */
    public static function make(array $components): Section
    {
        return Section::make()
            ->components($components)
            ->columnSpanFull()
            ->columns(2);
    }
}

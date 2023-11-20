<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;

class BasicSection
{
    /**
     * @param  array<Forms\Components\Component>  $components
     */
    public static function make(array $components): Forms\Components\Section
    {
        return Forms\Components\Section::make()
            ->schema($components)
            ->columns(2);
    }
}

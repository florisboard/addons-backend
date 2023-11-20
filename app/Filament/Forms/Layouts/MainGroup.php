<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;

class MainGroup
{
    /**
     * @param  array<Forms\Components\Component>  $components
     */
    public static function make(array $components): Forms\Components\Group
    {
        return Forms\Components\Group::make($components)
            ->columnSpan(2)
            ->columns(2);
    }
}

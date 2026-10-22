<?php

namespace App\Filament\Forms\Layouts;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;

class MainGroup
{
    /**
     * @param  array<Component>  $components
     */
    public static function make(array $components): Group
    {
        return Group::make($components)
            ->columnSpan(2)
            ->columns(2);
    }
}

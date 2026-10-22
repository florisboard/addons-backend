<?php

namespace App\Filament\Forms\Layouts;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;

class SideGroup
{
    /**
     * @param  array<Component>  $components
     */
    public static function make(array $components): Group
    {
        return Group::make($components)->columns(['lg' => 1]);
    }
}

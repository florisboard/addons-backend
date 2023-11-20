<?php

namespace App\Filament\Forms\Layouts;

use Filament\Forms;

class SideGroup
{
    /**
     * @param  array<Forms\Components\Component>  $components
     */
    public static function make(array $components): Forms\Components\Group
    {
        return Forms\Components\Group::make($components)->columns(['lg' => 1]);
    }
}

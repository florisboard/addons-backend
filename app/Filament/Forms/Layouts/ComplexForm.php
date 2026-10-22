<?php

namespace App\Filament\Forms\Layouts;

use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class ComplexForm
{
    /**
     * @param  array<Component>  $components
     * @param  array<Component>  $sideComponents
     */
    public static function make(Schema $schema, array $components = [], array $sideComponents = []): Schema
    {
        array_unshift($sideComponents, TimestampsSection::make());

        return $schema->components([MainGroup::make($components), SideGroup::make($sideComponents)])
            ->columns(3);
    }
}
